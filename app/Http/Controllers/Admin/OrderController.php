<?php

namespace App\Http\Controllers\Admin;

use App\Mail\OrderThankYouMail;
use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderStatusHistory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rule;

class OrderController extends Controller
{
    private const STATUS_META = [
        'pending' => ['label' => 'Chờ xác nhận', 'class' => 'warning'],
        'processing' => ['label' => 'Đang xử lý', 'class' => 'info'],
        'shipped' => ['label' => 'Đang giao', 'class' => 'primary'],
        'completed' => ['label' => 'Hoàn thành', 'class' => 'success'],
        'cancelled' => ['label' => 'Đã hủy', 'class' => 'danger'],
        'canceled' => ['label' => 'Đã hủy', 'class' => 'danger'],
    ];

    public function show_orders(Request $request)
    {
        /** @var \App\Models\User|null $adminUser */
        $adminUser = auth('admin')->user();

        if (! $adminUser || ! $adminUser->hasPermission('orders.view')) {
            abort(403, 'Bạn không có quyền truy cập trang này.');
        }

        $ordersQuery = Order::query()
            ->with(['user', 'shippingAddress', 'payment', 'orderItems.product'])
            ->orderByDesc('id');

        if ($request->filled('keyword')) {
            $keyword = trim($request->input('keyword'));
            $ordersQuery->where(function ($query) use ($keyword) {
                $query->where('id', $keyword)
                    ->orWhereHas('user', function ($userQuery) use ($keyword) {
                        $userQuery->where('name', 'like', '%' . $keyword . '%')
                            ->orWhere('email', 'like', '%' . $keyword . '%');
                    })
                    ->orWhereHas('shippingAddress', function ($shippingQuery) use ($keyword) {
                        $shippingQuery->where('full_name', 'like', '%' . $keyword . '%')
                            ->orWhere('phone', 'like', '%' . $keyword . '%');
                    });
            });
        }

        if ($request->filled('status')) {
            $ordersQuery->where('status', $request->input('status'));
        }

        $filteredOrdersCount = (clone $ordersQuery)->count();
        $orders = $ordersQuery->paginate(10)->appends($request->query());

        $totalOrders = Order::count();
        $pendingOrders = Order::where('status', 'pending')->count();
        $processingOrders = Order::where('status', 'processing')->count();
        $shippedOrders = Order::where('status', 'shipped')->count();
        $completedOrders = Order::where('status', 'completed')->count();
        $cancelledOrders = Order::whereIn('status', ['cancelled', 'canceled'])->count();

        return view('admin.pages.orders.show_orders', [
            'orders' => $orders,
            'totalOrders' => $totalOrders,
            'pendingOrders' => $pendingOrders,
            'processingOrders' => $processingOrders,
            'shippedOrders' => $shippedOrders,
            'completedOrders' => $completedOrders,
            'cancelledOrders' => $cancelledOrders,
            'filteredOrdersCount' => $filteredOrdersCount,
            'statusMeta' => self::STATUS_META,
        ]);
    }

    public function detail_order(Order $order)
    {
        /** @var \App\Models\User|null $adminUser */
        $adminUser = auth('admin')->user();

        if (! $adminUser || ! $adminUser->hasPermission('orders.view')) {
            abort(403, 'Bạn không có quyền truy cập trang này.');
        }

        $order->loadMissing(['user', 'shippingAddress', 'payment', 'orderItems.product', 'orderStatusHistories']);

        $subTotal = (float) $order->orderItems->sum(function ($item) {
            return (float) $item->price * (int) $item->quantity;
        });
        $discountAmount = (float) ($order->discount_amount ?? 0);

        $invoiceData = [
            'id' => $order->id,
            'created_at' => $order->created_at?->format('H:i d/m/Y'),
            'status' => $order->status,
            'sub_total' => $subTotal,
            'discount_amount' => $discountAmount,
            'coupon_code' => $order->coupon_code,
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

        return view('admin.pages.orders.order_detail', [
            'order' => $order,
            'invoiceData' => $invoiceData,
            'statusMeta' => self::STATUS_META,
        ]);
    }

    public function updateStatus(Request $request, Order $order): RedirectResponse
    {
        /** @var \App\Models\User|null $adminUser */
        $adminUser = auth('admin')->user();

        if (! $adminUser || ! $adminUser->hasPermission('orders.manage')) {
            abort(403, 'Bạn không có quyền cập nhật trạng thái đơn hàng.');
        }

        $validated = $request->validate([
            'status' => ['required', Rule::in(['pending', 'processing', 'shipped', 'completed', 'cancelled'])],
            'note' => ['nullable', 'string', 'max:500'],
        ]);

        $originalStatus = $order->status;
        $newStatus = $validated['status'];

        if ($originalStatus !== $newStatus) {
            $order->status = $newStatus;
            $order->save();

            $history = new OrderStatusHistory();
            $history->order_id = $order->id;
            $history->status = $newStatus;
            $history->note = $validated['note'] ?? ('Cập nhật từ ' . $originalStatus . ' sang ' . $newStatus);
            $history->changed_at = now();
            $history->save();

            // Đã xóa logic trừ stock ở đây vì stock đã được trừ ngay lúc khách đặt hàng (CheckoutController)

            // Hoàn lại stock nếu đơn hàng bị hủy
            if ($newStatus === 'cancelled' && $originalStatus !== 'cancelled') {
                $order->loadMissing(['orderItems.product']);
                foreach ($order->orderItems as $item) {
                    if ($item->product) {
                        $item->product->increment('stock', $item->quantity);
                        // Cập nhật trạng thái back to in_stock
                        if ($item->product->stock > 0 && $item->product->status === 'out_of_stock') {
                            $item->product->status = 'in_stock';
                            $item->product->save();
                        }
                    }
                }
            }

            if ($originalStatus === 'pending' && in_array($newStatus, ['processing', 'shipped', 'completed'], true)) {
                try {
                    $order->loadMissing(['user', 'shippingAddress', 'payment', 'orderItems.product']);
                    Mail::to($order->user?->email)->queue(new OrderThankYouMail($order, 'confirmed'));
                } catch (\Throwable $mailException) {
                    report($mailException);
                }
            }
        }

        flash('Cập nhật trạng thái đơn hàng thành công.', 'success');

        return redirect()->route('admin.orders.list');
    }

    public function send_invoice(Order $order): RedirectResponse
    {
        /** @var \App\Models\User|null $adminUser */
        $adminUser = auth('admin')->user();

        if (! $adminUser || ! $adminUser->hasPermission('orders.manage')) {
            abort(403, 'Bạn không có quyền gửi hóa đơn.');
        }

        try {
            $order->loadMissing(['user', 'shippingAddress', 'payment', 'orderItems.product']);
            Mail::to($order->user?->email)->queue(new OrderThankYouMail($order, 'invoice'));
            flash('Hóa đơn đã được gửi tới email: ' . $order->user?->email, 'success');
        } catch (\Throwable $mailException) {
            report($mailException);
            flash('Lỗi gửi email. Vui lòng thử lại.', 'error');
        }

        return redirect()->route('admin.orders.detail', $order);
    }
}
