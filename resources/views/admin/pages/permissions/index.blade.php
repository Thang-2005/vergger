@extends('layouts.admin')

@section('title', __('messages.role_management'))

@section('content')
@php
    $roleDisplayMap = [
        'admin' => __('messages.admin'),
        'staff' => __('messages.staff'),
        'customer' => __('messages.customer'),
        'accountant' => __('messages.accountant'),
    ];

    $permissionDisplayMap = [
        'users.view' => __('messages.users_view'),
        'users.manage' => __('messages.users_manage'),
        'categories.view' => __('messages.categories_view'),
        'categories.create' => __('messages.categories_module') . ': ' . __('messages.add_product'),
        'categories.update' => __('messages.categories_module') . ': ' . __('messages.edit'),
        'categories.delete' => __('messages.categories_module') . ': ' . __('messages.delete'),
        'categories.toggle_status' => __('messages.categories_module') . ': ' . __('messages.show') . '/' . __('messages.hide'),
        'products.view' => __('messages.products_view'),
        'products.create' => __('messages.products_module') . ': ' . __('messages.add_product'),
        'products.update' => __('messages.products_module') . ': ' . __('messages.edit'),
        'products.delete' => __('messages.products_module') . ': ' . __('messages.delete'),
        'orders.view' => __('messages.orders_view'),
        'orders.manage' => __('messages.orders_manage'),
        'contacts.view' => __('messages.contacts_view'),
        'contacts.manage' => __('messages.contacts_manage'),
        'permissions.view' => __('messages.permissions_view'),
        'permissions.manage' => __('messages.permissions_manage'),
        'permissions.assign' => __('messages.permissions_assign'),
        'permissions.create' => __('messages.permissions_create'),
    ];
@endphp
<div class="right_col" role="main">
    <div class="page-title">
        <div class="title_left">
            <h3>{{ __('messages.permission_by_role') }}</h3>
        </div>
    </div>

    <div class="clearfix"></div>

    <div class="row">
        <div class="col-md-3">
            <div class="x_panel">
                <div class="x_title">
                    <h2>{{ __('messages.role_list') }}</h2>
                    <div class="clearfix"></div>
                </div>

                <div class="x_content">
                    @if($roles->isEmpty())
                        <div class="alert alert-info">
                            {{ __('messages.no_role_message') }}
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
                                        <small style="display:block; opacity:0.8;">{{ __('messages.full_permission') }}</small>
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
                            {{ __('messages.role_permissions') }}: {{ $roleDisplayMap[strtolower($selectedRole->name)] ?? $selectedRole->name }}
                        @else
                            {{ __('messages.no_role_selected') }}
                        @endif
                    </h2>
                    <a href="{{ route('admin.roles.index') }}" class="btn btn-default btn-sm">
                        <i class="fa fa-users"></i> {{ __('messages.role_management') }}
                    </a>
                </div>

                <div class="x_content">
                    @if(!$selectedRole)
                        <div class="alert alert-info">{{ __('messages.no_role_for_permissions') }}</div>
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
                                    'USERS' => __('messages.users_module'),
                                    'CATEGORIES' => __('messages.categories_module'),
                                    'PRODUCTS' => __('messages.products_module'),
                                    'ORDERS' => __('messages.orders_module'),
                                    'CONTACTS' => __('messages.contacts_module'),
                                    'PERMISSIONS' => __('messages.permissions_module'),
                                    'KHÁC' => __('messages.other_module'),
                                ];
                            @endphp

                            <div class="permission-toolbar">
                                <div class="permission-search-wrap">
                                    <i class="fa fa-search"></i>
                                    <input type="text" id="permission-search" class="form-control" placeholder="{{ __('messages.permission_search') }}">
                                </div>
                                <div class="permission-toolbar-actions">
                                    <button type="button" class="btn btn-default btn-sm" id="expand-all-groups">{{ __('messages.expand_all') }}</button>
                                    <button type="button" class="btn btn-default btn-sm" id="collapse-all-groups">{{ __('messages.collapse_all') }}</button>
                                </div>
                            </div>

                            @foreach($permissionGroups as $groupKey => $groupPermissions)
                                <div class="permission-group-block" data-group-block="group-{{ $loop->index }}">
                                    <div class="permission-group-title" data-toggle-group="group-{{ $loop->index }}">
                                        <i class="fa fa-folder-open"></i>
                                        <strong class="permission-group-name">{{ __('messages.group_label') }} {{ $groupLabelMap[$groupKey] ?? $groupKey }}</strong>
                                        <span class="label label-info">{{ $groupPermissions->count() }} {{ __('messages.permissions_count') }}</span>
                                        <span class="label label-success group-selected-count" data-group-id="group-{{ $loop->index }}">0 {{ __('messages.selected') }}</span>
                                        <i class="fa fa-chevron-up permission-toggle-icon"></i>
                                    </div>

                                    <div class="table-responsive permission-group-body" id="group-{{ $loop->index }}">
                                        <table class="table table-bordered table-hover permission-table">
                                            <thead>
                                                <tr>
                                                    <th style="width:70px;">{{ __('messages.seq_no') }}</th>
                                                    <th>{{ __('messages.permission_name') }}</th>
                                                    <th style="width:130px; text-align:center;">{{ __('messages.assign_permission') }}</th>
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
                                        <i class="fa fa-save"></i> {{ __('messages.save_changes') }}
                                    </button>
                                @else
                                    <small style="color:#999;">{{ __('messages.role') }} {{ __('messages.admin') }} {{ __('messages.admin_no_edit') }}</small>
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
