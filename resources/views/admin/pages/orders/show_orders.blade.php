@extends('layouts.admin')

@section('title', __('messages.order_management'))

@section('content')
@php
    $statusOptions = [
        '' => __('messages.all_status'),
        'pending' => __('messages.pending'),
        'processing' => __('messages.processing'),
        'shipped' => __('messages.shipped'),
        'completed' => __('messages.completed'),
        'cancelled' => __('messages.cancelled'),
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
            <h3>{{ __('messages.order_management') }}</h3>
            <p style="margin:6px 0 0; color:#7a7a7a;">{{ __('messages.order_tracking_description') }}</p>
        </div>
    </div>

    <div class="clearfix"></div>

    <div class="row mb-3">
        <div class="col-md-2 col-sm-4 col-xs-6">
            <div class="x_panel"><div class="x_content"><p style="margin:0;color:#7a7a7a;">{{ __('messages.total_orders') }}</p><h3 style="margin:6px 0 0;">{{ $totalOrders }}</h3></div></div>
        </div>
        <div class="col-md-2 col-sm-4 col-xs-6">
            <div class="x_panel"><div class="x_content"><p style="margin:0;color:#7a7a7a;">{{ __('messages.pending') }}</p><h3 style="margin:6px 0 0;">{{ $pendingOrders }}</h3></div></div>
        </div>
        <div class="col-md-2 col-sm-4 col-xs-6">
            <div class="x_panel"><div class="x_content"><p style="margin:0;color:#7a7a7a;">{{ __('messages.processing') }}</p><h3 style="margin:6px 0 0;">{{ $processingOrders }}</h3></div></div>
        </div>
        <div class="col-md-2 col-sm-4 col-xs-6">
            <div class="x_panel"><div class="x_content"><p style="margin:0;color:#7a7a7a;">{{ __('messages.shipped') }}</p><h3 style="margin:6px 0 0;">{{ $shippedOrders }}</h3></div></div>
        </div>
        <div class="col-md-2 col-sm-4 col-xs-6">
            <div class="x_panel"><div class="x_content"><p style="margin:0;color:#7a7a7a;">{{ __('messages.completed') }}</p><h3 style="margin:6px 0 0;">{{ $completedOrders }}</h3></div></div>
        </div>
        <div class="col-md-2 col-sm-4 col-xs-6">
            <div class="x_panel"><div class="x_content"><p style="margin:0;color:#7a7a7a;">{{ __('messages.cancelled') }}</p><h3 style="margin:6px 0 0;">{{ $cancelledOrders }}</h3></div></div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12 col-sm-12">
            <div class="x_panel">
                <div class="x_title" style="display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:12px;">
                    <h2>{{ __('messages.order_list') }}</h2>
                    <span class="label label-default" style="font-size:11px;padding:4px 8px;">{{ __('messages.filter_results') }} {{ $filteredOrdersCount }}</span>
                    <div class="clearfix"></div>
                </div>

                <div class="x_content">
                    <form method="GET" action="{{ route('admin.orders.list') }}" class="row" style="margin-bottom:18px;">
                        <div class="col-md-6 col-sm-6">
                            <div class="form-group">
                                <label for="keyword">{{ __('messages.keyword') }}</label>
                                <input type="text" id="keyword" name="keyword" class="form-control" value="{{ request('keyword') }}" placeholder="{{ __('messages.search_orders') }}">
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-4">
                            <div class="form-group">
                                <label for="status">{{ __('messages.status') }}</label>
                                <select id="status" name="status" class="form-control">
                                    @foreach($statusOptions as $value => $label)
                                        <option value="{{ $value }}" {{ request('status', '') === $value ? 'selected' : '' }}>{{ $label }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2 col-sm-2">
                            <div class="form-group" style="margin-top:25px;display:flex;gap:8px;">
                                <button type="submit" class="btn btn-primary" style="width:100%;"><i class="fa fa-search"></i> {{ __('messages.filter') }}</button>
                                <a href="{{ route('admin.orders.list') }}" class="btn btn-default" style="width:100%;"><i class="fa fa-refresh"></i></a>
                            </div>
                        </div>
                    </form>

                    <div class="table-responsive">
                        <table class="table table-striped table-hover table-bordered">
                            <thead>
                                <tr>
                                    <th>{{ __('messages.seq_no') }}</th>
                                    <th style="width:80px;">{{ __('messages.order_code') }}</th>
                                    <th>{{ __('messages.customer') }}</th>
                                    <th style="width:150px;">{{ __('messages.total_amount') }}</th>
                                    <th style="width:150px;">{{ __('messages.payment_method') }}</th>
                                    <th style="width:140px;">{{ __('messages.status') }}</th>
                                    <th style="width:180px;">{{ __('messages.created_date') }}</th>
                                    <th style="width:180px;">{{ __('messages.view_details') }} {{ __('messages.order') }}</th>

                                    <th style="width:260px;">{{ __('messages.action') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($orders as $order)
                                    @php
                                        $currentStatus = $order->status;
                                        $statusInfo = $statusMeta[$currentStatus] ?? ['label' => ucfirst($currentStatus), 'class' => 'default'];
                                        $shippingName = $order->shippingAddress?->full_name ?? $order->user?->name ?? __('messages.not_determined');
                                        $shippingPhone = $order->shippingAddress?->phone ?? '-';
                                        $paymentLabel = match($order->payment?->payment_method) {
                                            'cash', 'cod' => 'COD',
                                            'vnpay' => 'VNPAY',
                                            'paypal' => 'PayPal',
                                            default => __('messages.not_updated'),
                                        };

                                        $itemLines = $order->orderItems->map(function ($item) {
                                            return ($item->product?->name ?? __('messages.product_deleted')) . ' x' . $item->quantity;
                                        })->values();
                                    @endphp
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td><strong>#{{ $order->id }}</strong></td>
                                        <td>
                                            <strong>{{ $shippingName }}</strong>
                                            <div><small>{{ $shippingPhone }}</small></div>
                                            <div><small style="color:#777;">{{ $order->user?->email ?? __('messages.no_email') }}</small></div>
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
                                        <td>{{ $order->created_at?->format(' d/m/Y H:i') }}</td>
                                        <td>
                                            <a href="{{ route('admin.orders.detail', $order) }}" class="btn btn-primary btn-sm">
                                                    <i class="fa fa-eye"></i> {{ __('messages.view_order') }}
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
                                        <td colspan="7" class="text-center" style="padding:30px 15px;">{{ __('messages.no_order_message') }}</td>
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
