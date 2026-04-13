@extends('layouts.admin')

@section('title', 'Phân quyền vai trò')

@section('content')
@php
    $roleDisplayMap = [
        'admin' => 'Quản trị viên',
        'staff' => 'Nhân viên',
        'customer' => 'Khách hàng',
        'accountant' => 'Kế toán',
    ];

    $permissionDisplayMap = [
        'users.view' => 'Người dùng: Xem danh sách',
        'users.manage' => 'Người dùng: Quản lý (nâng/hạ quyền, trạng thái)',
        'categories.view' => 'Danh mục: Xem',
        'categories.create' => 'Danh mục: Thêm',
        'categories.update' => 'Danh mục: Sửa',
        'categories.delete' => 'Danh mục: Xóa',
        'categories.toggle_status' => 'Danh mục: Ẩn/Hiện',
        'products.view' => 'Sản phẩm: Xem',
        'products.create' => 'Sản phẩm: Thêm',
        'products.update' => 'Sản phẩm: Sửa',
        'products.delete' => 'Sản phẩm: Xóa',
        'orders.view' => 'Đơn hàng: Xem',
        'orders.manage' => 'Đơn hàng: Quản lý',
        'contacts.view' => 'Liên hệ: Xem',
        'contacts.manage' => 'Liên hệ: Quản lý',
        'permissions.view' => 'Phân quyền: Xem',
        'permissions.manage' => 'Phân quyền: Quản lý',
        'permissions.assign' => 'Phân quyền: Gán quyền cho vai trò',
        'permissions.create' => 'Phân quyền: Tạo quyền mới',
    ];
@endphp
<div class="right_col" role="main">
    <div class="page-title">
        <div class="title_left">
            <h3>Phân quyền theo vai trò</h3>
        </div>
    </div>

    <div class="clearfix"></div>

    <div class="row">
        <div class="col-md-3">
            <div class="x_panel">
                <div class="x_title">
                    <h2>Danh sách vai trò</h2>
                    <div class="clearfix"></div>
                </div>

                <div class="x_content">
                    @if($roles->isEmpty())
                        <div class="alert alert-info">
                            Chưa có vai trò nào.
                        </div>
                    @else
                        <div class="list-group role-list" style="margin-bottom:0;">
                            @foreach($roles as $role)
                                <a
                                    href="{{ route('admin.permissions', ['role_id' => $role->id]) }}"
                                    class="list-group-item {{ $selectedRole && $selectedRole->id === $role->id ? 'active' : '' }}"
                                >
                                    <strong>{{ $roleDisplayMap[strtolower($role->name)] ?? $role->name }}</strong>
                                    @if(strtolower($role->name) === 'admin')
                                        <small style="display:block; opacity:0.8;">Quyền đầy đủ</small>
                                    @endif
                                </a>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-md-9">
            <div class="x_panel">
                <div class="x_title" style="display:flex; justify-content:space-between; align-items:center;">
                    <h2>
                        @if($selectedRole)
                            Quyền của vai trò: {{ $roleDisplayMap[strtolower($selectedRole->name)] ?? $selectedRole->name }}
                        @else
                            Chưa chọn vai trò
                        @endif
                    </h2>
                    <a href="{{ route('admin.roles.index') }}" class="btn btn-default btn-sm">
                        <i class="fa fa-users"></i> Quản lý vai trò
                    </a>
                </div>

                <div class="x_content">
                    @if(!$selectedRole)
                        <div class="alert alert-info">Không có vai trò để phân quyền.</div>
                    @else
                        <form id="role-permissions-form" data-update-url="{{ route('admin.permissions.update-role', $selectedRole) }}">
                            @php
                                $permissionGroups = $permissions->groupBy(function ($permission) {
                                    $name = $permission->name;

                                    if (str_contains($name, '.')) {
                                        return strtoupper(explode('.', $name)[0]);
                                    }

                                    if (str_contains($name, '_')) {
                                        return strtoupper(explode('_', $name)[0]);
                                    }

                                    return 'KHÁC';
                                });

                                $groupLabelMap = [
                                    'USERS' => 'NGƯỜI DÙNG',
                                    'CATEGORIES' => 'DANH MỤC',
                                    'PRODUCTS' => 'SẢN PHẨM',
                                    'ORDERS' => 'ĐƠN HÀNG',
                                    'CONTACTS' => 'LIÊN HỆ',
                                    'PERMISSIONS' => 'PHÂN QUYỀN',
                                    'KHÁC' => 'KHÁC',
                                ];
                            @endphp

                            <div class="permission-toolbar">
                                <div class="permission-search-wrap">
                                    <i class="fa fa-search"></i>
                                    <input type="text" id="permission-search" class="form-control" placeholder="Tìm quyền theo tên hoặc mô tả...">
                                </div>
                                <div class="permission-toolbar-actions">
                                    <button type="button" class="btn btn-default btn-sm" id="expand-all-groups">Mở tất cả</button>
                                    <button type="button" class="btn btn-default btn-sm" id="collapse-all-groups">Thu gọn</button>
                                </div>
                            </div>

                            @foreach($permissionGroups as $groupKey => $groupPermissions)
                                <div class="permission-group-block" data-group-block="group-{{ $loop->index }}">
                                    <div class="permission-group-title" data-toggle-group="group-{{ $loop->index }}">
                                        <i class="fa fa-folder-open"></i>
                                        <strong class="permission-group-name">Nhóm: {{ $groupLabelMap[$groupKey] ?? $groupKey }}</strong>
                                        <span class="label label-info">{{ $groupPermissions->count() }} quyền</span>
                                        <span class="label label-success group-selected-count" data-group-id="group-{{ $loop->index }}">0 đã chọn</span>
                                        <i class="fa fa-chevron-up permission-toggle-icon"></i>
                                    </div>

                                    <div class="table-responsive permission-group-body" id="group-{{ $loop->index }}">
                                        <table class="table table-bordered table-hover permission-table">
                                            <thead>
                                                <tr>
                                                    <th style="width:70px;">STT</th>
                                                    <th>Tên quyền</th>
                                                    <th style="width:130px; text-align:center;">Gán quyền</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($groupPermissions->values() as $index => $permission)
                                                    <tr class="permission-row" data-search="{{ strtolower($permission->name . ' ' . ($permissionDisplayMap[$permission->name] ?? $permission->name)) }}">
                                                        <td>{{ $index + 1 }}</td>
                                                        <td>
                                                            <code>{{ $permission->name }}</code>
                                                            <div class="permission-name">{{ $permissionDisplayMap[$permission->name] ?? $permission->name }}</div>
                                                        </td>
                                                        <td style="text-align:center; vertical-align:middle;">
                                                            <input
                                                                type="checkbox"
                                                                class="role-permission-checkbox"
                                                                data-permission-id="{{ $permission->id }}"
                                                                {{ in_array($permission->id, $selectedRolePermissions ?? []) ? 'checked' : '' }}
                                                                {{ strtolower($selectedRole->name) === 'admin' ? 'disabled' : '' }}
                                                            >
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            @endforeach

                            <div class="permission-save-bar text-right" style="margin-top:16px;">
                                @if(strtolower($selectedRole->name) !== 'admin')
                                    <button type="button" class="btn btn-primary" id="save-role-permissions" disabled>
                                        <i class="fa fa-save"></i> Lưu thay đổi
                                    </button>
                                @else
                                    <small style="color:#999;">Vai trò Quản trị viên không chỉnh sửa.</small>
                                @endif
                            </div>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.role-list .list-group-item {
    border-left: 3px solid transparent;
    padding: 10px 12px;
}

.role-list .list-group-item.active {
    border-left-color: #1abb9c;
    background: #f3fbf9;
    color: #1f6f63;
}

.permission-toolbar {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 12px;
    margin-bottom: 14px;
    flex-wrap: wrap;
}

.permission-search-wrap {
    position: relative;
    flex: 1;
    min-width: 240px;
}

.permission-search-wrap i {
    position: absolute;
    left: 12px;
    top: 11px;
    color: #999;
}

.permission-search-wrap input {
    padding-left: 34px;
}

.permission-group-block {
    border: 1px solid #e5e5e5;
    border-radius: 6px;
    margin-bottom: 12px;
    background: #fff;
}

.permission-group-title {
    background: #f9fbfc;
    padding: 10px 12px;
    border-bottom: 1px solid #e5e5e5;
    display: flex;
    align-items: center;
    gap: 10px;
    cursor: pointer;
}

.permission-group-name {
    flex: 1;
}

.permission-toggle-icon {
    color: #78909c;
}

.permission-table {
    margin-bottom: 0;
}

.permission-table th,
.permission-table td {
    padding-top: 8px;
    padding-bottom: 8px;
}

.permission-name {
    margin-top: 4px;
    color: #555;
    font-size: 13px;
}

.role-permission-checkbox {
    width: 18px;
    height: 18px;
}

.permission-save-bar {
    position: sticky;
    bottom: 0;
    background: #fff;
    padding-top: 12px;
}
</style>
@endsection
