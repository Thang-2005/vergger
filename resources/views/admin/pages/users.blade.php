@extends('layouts.admin')

@section('title', __('Quản Lý Thành Viên'))
@section('content')

<div class="right_col" role="main">
    <div class="">
        <div class="page-title">
            <div class="title_left">
                <h3>Quản Lý Thành Viên</h3>
            </div>
        </div>

        <div class="clearfix"></div>

        <div class="row">
            <div class="x_panel">
                <div class="x_content">
                    <div class="row">
                        @foreach($users as $user)
                        <div class="col-md-4 col-sm-6 profile_details">
                            <div class="well profile_view" style="display: flex; flex-direction: column; min-height: 220px; padding: 15px;">
                                <div class="col-sm-12" style="display: flex; align-items: center; flex: 1; padding: 0;">
                                    
                                    {{-- Thông tin bên trái --}}
                                    <div style="flex: 1; min-width: 0; padding-right: 10px;">
                                        <h4 class="brief " style="margin: 0 0 6px;">
                                            <i>
                                                @if($user->role->name == 'Admin')
                                                    <span class="badge badge-danger">Admin</span>
                                                @elseif($user->role->name == 'Staff')
                                                    <span class="badge badge-primary">Staff</span>
                                                @else
                                                    <span class="badge badge-secondary">Customer</span>
                                                @endif

                                            </i>
                                        </h4>
                                        <h2 style="font-size: 16px; margin: 0 0 6px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                            {{ $user->name }}
                                        </h2>
                                        <p style="margin: 0 0 4px; font-size: 13px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                            <strong>Email: </strong>{{ $user->email ?: 'Chưa cập nhật' }}
                                        </p>
                                        <ul class="list-unstyled" style="margin: 0;">
                                            <li style="font-size: 13px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                                <i class="fa fa-building"></i> {{ $user->address ?: 'Chưa cập nhật' }}
                                            </li>
                                            <li style="font-size: 13px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">
                                                <i class="fa fa-phone"></i> {{ $user->phone ?: 'Chưa cập nhật' }}
                                            </li>
                                        </ul>
                                    </div>

                                    {{-- Avatar bên phải --}}
                                    <div style="flex-shrink: 0; width: 80px; height: 80px; border-radius: 50%; overflow: hidden; background: #f5f5f5;">
                                        <img
                                            src="{{ $user->avatar ? asset('storage/' . $user->avatar) : asset('storage/uploads/avatars/default_avata.png') }}"
                                            alt="{{ $user->name }}"
                                            style="width: 100%; height: 100%; object-fit: cover; display: block;"
                                        >
                                    </div>

                                </div>

                                {{-- Footer --}}
                                <div class="profile-bottom text-center" style="margin-top: 12px; padding-top: 10px; border-top: 1px solid #eee; display: flex; align-items: center; justify-content: space-between;">
                                    <div class="emphasis">
                                        
                                    </div>
                                    <div class="emphasis">
                                        <button type="button" class="btn btn-success btn-sm">
                                            <i class="fa fa-comments-o"></i>
                                        </button>
                                            @if($canManageUsers && in_array($user->role->name, ['Customer', 'Staff']))
                                            @if($user->role->name == 'Customer')
                                            <button type="button" class="btn btn-primary btn-sm upgrateStart" data-user-id="{{ $user->id }}">
                                                <i class="fa fa-user"></i> Lên Staff
                                            </button>
                                            @endif

                                            @if($user->role->name == 'Staff')
                                            <button type="button" class="btn btn-default btn-sm downgradeStart" data-user-id="{{ $user->id }}">
                                                <i class="fa fa-level-down"></i> Hạ quyền
                                            </button>
                                            @endif

                                            @if($user->status=='banned')
                                            <button type="button" class="btn btn-info btn-sm changeStatus" data-user-id="{{ $user->id }}" data-status="active">
                                                <i class="fa fa-unlock"></i> Bỏ chặn
                                            </button>
                                            @else
                                            <button type="button" class="btn btn-warning btn-sm changeStatus" data-user-id="{{ $user->id }}" data-status="banned">
                                                <i class="fa fa-ban"></i> Chặn
                                            </button>
                                            @endif
                                        @endif
                                    </div>
                                </div>

                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection