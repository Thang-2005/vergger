@extends('layouts.admin')

@section('title', 'Quản lý mã giảm giá')

@section('content')
<div class="right_col" role="main">
    <div class="page-title">
        <div class="title_left">
            <h3>{{ 'Quản lý mã giảm giá' }}</h3>
        </div>
        <div class="title_right" style="text-align:right;">
            <button type="button" class="btn btn-success btn-sm" data-toggle="modal" data-target="#createCouponModal" style="margin-top:5px;">
                <i class="fa fa-plus"></i> {{ 'Thêm Mã Giảm Giá' }}
            </button>
        </div>
    </div>

    <div class="clearfix"></div>

    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel">
                <div class="x_title">
                    <h2>{{ 'Danh Sách Mã Giảm Giá' }}</h2>
                    <div class="clearfix"></div>
                </div>

                <div class="x_content">
                    <form method="GET" action="{{ route('admin.coupons.index') }}" class="row" style="margin-bottom:18px;">
                        <div class="col-md-5 col-sm-6">
                            <div class="form-group">
                                <label for="keyword">{{ 'Từ Khóa' }}</label>
                                <input type="text" id="keyword" name="keyword" class="form-control" placeholder="{{ 'Tìm kiếm mã, mô tả...' }}" value="{{ request('keyword') }}">
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-4">
                            <div class="form-group">
                                <label for="status">{{ 'Trạng thái' }}</label>
                                <select id="status" name="status" class="form-control">
                                    <option value="">{{ 'Tất cả trạng thái' }}</option>
                                    <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>{{ 'Hoạt Động' }}</option>
                                    <option value="expired" {{ request('status') == 'expired' ? 'selected' : '' }}>{{ 'Hết Hạn' }}</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-2">
                            <div class="form-group" style="margin-top:25px;display:flex;gap:8px;">
                                <button type="submit" class="btn btn-primary" style="width:100%;"><i class="fa fa-search"></i> {{ 'Lọc' }}</button>
                                <a href="{{ route('admin.coupons.index') }}" class="btn btn-default" style="width:100%;"><i class="fa fa-refresh"></i></a>
                            </div>
                        </div>
                    </form>

                    <div class="table-responsive">
                        <table class="table table-striped table-hover table-bordered">
                            <thead>
                                <tr>
                                    <th style="width:70px;">#</th>
                                    <th style="width:140px;">{{ 'Mã Giảm Giá' }}</th>
                                    <th style="width:150px;">{{ 'Loại Giảm Giá' }}</th>
                                    <th>Giá trị</th>
                                    <th style="width:120px;">{{ 'Đã Dùng' }}</th>
                                    <th style="width:180px;">{{ 'Hạn Sử Dụng' }}</th>
                                    <th style="width:120px;">{{ 'Trạng thái' }}</th>
                                    <th style="width:260px;">{{ 'Hành động' }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($coupons as $coupon)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td><span class="label label-info">{{ $coupon->code }}</span></td>
                                        <td>
                                            @if($coupon->discount_type === 'percentage')
                                                <span class="label label-success">{{ 'Giảm' }} {{ $coupon->discount_value }}%</span>
                                            @else
                                                <span class="label label-success">{{ 'Giảm' }} {{ number_format($coupon->discount_value, 0, ',', '.') }}đ</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($coupon->discount_type === 'percentage')
                                                {{ 'Giảm' }} {{ $coupon->discount_value }}%
                                                @if($coupon->max_discount)
                                                    ({{ 'Tối Đa' }} {{ number_format($coupon->max_discount, 0, ',', '.') }}đ)
                                                @endif
                                            @else
                                                {{ 'Giảm' }} {{ number_format($coupon->discount_value, 0, ',', '.') }}đ
                                            @endif
                                        </td>
                                        <td>{{ $coupon->usage_count }} / {{ $coupon->usage_limit ?? '∞' }}</td>
                                        <td>
                                            @if($coupon->valid_until)
                                                {{ $coupon->valid_until->format('d/m/Y H:i') }}
                                                @if($coupon->valid_until->isPast())
                                                    <div><span class="label label-danger">{{ 'Hết Hạn' }}</span></div>
                                                @endif
                                            @else
                                                <span class="label label-default">{{ 'Không Giới Hạn' }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if($coupon->is_active)
                                                <span class="label label-success">{{ 'Hoạt Động' }}</span>
                                            @else
                                                <span class="label label-default">{{ 'Không Hoạt Động' }}</span>
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
                                                    <i class="fa fa-edit"></i> {{ 'Sửa' }}
                                                </button>

                                                <form action="{{ route('admin.coupons.toggle', $coupon) }}" method="POST" style="display:inline-block;">
                                                    @csrf
                                                    @method('PATCH')
                                                    @php $msg = 'Bạn có chắc chắn muốn thay đổi trạng thái?'; @endphp
                                                    <button type="submit" class="btn btn-sm {{ $coupon->is_active ? 'btn-warning' : 'btn-success' }}" onclick="return confirm('{{ $msg }}');">
                                                        <i class="fa {{ $coupon->is_active ? 'fa-eye-slash' : 'fa-check' }}"></i>
                                                        {{ $coupon->is_active ? 'Vô Hiệu Hóa' : 'Kích Hoạt' }}
                                                    </button>
                                                </form>

                                                    <form action="{{ route('admin.coupons.destroy', $coupon) }}" method="POST" style="display:inline-block;">
                                                        @csrf
                                                        @method('DELETE')
                                                        @php $deleteMsg = 'Xác Nhận Xóa'; @endphp
                                                        <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('{{ $deleteMsg }}');">
                                                        <i class="fa fa-trash"></i> {{ 'Xóa' }}
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
                <h4 class="modal-title" id="createCouponModalLabel">{{ 'Thêm sản phẩm' }} mã giảm giá</h4>
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
                        <label for="create_min_order_amount">{{ 'Đơn hàng tối thiểu' }}</label>
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
                        <label for="create_is_active">{{ 'Trạng thái' }}</label>
                        <select id="create_is_active" name="is_active" class="form-control">
                            <option value="1" selected>{{ 'Hoạt Động' }}</option>
                            <option value="0">Vô hiệu</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="create_description">{{ 'Mô tả' }}</label>
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
                <h4 class="modal-title" id="editCouponModalLabel">{{ 'Chỉnh Sửa Mã Giảm Giá' }}</h4>
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
                        <label for="edit_min_order_amount">{{ 'Đơn hàng tối thiểu' }}</label>
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
                        <label for="edit_is_active">{{ 'Trạng thái' }}</label>
                        <select id="edit_is_active" name="is_active" class="form-control">
                            <option value="1">{{ 'Hoạt Động' }}</option>
                            <option value="0">Vô hiệu</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="edit_description">{{ 'Mô tả' }}</label>
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
