@extends('layouts.admin')

@section('title', __('messages.coupon_management'))

@section('content')
<div class="right_col" role="main">
    <div class="page-title">
        <div class="title_left">
            <h3>{{ __('messages.coupon_management') }}</h3>
        </div>
        <div class="title_right" style="text-align:right;">
            <button type="button" class="btn btn-success btn-sm" data-toggle="modal" data-target="#createCouponModal" style="margin-top:5px;">
                <i class="fa fa-plus"></i> {{ __('messages.add_coupon') }}
            </button>
        </div>
    </div>

    <div class="clearfix"></div>

    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel">
                <div class="x_title">
                    <h2>{{ __('messages.coupon_list') }}</h2>
                    <div class="clearfix"></div>
                </div>

                <div class="x_content">
                    <form method="GET" action="{{ route('admin.coupons.index') }}" class="row" style="margin-bottom:18px;">
                        <div class="col-md-5 col-sm-6">
                            <div class="form-group">
                                <label for="keyword">{{ __('messages.keyword') }}</label>
                                <input type="text" id="keyword" name="keyword" class="form-control" placeholder="{{ __('messages.search_coupon_placeholder') }}" value="{{ request('keyword') }}">
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-4">
                            <div class="form-group">
                                <label for="status">{{ __('messages.status') }}</label>
                                <select id="status" name="status" class="form-control">
                                    <option value="">{{ __('messages.all_status') }}</option>
                                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>{{ __('messages.active') }}</option>
                                    <option value="expired" {{ request('status') == 'expired' ? 'selected' : '' }}>{{ __('messages.expired') }}</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-2">
                            <div class="form-group" style="margin-top:25px;display:flex;gap:8px;">
                                <button type="submit" class="btn btn-primary" style="width:100%;"><i class="fa fa-search"></i> {{ __('messages.filter') }}</button>
                                <a href="{{ route('admin.coupons.index') }}" class="btn btn-default" style="width:100%;"><i class="fa fa-refresh"></i></a>
                            </div>
                        </div>
                    </form>

                    <div class="table-responsive">
                        <table class="table table-striped table-hover table-bordered">
                            <thead>
                                <tr>
                                    <th style="width:70px;">#</th>
                                    <th style="width:140px;">{{ __('messages.coupon_code') }}</th>
                                    <th style="width:150px;">{{ __('messages.discount_type') }}</th>
                                    <th>Giá trị</th>
                                    <th style="width:120px;">{{ __('messages.used_count') }}</th>
                                    <th style="width:180px;">{{ __('messages.expiration_date') }}</th>
                                    <th style="width:120px;">{{ __('messages.status') }}</th>
                                    <th style="width:260px;">{{ __('messages.action') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($coupons as $coupon)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td><span class="label label-info">{{ $coupon->code }}</span></td>
                                        <td>
                                            @if($coupon->discount_type === 'percentage')
                                                <span class="label label-success">{{ __('messages.discount') }} {{ $coupon->discount_value }}%</span>
                                            @else
                                                <span class="label label-success">{{ __('messages.discount') }} {{ number_format($coupon->discount_value, 0, ',', '.') }}đ</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($coupon->discount_type === 'percentage')
                                                {{ __('messages.discount') }} {{ $coupon->discount_value }}%
                                                @if($coupon->max_discount)
                                                    ({{ __('messages.max_discount_label') }} {{ number_format($coupon->max_discount, 0, ',', '.') }}đ)
                                                @endif
                                            @else
                                                {{ __('messages.discount') }} {{ number_format($coupon->discount_value, 0, ',', '.') }}đ
                                            @endif
                                        </td>
                                        <td>{{ $coupon->usage_count }} / {{ $coupon->usage_limit ?? '∞' }}</td>
                                        <td>
                                            @if($coupon->valid_until)
                                                {{ $coupon->valid_until->format('d/m/Y H:i') }}
                                                @if($coupon->valid_until->isPast())
                                                    <div><span class="label label-danger">{{ __('messages.expired') }}</span></div>
                                                @endif
                                            @else
                                                <span class="label label-default">{{ __('messages.unlimited') }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($coupon->is_active)
                                                <span class="label label-success">{{ __('messages.active') }}</span>
                                            @else
                                                <span class="label label-default">{{ __('messages.inactive') }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="btn-group btn-group-sm" role="group" style="display:flex;flex-wrap:wrap;gap:6px;">
                                                <button
                                                    type="button"
                                                    class="btn btn-info btn-sm edit-coupon-btn"
                                                    data-toggle="modal"
                                                    data-target="#editCouponModal"
                                                    data-id="{{ $coupon->id }}"
                                                    data-code="{{ e($coupon->code) }}"
                                                    data-discount-type="{{ $coupon->discount_type }}"
                                                    data-discount-value="{{ $coupon->discount_value }}"
                                                    data-max-discount="{{ $coupon->max_discount }}"
                                                    data-min-order-amount="{{ $coupon->min_order_amount }}"
                                                    data-usage-limit="{{ $coupon->usage_limit }}"
                                                    data-valid-from="{{ $coupon->valid_from?->format('Y-m-d\\TH:i') }}"
                                                    data-valid-until="{{ $coupon->valid_until?->format('Y-m-d\\TH:i') }}"
                                                    data-is-active="{{ $coupon->is_active ? 1 : 0 }}"
                                                    data-description="{{ e($coupon->description) }}"
                                                >
                                                    <i class="fa fa-edit"></i> {{ __('messages.edit') }}
                                                </button>

                                                <form action="{{ route('admin.coupons.toggle', $coupon) }}" method="POST" style="display:inline-block;">
                                                    @csrf
                                                    @method('PATCH')
                                                    @php $msg = __('messages.confirm_change_status'); @endphp
                                                    <button type="submit" class="btn btn-sm {{ $coupon->is_active ? 'btn-warning' : 'btn-success' }}" onclick="return confirm('{{ $msg }}');">
                                                        <i class="fa {{ $coupon->is_active ? 'fa-eye-slash' : 'fa-check' }}"></i>
                                                        {{ $coupon->is_active ? __('messages.deactivate') : __('messages.activate') }}
                                                    </button>
                                                </form>

                                                    <form action="{{ route('admin.coupons.destroy', $coupon) }}" method="POST" style="display:inline-block;">
                                                        @csrf
                                                        @method('DELETE')
                                                        @php $deleteMsg = __('messages.confirm_delete'); @endphp
                                                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('{{ $deleteMsg }}');">
                                                        <i class="fa fa-trash"></i> {{ __('messages.delete') }}
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center">Chưa có mã giảm giá nào.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="text-center">
                        {{ $coupons->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="createCouponModal" tabindex="-1" role="dialog" aria-labelledby="createCouponModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="createCouponModalLabel">{{ __('messages.add_product') }} mã giảm giá</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="POST" action="{{ route('admin.coupons.store') }}">
                    @csrf
                    <div class="form-group">
                        <label for="create_code">Mã giảm giá</label>
                        <input type="text" id="create_code" name="code" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="create_discount_type">Loại giảm giá</label>
                        <select id="create_discount_type" name="discount_type" class="form-control" required>
                            <option value="percentage">Giảm theo %</option>
                            <option value="fixed">Giảm theo tiền</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="create_discount_value">Giá trị giảm</label>
                        <input type="number" id="create_discount_value" name="discount_value" class="form-control" step="0.01" min="0.01" required>
                    </div>
                    <div class="form-group">
                        <label for="create_max_discount">Giảm tối đa</label>
                        <input type="number" id="create_max_discount" name="max_discount" class="form-control" step="0.01" min="0">
                    </div>
                    <div class="form-group">
                        <label for="create_min_order_amount">{{ __('messages.minimum_order_amount') }}</label>
                        <input type="number" id="create_min_order_amount" name="min_order_amount" class="form-control" step="0.01" min="0">
                    </div>
                    <div class="form-group">
                        <label for="create_usage_limit">Giới hạn sử dụng</label>
                        <input type="number" id="create_usage_limit" name="usage_limit" class="form-control" min="1">
                    </div>
                    <div class="form-group">
                        <label for="create_valid_from">Ngày bắt đầu</label>
                        <input type="datetime-local" id="create_valid_from" name="valid_from" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="create_valid_until">Ngày hết hạn</label>
                        <input type="datetime-local" id="create_valid_until" name="valid_until" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="create_is_active">{{ __('messages.status') }}</label>
                        <select id="create_is_active" name="is_active" class="form-control">
                            <option value="1" selected>{{ __('messages.active') }}</option>
                            <option value="0">Vô hiệu</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="create_description">{{ __('messages.description') }}</label>
                        <textarea id="create_description" name="description" rows="3" class="form-control"></textarea>
                    </div>
                    <div class="text-right">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Đóng</button>
                        <button type="submit" class="btn btn-success">Lưu</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="editCouponModal" tabindex="-1" role="dialog" aria-labelledby="editCouponModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="editCouponModalLabel">{{ __('messages.edit_coupon') }}</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form method="POST" action="" id="editCouponForm" data-base-url="{{ url('/admin/coupons') }}">
                    @csrf
                    @method('PUT')
                    <div class="form-group">
                        <label for="edit_code">Mã giảm giá</label>
                        <input type="text" id="edit_code" name="code" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="edit_discount_type">Loại giảm giá</label>
                        <select id="edit_discount_type" name="discount_type" class="form-control" required>
                            <option value="percentage">Giảm theo %</option>
                            <option value="fixed">Giảm theo tiền</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="edit_discount_value">Giá trị giảm</label>
                        <input type="number" id="edit_discount_value" name="discount_value" class="form-control" step="0.01" min="0.01" required>
                    </div>
                    <div class="form-group">
                        <label for="edit_max_discount">Giảm tối đa</label>
                        <input type="number" id="edit_max_discount" name="max_discount" class="form-control" step="0.01" min="0">
                    </div>
                    <div class="form-group">
                        <label for="edit_min_order_amount">{{ __('messages.minimum_order_amount') }}</label>
                        <input type="number" id="edit_min_order_amount" name="min_order_amount" class="form-control" step="0.01" min="0">
                    </div>
                    <div class="form-group">
                        <label for="edit_usage_limit">Giới hạn sử dụng</label>
                        <input type="number" id="edit_usage_limit" name="usage_limit" class="form-control" min="1">
                    </div>
                    <div class="form-group">
                        <label for="edit_valid_from">Ngày bắt đầu</label>
                        <input type="datetime-local" id="edit_valid_from" name="valid_from" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="edit_valid_until">Ngày hết hạn</label>
                        <input type="datetime-local" id="edit_valid_until" name="valid_until" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="edit_is_active">{{ __('messages.status') }}</label>
                        <select id="edit_is_active" name="is_active" class="form-control">
                            <option value="1">{{ __('messages.active') }}</option>
                            <option value="0">Vô hiệu</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="edit_description">{{ __('messages.description') }}</label>
                        <textarea id="edit_description" name="description" rows="3" class="form-control"></textarea>
                    </div>
                    <div class="text-right">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Đóng</button>
                        <button type="submit" class="btn btn-primary">Cập nhật</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        var editButtons = document.querySelectorAll('.edit-coupon-btn');
        var form = document.getElementById('editCouponForm');
        if (!form) {
            return;
        }

        var baseUrl = form.getAttribute('data-base-url');

        editButtons.forEach(function (button) {
            button.addEventListener('click', function () {
                var id = this.getAttribute('data-id');
                form.setAttribute('action', baseUrl + '/' + id);

                document.getElementById('edit_code').value = this.getAttribute('data-code') || '';
                document.getElementById('edit_discount_type').value = this.getAttribute('data-discount-type') || 'percentage';
                document.getElementById('edit_discount_value').value = this.getAttribute('data-discount-value') || '';
                document.getElementById('edit_max_discount').value = this.getAttribute('data-max-discount') || '';
                document.getElementById('edit_min_order_amount').value = this.getAttribute('data-min-order-amount') || '';
                document.getElementById('edit_usage_limit').value = this.getAttribute('data-usage-limit') || '';
                document.getElementById('edit_valid_from').value = this.getAttribute('data-valid-from') || '';
                document.getElementById('edit_valid_until').value = this.getAttribute('data-valid-until') || '';
                document.getElementById('edit_is_active').value = this.getAttribute('data-is-active') || '1';
                document.getElementById('edit_description').value = this.getAttribute('data-description') || '';
            });
        });
    });
</script>
@endsection
