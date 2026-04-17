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
@endphp
<div class="right_col" role="main">
    <div class="page-title">
        <div class="title_left">
            <h3>{{ __('messages.role_management') }}</h3>
        </div>
    </div>

    <div class="clearfix"></div>

    <div class="row">
        <div class="col-md-12">
            <div class="x_panel">
                <div class="x_title" style="display:flex; justify-content:space-between; align-items:center;">
                    <h2>{{ __('messages.role_list') }}</h2>
                    <button type="button" class="btn btn-success btn-sm" id="btn-open-create-role">
                        <i class="fa fa-plus"></i> {{ __('messages.add_role') }}
                    </button>
                </div>

                <div class="x_content">
                    <div class="table-responsive">
                        <table class="table table-striped table-hover table-bordered">
                            <thead>
                                <tr>
                                    <th style="width:70px;">#</th>
                                    <th>{{ __('messages.role_name') }}</th>
                                    <th style="width:160px; text-align:center;">{{ __('messages.permissions_count') }}</th>
                                    <th style="width:180px; text-align:center;">{{ __('messages.users_count') }}</th>
                                    <th style="width:220px; text-align:center;">{{ __('messages.action') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($roles as $role)
                                    @php
                                        $isAdminRole = strtolower($role->name) === 'admin';
                                        $roleLabel = $roleDisplayMap[strtolower($role->name)] ?? $role->name;
                                    @endphp
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>
                                            <strong class="{{ $isAdminRole ? 'text-danger' : 'text-primary' }}">{{ $roleLabel }}</strong>
                                            @if($isAdminRole)
                                                <br><small style="color:#888;">{{ __('messages.admin_cannot_edit') }}</small>
                                            @endif
                                        </td>
                                        <td style="text-align:center;">
                                            <span class="label label-info">{{ $role->permissions_count }}</span>
                                        </td>
                                        <td style="text-align:center;">
                                            <span class="label label-default">{{ $role->users_count }}</span>
                                        </td>
                                        <td style="text-align:center;">
                                            <div class="btn-group btn-group-sm" role="group">
                                                <a href="{{ route('admin.permissions', ['role_id' => $role->id]) }}" class="btn btn-primary">
                                                    <i class="fa fa-key"></i> {{ __('messages.assign_permissions') }}
                                                </a>
                                                @unless($isAdminRole)
                                                    <button
                                                        type="button"
                                                        class="btn btn-info btn-edit-role"
                                                        data-id="{{ $role->id }}"
                                                        data-name="{{ e($role->name) }}"
                                                    >
                                                        <i class="fa fa-edit"></i>
                                                    </button>
                                                    <button
                                                        type="button"
                                                        class="btn btn-danger btn-delete-role"
                                                        data-id="{{ $role->id }}"
                                                        data-name="{{ e($role->name) }}"
                                                        data-users-count="{{ $role->users_count }}"
                                                    >
                                                        <i class="fa fa-trash"></i>
                                                    </button>
                                                @endunless
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center">{{ __('messages.no_role_message') }}</td>
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
