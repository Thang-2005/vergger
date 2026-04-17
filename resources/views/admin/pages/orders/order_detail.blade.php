@extends('layouts.admin')

@section('title', __('messages.view_details') . ' #' . $order->id)

@section('content')


<div class="right_col" role="main">
    <div style="display:flex; gap:8px; flex-wrap:wrap; margin-bottom:12px;">
        <a href="#customer-info" class="btn btn-default btn-sm"><i class="fa fa-user"></i> {{ __('messages.customer_info') }}</a>
        <a href="#order-items" class="btn btn-default btn-sm"><i class="fa fa-list"></i> {{ __('messages.product_name') }}</a>
        <a href="#status-history" class="btn btn-default btn-sm"><i class="fa fa-history"></i> {{ __('messages.order_status') }}</a>
        <a href="#order-actions" class="btn btn-default btn-sm"><i class="fa fa-cogs"></i> {{ __('messages.action') }}</a>
    </div>

    <!-- Header -->
    <div class="order-header">
        <div>
            <h3>
                <i class="fa fa-shopping-cart"></i>
                {{ __('messages.view_details') }} #{{ $order->id }}
            </h3>
            <p style="margin:6px 0 0; color:#7a7a7a;">{{ __('messages.manage_order_description') }}</p>
        </div>
        @php
            $statusClass = match($order->status) {
                'pending' => 'label-pending',
                'processing' => 'label-processing',
                'shipped' => 'label-shipped',
                'completed' => 'label-completed',
                'cancelled', 'canceled' => 'label-cancelled',
                default => 'label-default',
            };
        @endphp
        <span class="label {{ $statusClass }}" style="padding: 8px 16px; font-size: 13px;">
            {{ $statusMeta[$order->status]['label'] ?? $order->status }}
        </span>
    </div>

    <div class="clearfix"></div>

    <!-- Customer Info -->
    <div class="row" id="customer-info">
        <div class="col-md-12">
            <div class="x_panel">
                <div class="x_title">
                    <h2><i class="fa fa-user"></i> {{ __('messages.customer_info') }}</h2>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    @if ($order->shippingAddress)
                        <div class="customer-info-card">
                            <div class="info-item">
                                <i class="fa fa-user-circle"></i>
                                <div class="info-label">Người nhận:</div>
                                <div>{{ $order->shippingAddress->full_name }}</div>
                            </div>
                            <div class="info-item">
                                <i class="fa fa-phone"></i>
                                <div class="info-label">Điện thoại:</div>
                                <div>{{ $order->shippingAddress->phone }}</div>
                            </div>
                            <div class="info-item">
                                <i class="fa fa-envelope"></i>
                                <div class="info-label">Email:</div>
                                <div>{{ $order->user?->email ?? 'N/A' }}</div>
                            </div>
                            <div class="info-item">
                                <i class="fa fa-map-marker"></i>
                                <div class="info-label">{{ __('messages.address') }}:</div>
                                <div>{{ $order->shippingAddress->address }}, {{ $order->shippingAddress->city }}</div>
                            </div>
                        </div>
                    @else
                        <p style="color:#999;"><i class="fa fa-exclamation-circle"></i> Không có thông tin địa chỉ</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Order Summary -->
    <div class="row">
        <div class="col-md-12">
            <div class="order-summary">
                <div class="summary-item">
                    <div class="summary-item-label"><i class="fa fa-calendar"></i> Ngày đặt</div>
                    <div class="summary-item-value">{{ \Carbon\Carbon::parse($order->created_at)->format('d/m/Y') }}</div>
                    <small style="color:#666;">{{ \Carbon\Carbon::parse($order->created_at)->format('H:i') }}</small>
                </div>

                <div class="summary-item">
                    <div class="summary-item-label"><i class="fa fa-money"></i> {{ __('messages.total_amount') }}</div>
                    <div class="summary-item-value">{{ number_format($order->total_price, 0, ',', '.') }} đ</div>
                    @if(($order->discount_amount ?? 0) > 0)
                        <small style="display:block; color:#1f7a1f; margin-top:4px;">Đã giảm: -{{ number_format($order->discount_amount, 0, ',', '.') }} đ</small>
                        @if($order->coupon_code)
                            <small style="display:block; color:#666;">Mã: {{ $order->coupon_code }}</small>
                        @endif
                    @endif
                </div>

                <div class="summary-item">
                    <div class="summary-item-label"><i class="fa fa-credit-card"></i> Phương thức</div>
                    <div style="font-size: 14px; font-weight: 600; color: #333; margin-top: 8px;">
                        {{ match($order->payment?->payment_method) {
                            'cash', 'cod' => 'Thanh toán khi nhận hàng',
                            'vnpay' => 'VNPAY',
                            'paypal' => 'PayPal',
                            default => 'Chưa cập nhật',
                        } }}
                    </div>
                </div>

                <div class="summary-item">
                    <div class="summary-item-label"><i class="fa fa-check-circle"></i> Thanh toán</div>
                    <div style="margin-top: 8px;">
                        @if ($order->payment?->status === 'completed')
                            <span class="label" style="background-color: #D4EDDA; color: #155724; padding: 4px 8px; border-radius: 3px;">Đã thanh toán</span>
                        @else
                            <span class="label" style="background-color: #FFF3CD; color: #856404; padding: 4px 8px; border-radius: 3px;">Chưa thanh toán</span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Products Table -->
    <div class="row" id="order-items">
        <div class="col-md-12">
            <div class="x_panel">
                <div class="x_title">
                    <h2><i class="fa fa-list"></i> Sản phẩm trong đơn</h2>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th style="width:50px;">#</th>
                                    <th style="width:70px;">{{ __('messages.product_image') }}</th>
                                    <th>Sản phẩm</th>
                                    <th style="width:80px; text-align:center;">SL</th>
                                    <th style="width:120px; text-align:right;">Đơn giá</th>
                                    <th style="width:120px; text-align:right;">Thành tiền</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($order->orderItems as $index => $item)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>
                                            @if ($item->product && $item->product->image_url)
                                                <img src="{{ $item->product->image_url }}" alt="{{ $item->product->name }}" style="width:50px; height:50px; object-fit:cover; border-radius:4px;">
                                            @else
                                                <div style="width:50px; height:50px; background:#eee; display:flex; align-items:center; justify-content:center; color:#999; border-radius:4px;">
                                                    <i class="fa fa-image"></i>
                                                </div>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($item->product)
                                                <a href="{{ route('product.detail', $item->product->slug) }}" target="_blank">{{ $item->product->name }}</a>
                                            @else
                                                <span style="color:#999;">{{ __('messages.product_deleted') }}</span>
                                            @endif
                                        </td>
                                        <td style="text-align:center;">{{ $item->quantity }}</td>
                                        <td style="text-align:right;">{{ number_format($item->price, 0, ',', '.') }} đ</td>
                                        <td style="text-align:right;">{{ number_format($item->price * $item->quantity, 0, ',', '.') }} đ</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" style="text-align:center; padding:30px; color:#999;">{{ __('messages.no_products_in_order') }}</td>
                                    </tr>
                                @endforelse
                                <tr style="background:#f8f9fa; font-weight:600;">
                                    <td colspan="4" style="text-align:right;">Tạm tính:</td>
                                    <td colspan="2" style="text-align:right;">{{ number_format($order->orderItems->sum(fn($i) => $i->price * $i->quantity), 0, ',', '.') }} đ</td>
                                </tr>
                                @if(($order->discount_amount ?? 0) > 0)
                                <tr style="background:#f8f9fa; font-weight:600; color:#1f7a1f;">
                                    <td colspan="4" style="text-align:right;">Giảm giá @if($order->coupon_code) ({{ $order->coupon_code }}) @endif:</td>
                                    <td colspan="2" style="text-align:right;">-{{ number_format($order->discount_amount, 0, ',', '.') }} đ</td>
                                </tr>
                                @endif
                                <tr style="background:#f5f5f5; font-weight:bold;">
                                    <td colspan="4" style="text-align:right;">Tổng cộng:</td>
                                    <td colspan="2" style="text-align:right; font-size:16px; color:#2a6edb;">{{ number_format($order->total_price, 0, ',', '.') }} đ</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Status History -->
    <div class="row" id="status-history">
        <div class="col-md-12">
            <div class="x_panel">
                <div class="x_title">
                    <h2><i class="fa fa-history"></i> Lịch sử cập nhật</h2>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    @if ($order->orderStatusHistories && $order->orderStatusHistories->count())
                        <div class="table-responsive">
                            <table class="table table-sm table-hover">
                                <thead>
                                    <tr>
                                        <th style="width:150px;">Thời gian</th>
                                        <th style="width:150px;">{{ __('messages.status') }}</th>
                                        <th>Ghi chú</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($order->orderStatusHistories->reverse() as $history)
                                        <tr>
                                            <td>{{ \Carbon\Carbon::parse($history->changed_at)->format('H:i d/m/Y') }}</td>
                                            <td>
                                                @php
                                                    $statusClass = match($history->status) {
                                                        'pending' => 'label-pending',
                                                        'processing' => 'label-processing',
                                                        'shipped' => 'label-shipped',
                                                        'completed' => 'label-completed',
                                                        'cancelled', 'canceled' => 'label-cancelled',
                                                        default => 'label-default',
                                                    };
                                                @endphp
                                                <span class="label {{ $statusClass }}" style="padding: 4px 8px; font-size: 11px;">
                                                    {{ $statusMeta[$history->status]['label'] ?? $history->status }}
                                                </span>
                                            </td>
                                            <td>{{ $history->note ?? '-' }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p style="color:#999; margin:0;"><i class="fa fa-info-circle"></i> Chưa có lịch sử cập nhật</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Actions -->
    <div class="row" id="order-actions">
        <div class="col-md-12">
            <div class="x_panel">
                <div class="x_title">
                    <h2><i class="fa fa-cogs"></i> {{ __('messages.action') }}</h2>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="action-buttons">
                                <button type="button" class="btn btn-info" id="downloadInvoiceBtn" title="Xuất hóa đơn PDF">
                                    <i class="fa fa-file-pdf-o"></i> Tải PDF
                                </button>
                                
                                <button type="button" class="btn btn-primary" id="printInvoiceBtn" title="In hóa đơn">
                                    <i class="fa fa-print"></i> In
                                </button>

                                <form method="POST" action="{{ route('admin.orders.send-invoice', $order) }}" style="display:inline;">
                                    @csrf
                                    @php $sendConfirm = __('messages.send') . ' hóa đơn tới: ' . ($order->user?->email ?? 'email') . '?'; @endphp
                                    <button type="submit" class="btn btn-success" title="{{ __('messages.send') }} hóa đơn qua email" onclick="return confirm('{{ $sendConfirm }}')">
                                        <i class="fa fa-envelope"></i> Email
                                    </button>
                                </form>

                                <a href="{{ route('admin.orders.list') }}" class="btn btn-secondary" title="Quay lại danh sách">
                                    <i class="fa fa-chevron-left"></i> Quay lại
                                </a>
                            </div>
                        </div>
                        <div class="col-md-6" style="text-align: right;">
                            <form method="POST" action="{{ route('admin.orders.update-status', $order) }}" class="status-update-form">
                                @csrf
                                @method('PUT')
                                <label for="status" style="font-weight:bold; white-space:nowrap;">{{ __('messages.update_status') }}:</label>
                                <select name="status" id="status" class="form-control">
                                    <option value="pending" {{ $order->status === 'pending' ? 'selected' : '' }}>Chờ xác nhận</option>
                                    <option value="processing" {{ $order->status === 'processing' ? 'selected' : '' }}>Đang xử lý</option>
                                    <option value="shipped" {{ $order->status === 'shipped' ? 'selected' : '' }}>Đang giao</option>
                                    <option value="completed" {{ $order->status === 'completed' ? 'selected' : '' }}>Hoàn thành</option>
                                    <option value="cancelled" {{ in_array($order->status, ['cancelled', 'canceled']) ? 'selected' : '' }}>Đã hủy</option>
                                </select>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fa fa-check"></i> Cập nhật
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="invoiceDataJson" style="display: none;">{!! json_encode($invoiceData) !!}</div>

@endsection
