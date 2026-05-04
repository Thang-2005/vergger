<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\Client\CheckoutRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use App\Models\Product;
use App\Models\CartItem;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\ShippingAddress;
use App\Models\Coupon;
use App\Services\VnpayService;
use App\Mail\OrderThankYouMail;

class CheckoutController extends Controller
{
    public function index()
    {
        // Lấy cart items của user hiện tại
        $cartItems = CartItem::where('user_id', Auth::id())
            ->with('product')
            ->get();

        // Nếu giỏ hàng trống, redirect về cart
        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('warning', 'Giỏ hàng trống!');
        }

        // Tính tổng giá
        $totalPrice = $cartItems->sum(function ($item) {
            return $item->product->price * $item->quantity;
        });

        // Lấy địa chỉ giao hàng của user
        $addresses = ShippingAddress::where('user_id', Auth::id())->get();
        $defaultAddress = ShippingAddress::where('user_id', Auth::id())
            ->where('default', 1)
            ->first();

        if ($addresses->isNotEmpty()) {
            session()->flash('info', 'Lưu ý: Nếu bạn chọn địa chỉ có sẵn, các trường địa chỉ mới sẽ được bỏ qua.');
        }

        [$appliedCoupon, $discountAmount] = $this->resolveAppliedCoupon($totalPrice);

