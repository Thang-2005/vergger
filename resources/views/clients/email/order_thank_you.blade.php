@php
    $shipping = $order->shippingAddress;
    $paymentMethod = match ($order->payment?->payment_method) {
        'cash', 'cod' => 'Thanh toán khi nhận hàng (COD)',
        'vnpay' => 'VNPAY',
        'paypal' => 'PayPal',
        default => 'Chưa cập nhật',
    };
    $paymentStatus = match ($order->payment?->status) {
        'paid' => 'Đã thanh toán',
        'pending' => 'Chưa thanh toán',
        default => 'Chưa cập nhật',
    };
    $orderStatus = match ($order->status) {
        'pending' => 'Chờ xác nhận',
        'processing' => 'Đang xử lý',
        'shipped' => 'Đang giao',
        'completed' => 'Hoàn thành',
        'canceled' => 'Đã hủy',
        default => $order->status,
    };
    $subjectLine = $context === 'confirmed'
        ? 'Đơn hàng được xác nhận'
        : 'Cảm ơn bạn đã đặt hàng';
    $detailUrl = route('order.detail', $order->id);
@endphp

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: #2f6d3a; color: white; padding: 20px; text-align: center; border-radius: 4px 4px 0 0; }
        .header h1 { margin: 0; font-size: 24px; }
        .content { background: #f9f9f9; padding: 20px; border: 1px solid #ddd; border-radius: 0 0 4px 4px; }
        .section { margin: 15px 0; }
        .section-title { font-weight: bold; color: #2f6d3a; border-bottom: 2px solid #2f6d3a; padding-bottom: 8px; margin-bottom: 10px; }
        .info-row { display: flex; margin: 8px 0; }
        .info-label { font-weight: bold; width: 150px; color: #555; }
        .info-value { flex: 1; color: #333; }
        table { width: 100%; border-collapse: collapse; margin: 15px 0; }
        th { background: #eef4ef; border: 1px solid #ddd; padding: 10px; text-align: left; font-weight: bold; color: #2f6d3a; }
        td { border: 1px solid #ddd; padding: 10px; }
        .total-row { font-weight: bold; background: #eef4ef; }
        .button { display: inline-block; background: #2f6d3a; color: white; padding: 12px 24px; text-decoration: none; border-radius: 4px; margin: 15px 0; }
        .footer { text-align: center; margin-top: 20px; color: #666; font-size: 12px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>VEGGIE</h1>
            <p>{{ $subjectLine }}</p>
        </div>
        <div class="content">
            <p>Xin chào <strong>{{ $order->user?->name ?? 'bạn' }}</strong>,</p>

            @if ($context === 'confirmed')
                <p>Cảm ơn bạn! Đơn hàng <strong>#{{ $order->id }}</strong> của bạn đã được xác nhận và đang được xử lý. Chúng tôi sẽ tiếp tục cập nhật trạng thái khi đơn hàng được đóng gói và giao đi.</p>
            @else
                <p>Chúng tôi đã nhận được đơn hàng <strong>#{{ $order->id }}</strong> của bạn. Cảm ơn bạn đã tin tưởng Veggie!</p>
            @endif

            <div class="section">
                <div class="section-title">Thông tin đơn hàng</div>
                <div class="info-row">
                    <div class="info-label">Mã đơn hàng:</div>
                    <div class="info-value">#{{ $order->id }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Ngày đặt:</div>
                    <div class="info-value">{{ $order->created_at?->format('H:i d/m/Y') }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Trạng thái:</div>
                    <div class="info-value">{{ $orderStatus }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Tổng tiền:</div>
                    <div class="info-value"><strong style="color: #2f6d3a; font-size: 18px;">{{ number_format($order->total_price, 0, ',', '.') }} đ</strong></div>
                </div>
            </div>

            <div class="section">
                <div class="section-title">Thông tin thanh toán</div>
                <div class="info-row">
                    <div class="info-label">Phương thức:</div>
                    <div class="info-value">{{ $paymentMethod }}</div>
                </div>
                <div class="info-row">
                    <div class="info-label">Trạng thái:</div>
                    <div class="info-value"><strong>{{ $paymentStatus }}</strong></div>
                </div>
            </div>

            @if ($shipping)
                <div class="section">
                    <div class="section-title">Địa chỉ nhận hàng</div>
                    <div class="info-row">
                        <div class="info-label">Tên người nhận:</div>
                        <div class="info-value">{{ $shipping->full_name }}</div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Số điện thoại:</div>
                        <div class="info-value">{{ $shipping->phone }}</div>
                    </div>
                    <div class="info-row">
                        <div class="info-label">Địa chỉ:</div>
                        <div class="info-value">{{ $shipping->address }}, {{ $shipping->city }}</div>
                    </div>
                </div>
            @endif

            <div class="section">
                <div class="section-title">Chi tiết sản phẩm</div>
                <table>
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Sản phẩm</th>
                            <th style="text-align: center;">SL</th>
                            <th style="text-align: right;">Đơn giá</th>
                            <th style="text-align: right;">Thành tiền</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($order->orderItems as $index => $item)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $item->product?->name ?? 'Sản phẩm đã xóa' }}</td>
                                <td style="text-align: center;">{{ $item->quantity }}</td>
                                <td style="text-align: right;">{{ number_format($item->price, 0, ',', '.') }} đ</td>
                                <td style="text-align: right;">{{ number_format($item->price * $item->quantity, 0, ',', '.') }} đ</td>
                            </tr>
                        @endforeach
                        <tr class="total-row">
                            <td colspan="4" style="text-align: right;">Tổng cộng:</td>
                            <td style="text-align: right;">{{ number_format($order->total_price, 0, ',', '.') }} đ</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div style="text-align: center; margin: 20px 0;">
                <a href="{{ $detailUrl }}" class="button">Xem chi tiết đơn hàng</a>
            </div>

            <p style="color: #666; font-size: 13px; line-height: 1.8;">
                <strong>Lưu ý:</strong> Bạn có thể xem chi tiết và tải hóa đơn PDF từ link trên. Nếu bạn có bất kỳ câu hỏi nào, vui lòng liên hệ với chúng tôi qua hotline 0900 000 000 hoặc email support@veggie.local.
            </p>
        </div>
        <div class="footer">
            <p>Trân trọng,<br>Đội ngũ Veggie</p>
            <p style="margin-top: 15px; color: #999;">&copy; {{ date('Y') }} Veggie. All rights reserved.</p>
        </div>
    </div>
</body>
</html>