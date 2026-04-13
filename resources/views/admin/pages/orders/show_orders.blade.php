@extends('layouts.admin')

@section('title', 'Quản lý đơn hàng')

@section('content')
@php
    $statusOptions = [
        '' => 'Tất cả trạng thái',
        'pending' => 'Chờ xác nhận',
        'processing' => 'Đang xử lý',
        'shipped' => 'Đang giao',
        'completed' => 'Hoàn thành',
        'cancelled' => 'Đã hủy',
    ];

    $formatPrice = function ($value) {
        return number_format((float) $value, 0, ',', '.') . ' đ';
    };
@endphp

<style>
    .orders-page .btn,
    .orders-page .btn-sm,
    .orders-page .btn-group-sm > .btn {
        padding: 3px 8px !important;
        font-size: 11px !important;
        line-height: 1.2 !important;
        border-radius: 4px !important;
    }

    .orders-page .pagination > li > a,
    .orders-page .pagination > li > span {
        padding: 4px 9px !important;
        font-size: 11px !important;
        min-width: 28px !important;
        line-height: 1.25 !important;
    }

    /* Status Labels Styling */
    .orders-page .label {
        padding: 3px 8px !important;
        font-size: 11px !important;
        border-radius: 3px !important;
        font-weight: 600 !important;
        display: inline-block !important;
    }

    .label-pending {
        background-color: #FFF3CD;
        color: #856404;
    }

    .label-processing {
        background-color: #D1ECF1;
        color: #0C5460;
    }

    .label-shipped {
        background-color: #CCE5FF;
        color: #004085;
    }

    .label-completed {
        background-color: #D4EDDA;
        color: #155724;
    }

    .label-cancelled,
    .label-canceled {
        background-color: #F8D7DA;
        color: #721C24;
    }

    .label-info {
        background-color: #E7F3FF;
        color: #004085;
    }

    .label-cod,
    .label-cash {
        background-color: #E2F0D9;
        color: #3D5A35;
    }

    .label-vnpay {
        background-color: #DCE9F9;
        color: #1E3A5F;
    }

    .label-paypal {
        background-color: #FFF3CD;
        color: #856404;
    }
</style>