        return view('clients.pages.checkout', [
            'cartItems' => $cartItems,
            'totalPrice' => $totalPrice,
            'addresses' => $addresses,
            'defaultAddress' => $defaultAddress,
            'user' => Auth::user(),
            'appliedCoupon' => $appliedCoupon,
            'discountAmount' => $discountAmount,
            'finalPrice' => max(0, $totalPrice - $discountAmount),
        ]);
    }

    public function store(CheckoutRequest $request)
    {
        // === BƯỚC 1: KIỂM TRA DỮ LIỆU ĐẦU VÀO ===
        $validated = $request->validated();

        // === BƯỚC 2: LẤY GIỎ HÀNG ===
        $cartItems = CartItem::where('user_id', Auth::id())->with('product')->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Giỏ hàng trống!');
        }

        // === BƯỚC 3: XÁC ĐỊNH ĐỊA CHỈ GIAO HÀNG ===
        $shippingAddressId = $validated['shipping_address_id'] ?? null;

        if (!$shippingAddressId) {
            // Tạo địa chỉ giao hàng mới nếu không chọn địa chỉ có sẵn
            $address = ShippingAddress::create([
                'user_id' => Auth::id(),
                'full_name' => $validated['full_name'],
                'phone' => $validated['phone'],
                'address' => $validated['address'],
                'city' => $validated['city'],
                'default' => 0,
            ]);
            $shippingAddressId = $address->id;
        }

        // === BƯỚC 4: TÍNH TỔNG TIỀN ===
        $totalPrice = $cartItems->sum(function ($item) {
            return $item->product->price * $item->quantity;
        });

        // === BƯỚC 5: KIỂM TRA VÀ TÍNH TOÁN MÃ GIẢM GIÁ ===
        // Re-validate mã giảm giá từ session và database
        // Nếu mã không còn hợp lệ (hết hạn, hết lượt, v.v.), sẽ tự động xóa
        [$appliedCoupon, $discountAmount] = $this->resolveAppliedCoupon($totalPrice);

        // === BƯỚC 6: TÍNH TỔNG TIỀN CUỐI CÙNG ===
        // Tổng tiền sau khi trừ giảm giá (không được âm)
        $finalPrice = max(0, $totalPrice - $discountAmount);

        $order = null;

        try {
            DB::transaction(function () use (&$order, $cartItems, $shippingAddressId, $finalPrice, $discountAmount, $validated, $appliedCoupon) {
                // === BƯỚC 7: TẠO ĐƠN HÀNG ===
                $order = Order::create([
                    'user_id' => Auth::id(),
                    'total_price' => $finalPrice,             // Tổng tiền sau giảm
                    'discount_amount' => $discountAmount,      // Tiền được giảm
                    'coupon_code' => $appliedCoupon['code'] ?? null,  // Mã được áp dụng
                    'status' => 'pending',
                    'shipping_address_id' => $shippingAddressId,
                ]);

                $paymentStatus = 'pending';

                // === BƯỚC 8: TẠO THÔNG TIN THANH TOÁN ===
                Payment::create([
                    'order_id' => $order->id,
                    'payment_method' => $validated['payment_method'],
                    'transaction_id' => null,
                    'amount' => $finalPrice,  // Lưu số tiền thanh toán (sau giảm)
                    'status' => $paymentStatus,
                    'paid_at' => null,
                ]);

                // === BƯỚC 9: TẠO CHI TIẾT ĐƠN HÀNG VÀ CẬP NHẬT TỒN KHO ===
                foreach ($cartItems as $cartItem) {
                    $product = Product::whereKey($cartItem->product_id)->lockForUpdate()->firstOrFail();

                    if ($product->stock < $cartItem->quantity) {
                        throw new \RuntimeException("Sản phẩm {$product->name} không đủ tồn kho.");
                    }

                    $product->stock -= $cartItem->quantity;
                    if ($product->stock <= 0) {
                        $product->status = 'out_of_stock';
                    }
                    $product->save();

                    OrderItem::create([
                        'order_id' => $order->id,
                        'product_id' => $cartItem->product_id,
                        'quantity' => $cartItem->quantity,
                        'price' => $cartItem->product->price,
                    ]);
                }

                // Tăng số lần sử dụng coupon
                if ($appliedCoupon) {
                    $coupon = Coupon::find($appliedCoupon['id']);
                    if ($coupon && $coupon->isValid()) {
                        $coupon->incrementUsage();
                    }
                }

                // Xóa cart items sau khi order được tạo
                CartItem::where('user_id', Auth::id())->delete();

                // Xóa coupon từ session
                session()->forget('applied_coupon');
            });
        } catch (\RuntimeException $e) {
            return redirect()->route('cart.index')->with('error', $e->getMessage());
        }

        if ($order) {
            try {
                $order->load(['user', 'shippingAddress', 'payment', 'orderItems.product']);
                Mail::to($order->user?->email)->queue(new OrderThankYouMail($order, 'placed'));
            } catch (\Throwable $mailException) {
                report($mailException);
            }

            // Xử lý VNPAY payment
            if ($validated['payment_method'] === 'vnpay') {
                try {
                    $vnpayService = new VnpayService();
                    
                    // Format TxnRef đơn giản: timestamp(yyyyMMddHHmmss) + order_id
                    // Ví dụ: 202604141530001 (14 chars)
                    $vnp_OrderId = date('YmdHis') . str_pad($order->id, 3, '0', STR_PAD_LEFT);
                    
                    Log::info('VNPAY TxnRef created', [
                        'order_id' => $order->id,
                        'txn_ref' => $vnp_OrderId,
                        'length' => strlen($vnp_OrderId),
                    ]);
                    
                    // Tạo URL thanh toán
                    $paymentUrl = $vnpayService->createPaymentUrl(
                        $vnp_OrderId,
                        $order->total_price,
                        'Thanh toán đơn hàng #' . $order->id,
                        request()->ip()
                    );
                    
                    // Validate URL created
                    if (empty($paymentUrl) || !str_starts_with($paymentUrl, 'https://')) {
                        throw new \Exception('VNPAY URL không hợp lệ');
                    }
                    
                    // Lưu vnp_txn_ref vào payment table để lookup sau này
                    $payment = $order->payment;
                    $payment->update([
                        'vnp_txn_ref' => $vnp_OrderId,
                    ]);
                    
                    Log::info('VNPAY Payment initiated from checkout', [
                        'order_id' => $order->id,
                        'vnp_order_id' => $vnp_OrderId,
                        'amount' => $order->total_price,
                        'payment_url_valid' => true,
                    ]);
                    
                    // Redirect đến VNPAY
                    return redirect($paymentUrl);
                    
                } catch (\Exception $e) {
                    Log::error('VNPAY Payment URL Error', [
                        'error' => $e->getMessage(),
                        'order_id' => $order->id,
                        'trace' => $e->getTraceAsString(),
                    ]);
                    
                    // Restore inventory và delete order
                    try {
                        DB::transaction(function () use ($order, $cartItems) {
                            foreach ($order->orderItems as $orderItem) {
                                $product = Product::find($orderItem->product_id);
                                if ($product) {
                                    $product->stock += $orderItem->quantity;
                                    if ($product->status === 'out_of_stock') {
                                        $product->status = 'active';
                                    }
                                    $product->save();
                                }
                            }
                            
                            // Restore cart items
                            foreach ($cartItems as $cartItem) {
                                $exists = CartItem::where('user_id', Auth::id())
                                    ->where('product_id', $cartItem->product_id)
                                    ->first();
                                
                                if ($exists) {
                                    $exists->quantity += $cartItem->quantity;
                                    $exists->save();
                                } else {
                                    CartItem::create([
                                        'user_id' => Auth::id(),
                                        'product_id' => $cartItem->product_id,
                                        'quantity' => $cartItem->quantity,
                                    ]);
                                }
                            }
                            
                            // Delete order (cascade delete order_items, payments)
                            $order->delete();
                        });
                    } catch (\Exception $rollbackError) {
                        Log::error('VNPAY Rollback Error', ['error' => $rollbackError->getMessage()]);
                    }
                    
                    return redirect()->route('checkout')
                        ->with('error', 'Lỗi tạo URL thanh toán VNPAY: ' . $e->getMessage() . '. Vui lòng thử lại.');
                }
            }
        }

        flash('Đơn hàng được tạo thành công! Kiểm tra tại mục "Đơn hàng" trong tài khoản của bạn.', 'success');
        return redirect()->route('account.orders');
    }

    /**
     * Xác định mã giảm giá đã áp dụng từ session
     * Re-validate từ DB để đảm bảo mã vẫn còn hợp lệ
     */
    private function resolveAppliedCoupon(float $totalPrice): array
    {
        $sessionCoupon = session('applied_coupon');
        if (!$sessionCoupon || empty($sessionCoupon['id'])) {
            return [null, 0];
        }

        // Lấy mã từ DB
        $coupon = Coupon::find($sessionCoupon['id']);
        if (!$coupon) {
            session()->forget('applied_coupon');
            return [null, 0];
        }

        // Tính toán lại từ DB
        $result = $coupon->calculateDiscount($totalPrice);
        if (!$result['valid']) {
            session()->forget('applied_coupon');
            return [null, 0];
        }

        // Chuẩn hóa dữ liệu
        $normalized = [
            'code' => $coupon->code,
            'id' => $coupon->id,
            'discount_value' => (float) $coupon->discount_value,
            'discount_type' => $coupon->discount_type,
            'discount_amount' => (float) $result['discount'],
        ];

        session(['applied_coupon' => $normalized]);
        return [$normalized, (float) $result['discount']];
    }
}
