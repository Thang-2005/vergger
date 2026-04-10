<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Product;
use App\Models\Category;
use App\Models\CartItem;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\ShippingAddress;

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
            return redirect()->route('cart')->with('warning', 'Giỏ hàng trống!');
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

        return view('clients.pages.checkout', [
            'cartItems' => $cartItems,
            'totalPrice' => $totalPrice,
            'addresses' => $addresses,
            'defaultAddress' => $defaultAddress,
            'user' => Auth::user(),
        ]);
    }

    public function store(Request $request)
    {
        // Validate input
        $validated = $request->validate([
            'shipping_address_id' => 'nullable|exists:shipping_address,id',
            'full_name' => 'required_if:shipping_address_id,null|string|max:255',
            'phone' => 'required_if:shipping_address_id,null|string|max:20',
            'address' => 'required_if:shipping_address_id,null|string|max:500',
            'city' => 'required_if:shipping_address_id,null|string|max:100',
            'payment_method' => 'required|in:cod,vnpay',
        ], [
            'full_name.required_if' => 'Vui lòng nhập tên người nhận',
            'phone.required_if' => 'Vui lòng nhập số điện thoại',
            'address.required_if' => 'Vui lòng nhập địa chỉ',
            'city.required_if' => 'Vui lòng nhập thành phố',
            'payment_method.required' => 'Vui lòng chọn phương thức thanh toán',
            'payment_method.in' => 'Phương thức thanh toán không hợp lệ',
        ]);

        // Lấy cart items
        $cartItems = CartItem::where('user_id', Auth::id())->with('product')->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Giỏ hàng trống!');
        }

        // Xác định địa chỉ giao hàng
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

        // Tính tổng giá
        $totalPrice = $cartItems->sum(function ($item) {
            return $item->product->price * $item->quantity;
        });

        try {
            DB::transaction(function () use ($cartItems, $shippingAddressId, $totalPrice, $validated) {
                // Tạo order
                $order = Order::create([
                    'user_id' => Auth::id(),
                    'total_price' => $totalPrice,
                    'status' => 'pending',
                    'shipping_address_id' => $shippingAddressId,
                ]);

                $paymentStatus = 'pending';

                Payment::create([
                    'order_id' => $order->id,
                    'payment_method' => $validated['payment_method'],
                    'transaction_id' => null,
                    'amount' => $totalPrice,
                    'status' => $paymentStatus,
                    'paid_at' => null,
                ]);

                // Tạo order items và trừ tồn kho
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

                // Xóa cart items sau khi order được tạo
                CartItem::where('user_id', Auth::id())->delete();
            });
        } catch (\RuntimeException $e) {
            return redirect()->route('cart.index')->with('error', $e->getMessage());
        }

        return redirect()->route('account.orders')->with('success', 'Đơn hàng được tạo thành công! Kiểm tra tại mục "Đơn hàng" trong tài khoản của bạn.');
    }
}