<div class="right_col orders-page" role="main">
    <div class="page-title">
        <div class="title_left">
            <h3>Quản lý đơn hàng</h3>
            <p style="margin:6px 0 0; color:#7a7a7a;">Theo dõi trạng thái, xem chi tiết và cập nhật đơn hàng nhanh chóng.</p>
        </div>
    </div>

    <div class="clearfix"></div>

    <div class="row mb-3">
        <div class="col-md-2 col-sm-4 col-xs-6">
            <div class="x_panel"><div class="x_content"><p style="margin:0;color:#7a7a7a;">Tổng</p><h3 style="margin:6px 0 0;">{{ $totalOrders }}</h3></div></div>
        </div>
        <div class="col-md-2 col-sm-4 col-xs-6">
            <div class="x_panel"><div class="x_content"><p style="margin:0;color:#7a7a7a;">Chờ xác nhận</p><h3 style="margin:6px 0 0;">{{ $pendingOrders }}</h3></div></div>
        </div>
        <div class="col-md-2 col-sm-4 col-xs-6">
            <div class="x_panel"><div class="x_content"><p style="margin:0;color:#7a7a7a;">Đang xử lý</p><h3 style="margin:6px 0 0;">{{ $processingOrders }}</h3></div></div>
        </div>
        <div class="col-md-2 col-sm-4 col-xs-6">
            <div class="x_panel"><div class="x_content"><p style="margin:0;color:#7a7a7a;">Đang giao</p><h3 style="margin:6px 0 0;">{{ $shippedOrders }}</h3></div></div>
        </div>
        <div class="col-md-2 col-sm-4 col-xs-6">
            <div class="x_panel"><div class="x_content"><p style="margin:0;color:#7a7a7a;">Hoàn thành</p><h3 style="margin:6px 0 0;">{{ $completedOrders }}</h3></div></div>
        </div>
        <div class="col-md-2 col-sm-4 col-xs-6">
            <div class="x_panel"><div class="x_content"><p style="margin:0;color:#7a7a7a;">Đã hủy</p><h3 style="margin:6px 0 0;">{{ $cancelledOrders }}</h3></div></div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12 col-sm-12">
            <div class="x_panel">
                <div class="x_title" style="display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:12px;">
                    <h2>Danh sách đơn hàng</h2>
                    <span class="label label-default" style="font-size:11px;padding:4px 8px;">Kết quả lọc: {{ $filteredOrdersCount }}</span>
                    <div class="clearfix"></div>
                </div>

                <div class="x_content">
                    <form method="GET" action="{{ route('admin.orders.list') }}" class="row" style="margin-bottom:18px;">
                        <div class="col-md-6 col-sm-6">
                            <div class="form-group">
                                <label for="keyword">Từ khóa</label>
                                <input type="text" id="keyword" name="keyword" class="form-control" value="{{ request('keyword') }}" placeholder="Mã đơn, tên khách, email, số điện thoại">
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-4">
                            <div class="form-group">
                                <label for="status">Trạng thái</label>
                                <select id="status" name="status" class="form-control">
                                    @foreach($statusOptions as $value => $label)
                                        <option value="{{ $value }}" {{ request('status', '') === $value ? 'selected' : '' }}>{{ $label }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2 col-sm-2">
                            <div class="form-group" style="margin-top:25px;display:flex;gap:8px;">
                                <button type="submit" class="btn btn-primary" style="width:100%;"><i class="fa fa-search"></i> Lọc</button>
                                <a href="{{ route('admin.orders.list') }}" class="btn btn-default" style="width:100%;"><i class="fa fa-refresh"></i></a>
                            </div>
                        </div>
                    </form>

                    <div class="table-responsive">
                        <table class="table table-striped table-hover table-bordered">
                            <thead>
                                <tr>
                                    <th> STT</th>
                                    <th style="width:80px;">Mã đơn</th>
                                    <th>Khách hàng</th>
                                    <th style="width:150px;">Tổng tiền</th>
                                    <th style="width:150px;">Thanh toán</th>
                                    <th style="width:140px;">Trạng thái</th>
                                    <th style="width:180px;">Ngày tạo</th>
                                    <th style="width:180px;">Chi tiết đơn hàng</th>

                                    <th style="width:260px;">Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($orders as $order)
                                    @php
                                        $currentStatus = $order->status;
                                        $statusInfo = $statusMeta[$currentStatus] ?? ['label' => ucfirst($currentStatus), 'class' => 'default'];
                                        $shippingName = $order->shippingAddress?->full_name ?? $order->user?->name ?? 'Không xác định';
                                        $shippingPhone = $order->shippingAddress?->phone ?? '-';
                                        $paymentLabel = match($order->payment?->payment_method) {
                                            'cash', 'cod' => 'COD',
                                            'vnpay' => 'VNPAY',
                                            'paypal' => 'PayPal',
                                            default => 'Chưa cập nhật',
                                        };

                                        $itemLines = $order->orderItems->map(function ($item) {
                                            return ($item->product?->name ?? 'Sản phẩm đã xóa') . ' x' . $item->quantity;
                                        })->values();
                                    @endphp
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td><strong>#{{ $order->id }}</strong></td>
                                        <td>
                                            <strong>{{ $shippingName }}</strong>
                                            <div><small>{{ $shippingPhone }}</small></div>
                                            <div><small style="color:#777;">{{ $order->user?->email ?? 'Không có email' }}</small></div>
                                        </td>
                                        <td><strong style="color:#2a6edb;">{{ $formatPrice($order->total_price) }}</strong></td>
                                        <td>
                                            @php
                                                $paymentClass = match($order->payment?->payment_method) {
                                                    'cash', 'cod' => 'label-cod',
                                                    'vnpay' => 'label-vnpay',
                                                    'paypal' => 'label-paypal',
                                                    default => 'label-info',
                                                };
                                            @endphp
                                            <span class="label {{ $paymentClass }}">{{ $paymentLabel }}</span>
                                            <div><small style="color:#777;">{{ $order->payment?->status ?? 'pending' }}</small></div>
                                        </td>
                                        <td>
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
                                            <span class="label {{ $statusClass }}">{{ $statusInfo['label'] }}</span>
                                        </td>
                                        <td>{{ $order->created_at?->format('H:i d/m/Y') }}</td>
                                        <td>
                                            <a href="{{ route('admin.orders.detail', $order) }}" class="btn btn-primary btn-sm">
                                                    <i class="fa fa-eye"></i> xem đơn hàng
                                                </a>
                                        </td>
                                        <td>
                                            <div style="display:flex;flex-wrap:wrap;gap:6px;align-items:center;">
                                                

                                                <form method="POST" action="{{ route('admin.orders.update-status', $order) }}" class="update-order-status-form" style="display:flex;gap:6px;align-items:center;">
                                                    @csrf
                                                    @method('PUT')
                                                    <select name="status" class="form-control input-sm" style="min-width:120px;">
                                                        @foreach($statusOptions as $value => $label)
                                                            @if($value !== '')
                                                                <option value="{{ $value }}" {{ $currentStatus === $value || ($currentStatus === 'canceled' && $value === 'cancelled') ? 'selected' : '' }}>{{ $label }}</option>
                                                            @endif
                                                        @endforeach
                                                    </select>
                                                    <button type="submit" class="btn btn-success btn-sm"><i class="fa fa-check"></i></button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center" style="padding:30px 15px;">Chưa có đơn hàng nào.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
