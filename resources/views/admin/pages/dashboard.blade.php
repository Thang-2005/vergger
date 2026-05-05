@extends('layouts.admin')

@section('title', 'Trang quản trị')

@section('content')

<style>
    .stat-box {
        position: relative;
        border-radius: 2px;
        background: #fff;
        margin-bottom: 20px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .stat-icon {
        padding: 15px;
        display: inline-block;
        background: linear-gradient(45deg, #4CAF50, #45a049);
        color: white;
        border-radius: 2px;
        font-size: 28px;
    }

    .stat-info {
        display: inline-block;
        margin-left: 15px;
        vertical-align: middle;
    }

    .stat-value {
        font-size: 28px;
        font-weight: 700;
        color: #2C3E50;
    }

    .stat-label {
        font-size: 12px;
        color: #999;
        text-transform: uppercase;
        margin-top: 5px;
    }

    .stat-growth {
        font-size: 13px;
        margin-top: 5px;
    }

    .stat-growth.positive {
        color: #5cb85c;
    }

    .stat-growth.negative {
        color: #d9534f;
    }

    .recent-table {
        font-size: 13px;
    }

    .recent-table thead {
        background: #f5f5f5;
    }

    .order-status {
        padding: 4px 12px;
        border-radius: 3px;
        font-size: 11px;
        font-weight: 600;
        text-transform: uppercase;
    }

    .status-pending {
        background: #fff3cd;
        color: #856404;
    }

    .status-completed {
        background: #d4edda;
        color: #155724;
    }

    .status-cancelled {
        background: #f8d7da;
        color: #721c24;
    }
</style>

<div class="right_col" role="main">
    <!-- KPI Stats -->
    <div class="row">
        <div class="col-md-3 col-sm-6">
            <div class="stat-box">
                <div class="stat-icon" style="background: linear-gradient(45deg, #4CAF50, #45a049);">
                    <i class="fa fa-users"></i>
                </div>
                <div class="stat-info">
                    <div class="stat-value">{{ $totalUsers }}</div>
                    <div class="stat-label">Total Users</div>
                    <div class="stat-growth {{ $usersGrowth >= 0 ? 'positive' : 'negative' }}">
                        <i class="fa {{ $usersGrowth >= 0 ? 'fa-arrow-up' : 'fa-arrow-down' }}"></i>
                        {{ abs($usersGrowth) }}% from last week
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3 col-sm-6">
            <div class="stat-box">
                <div class="stat-icon" style="background: linear-gradient(45deg, #2196F3, #1976D2);">
                    <i class="fa fa-shopping-cart"></i>
                </div>
                <div class="stat-info">
                    <div class="stat-value">{{ $totalOrders }}</div>
                    <div class="stat-label">Total Orders</div>
                    <div class="stat-growth {{ $ordersGrowth >= 0 ? 'positive' : 'negative' }}">
                        <i class="fa {{ $ordersGrowth >= 0 ? 'fa-arrow-up' : 'fa-arrow-down' }}"></i>
                        {{ abs($ordersGrowth) }}% this month
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3 col-sm-6">
            <div class="stat-box">
                <div class="stat-icon" style="background: linear-gradient(45deg, #FF9800, #F57C00);">
                    <i class="fa fa-cube"></i>
                </div>
                <div class="stat-info">
                    <div class="stat-value">{{ $totalProducts }}</div>
                    <div class="stat-label">Total Products</div>
                    <div class="stat-growth {{ $productsGrowth >= 0 ? 'positive' : 'negative' }}">
                        <i class="fa {{ $productsGrowth >= 0 ? 'fa-arrow-up' : 'fa-arrow-down' }}"></i>
                        {{ $activeProducts }} Active
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3 col-sm-6">
            <div class="stat-box">
                <div class="stat-icon" style="background: linear-gradient(45deg, #9C27B0, #7B1FA2);">
                    <i class="fa fa-money"></i>
                </div>
                <div class="stat-info">
                    <div class="stat-value">{{ $totalRevenue }}</div>
                    <div class="stat-label">Total Revenue</div>
                    <div class="stat-growth {{ $revenueGrowth >= 0 ? 'positive' : 'negative' }}">
                        <i class="fa {{ $revenueGrowth >= 0 ? 'fa-arrow-up' : 'fa-arrow-down' }}"></i>
                        {{ abs($revenueGrowth) }}% this month
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /KPI Stats -->

    <div class="row">
        <!-- Recent Orders -->
        <div class="col-md-8 col-sm-12">
            <div class="x_panel">
                <div class="x_title">
                    <h2>{{ __('messages.recent_orders') }} <small>{{ __('messages.latest_10_orders') }}</small></h2>
                    <ul class="nav navbar-right panel_toolbox">
                        <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><i class="fa fa-wrench"></i></a>
                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                <a class="dropdown-item" href="{{ route('admin.orders.list') }}">{{ __('messages.view_all_orders') }}</a>
                            </div>
                        </li>
                        <li><a class="close-link"><i class="fa fa-close"></i></a></li>
                    </ul>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    @if($recentOrders->count() > 0)
                    <table class="table table-hover recent-table">
                        <thead>
                            <tr>
                                <th>{{ __('messages.order_id') }}</th>
                                <th>{{ __('messages.customer') }}</th>
                                <th>{{ __('messages.amount') }}</th>
                                <th>{{ __('messages.status') }}</th>
                                <th>{{ __('messages.date') }}</th>
                                <th>{{ __('messages.action') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recentOrders as $order)
                            <tr>
                                <td>#{{ $order->id }}</td>
                                <td>{{ $order->user->name ?? 'N/A' }}</td>
                                <td><strong>{{ number_format($order->total_price, 0) }} đ</strong></td>
                                <td>
                                    <span class="order-status status-{{ strtolower($order->status) }}">
                                        {{ ucfirst($order->status) }}
                                    </span>
                                </td>
                                <td>{{ $order->created_at->format('d/m/Y H:i') }}</td>
                                <td>
                                    <a href="{{ route('admin.orders.detail', $order->id) }}" class="btn btn-xs btn-primary">
                                        <i class="fa fa-eye"></i> {{ __('messages.view') }}
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    @else
                    <p class="text-center text-muted mt-3">{{ __('messages.no_orders_yet') }}</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Order Status Distribution -->
        <div class="col-md-4 col-sm-12">
            <div class="x_panel">
                <div class="x_title">
                    <h2>{{ __('messages.order_status_distribution') }} <small>{{ __('messages.distribution') }}</small></h2>
                    <ul class="nav navbar-right panel_toolbox">
                        <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
                        <li><a class="close-link"><i class="fa fa-close"></i></a></li>
                    </ul>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <ul class="list-unstyled">
                        @foreach($orderStatusDistribution as $item)
                        <li class="mb-3">
                            <div style="display: flex; justify-content: space-between; margin-bottom: 5px;">
                                <strong>{{ ucfirst($item->status) }}</strong>
                                <span class="badge badge-info">{{ $item->total }}</span>
                            </div>
                            <div class="progress" style="height: 20px;">
                                @php
                                    $percentage = ($item->total / ($totalOrders ?: 1)) * 100;
                                    $statusClass = ['pending' => 'warning', 'completed' => 'success', 'cancelled' => 'danger'][$item->status] ?? 'info';
                                    $progressStyle = 'width: ' . $percentage . '%;';
                                @endphp
                                <div class="progress-bar bg-{{ $statusClass }}" role="progressbar" aria-valuenow="{{ $item->total }}" aria-valuemin="0" aria-valuemax="{{ $totalOrders }}" @style("width: {$percentage}%")></div>
                            </div>
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <!-- Top Products -->
        <div class="col-md-6 col-sm-12">
            <div class="x_panel">
                <div class="x_title">
                    <h2>Top Products <small>Most ordered</small></h2>
                    <ul class="nav navbar-right panel_toolbox">
                        <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false"><i class="fa fa-wrench"></i></a>
                            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                <a class="dropdown-item" href="{{ route('admin.products.list') }}">{{ __('messages.view_all_products') }}</a>
                            </div>
                        </li>
                        <li><a class="close-link"><i class="fa fa-close"></i></a></li>
                    </ul>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    @if($topProducts->count() > 0)
                    <table class="table table-hover recent-table">
                        <thead>
                            <tr>
                                <th>Product Name</th>
                                <th>Price</th>
                                <th>Orders</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($topProducts as $product)
                            <tr>
                                <td>{{ Str::limit($product->name, 30) }}</td>
                                <td>{{ number_format($product->price, 0) }} đ</td>
                                <td>
                                    <span class="badge badge-success">{{ $product->order_items_count }}</span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    @else
                    <p class="text-center text-muted mt-3">No products yet</p>
                    @endif
                </div>
            </div>
        </div>

        
        <!-- Categories Info -->
        <div class="col-md-6 col-sm-12">
            <div class="x_panel">
                <div class="x_title">
                    <h2>{{ __('messages.system_overview') }}</h2>
                    <ul class="nav navbar-right panel_toolbox">
                        <li><a class="collapse-link"><i class="fa fa-chevron-up"></i></a></li>
                    </ul>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <div class="row">
                        <div class="col-md-6">
                            <div style="background: #f5f5f5; padding: 20px; border-radius: 3px; text-align: center; margin-bottom: 15px;">
                                <h4>Total Categories</h4>
                                <h2 style="color: #4CAF50; font-weight: bold;">{{ $totalCategories }}</h2>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div style="background: #f5f5f5; padding: 20px; border-radius: 3px; text-align: center; margin-bottom: 15px;">
                                <h4>This Month Orders</h4>
                                <h2 style="color: #2196F3; font-weight: bold;">{{ $ordersThisMonth }}</h2>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <hr>
                            <h4>Revenue This Month</h4>
                            <h2 style="color: #9C27B0; font-weight: bold;">{{ number_format($revenueThisMonth, 0) }} đ</h2>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- top tiles -->

</div>

@endsection