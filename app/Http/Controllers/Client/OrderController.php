<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;

class OrderController extends Controller
{
    public function showOrder($id)
    {
        $query = Order::with(['orderItems.product.firstImage', 'shippingAddress']);

        if (Schema::hasTable('payments')) {
            $query->with('payment');
        }

        $order = $query->findOrFail($id);

        if ($order->user_id != Auth::id()) {
            return redirect()->route('account.orders')->with('error', 'Bạn không có quyền xem chi tiết đơn hàng này');
        }
        
        $paymentMethod = 'Chua cap nhat';

        if (Schema::hasTable('payments') && $order->relationLoaded('payment') && $order->payment) {
            $paymentMethod = match ($order->payment->payment_method) {
                'cash', 'cod' => 'Thanh toán khi nhận hàng',
                'vnpay', 'paypal' => 'VNPAY',
                default => 'Chua cap nhat',
            };
        }

        $invoiceData = [
            'id' => $order->id,
            'created_at' => $order->created_at?->format('H:i d/m/Y'),
            'status' => $order->status,
            'total_price' => $order->total_price,
            'payment_method' => $order->payment?->payment_method,
            'payment_status' => $order->payment?->status,
            'payment_status_label' => match ($order->payment?->status) {
                'paid' => 'Đã thanh toán',
                'pending' => 'Chưa thanh toán',
                default => 'Chưa cập nhật',
            },
            'shipping' => $order->shippingAddress ? [
                'full_name' => $order->shippingAddress->full_name,
                'phone' => $order->shippingAddress->phone,
                'address' => $order->shippingAddress->address,
                'city' => $order->shippingAddress->city,
            ] : null,
            'items' => $order->orderItems->map(function ($item) {
                return [
                    'name' => $item->product?->name ?? 'Sản phẩm đã xóa',
                    'quantity' => $item->quantity,
                    'price' => (float) $item->price,
                    'total' => (float) $item->price * $item->quantity,
                ];
            })->values(),
        ];

        return view('clients.pages.order_detail', compact('order', 'paymentMethod', 'invoiceData'));
    }

    public function cancel_order(Request $request, $id)
    {
        $order = Order::findOrFail($id);

        if ($order->user_id != Auth::id()) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Bạn không có quyền hủy đơn hàng này'
                ], 403);
            }

            return redirect()->back()->with('error', 'Bạn không có quyền hủy đơn hàng này');
        }

        if ($order->status != 'pending') {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Đơn hàng đã được xác nhận hoặc đang giao, bạn không thể hủy'
                ], 400);
            }

            return redirect()->back()->with('error', 'Đơn hàng đã được xác nhận hoặc đang giao, bạn không thể hủy');
        }

        DB::transaction(function () use ($order) {
            $order->load('orderItems.product');

            foreach ($order->orderItems as $orderItem) {
                $product = $orderItem->product;

                if (!$product) {
                    continue;
                }

                $product->stock += $orderItem->quantity;
                if ($product->stock > 0 && $product->status === 'out_of_stock') {
                    $product->status = 'in_stock';
                }
                $product->save();
            }

            $order->status = 'canceled';
            $order->save();
        });

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'Đơn hàng đã được hủy thành công'
            ]);
        }

        return redirect()->back()->with('success', 'Đơn hàng đã được hủy thành công');
    }
}
