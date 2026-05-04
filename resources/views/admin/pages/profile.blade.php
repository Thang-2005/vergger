@extends('layouts.admin')

@section('title', 'Hồ sơ cá nhân')

@section('content')
<div class="right_col" role="main">
    <div class="page-title">
        <div class="title_left">
            <h3>{{ 'Hồ sơ cá nhân' }}</h3>
        </div>
    </div>
    <div class="clearfix"></div>

    <div class="row">
        <div class="col-md-12">

            {{-- Hero Card --}}
            <div class="x_panel p-0" style="border-radius: 8px; overflow: hidden;">
                <div style="height: 80px; background: linear-gradient(135deg, #1a73e8 0%, #0d47a1 100%);"></div>
                <div class="x_content" style="padding: 0 24px 24px;">
                    <div class="d-flex align-items-end" style="margin-top: -36px; margin-bottom: 16px; gap: 16px;">
                        @php $admin = Auth::guard('admin')->user(); @endphp
                        <div>
                            @if($admin->avatar && file_exists(public_path($admin->avatar)))
                            <img src="{{ asset($admin->avatar) }}" alt="Avatar"
                                class="rounded-circle"
                                style="width: 72px; height: 72px; object-fit: cover; border: 3px solid #fff;">
                            @else
                            <div class="rounded-circle d-flex align-items-center justify-content-center"
                                style="width: 72px; height: 72px; background: #1a73e8; font-size: 26px; font-weight: 600; color: #fff; border: 3px solid #fff;">
                                {{ strtoupper(substr($admin->name, 0, 1)) }}
                            </div>
                            @endif
                        </div>
                        <div style="padding-bottom: 4px;">
                            <h4 class="mb-1">
                                {{ $admin->name }}
                                <span class="badge badge-primary ml-2" style="font-size: 11px;">{{ 'Quản trị viên' }}</span>
                            </h4>
                            <small class="text-muted">
                                {{ $admin->email }} &nbsp;·&nbsp; {{ 'Thành viên từ' }} {{ $admin->created_at->format('d/m/Y') }}
                            </small>
                        </div>
                    </div>

                    {{-- Stats --}}
                    <div class="row" style="gap: 0;">
                        <div class="col-md-4 col-sm-6">
                            <div class="tile-stats" style="background: #f8f9fa; border-radius: 6px; padding: 12px 16px; margin-bottom: 10px;">
                                <div class="text-muted" style="font-size: 11px;">{{ 'Ngày tạo tài khoản' }}</div>
                                <div style="font-size: 15px; font-weight: 500;">{{ $admin->created_at->format('d/m/Y') }}</div>
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-6">
                            <div class="tile-stats" style="background: #f8f9fa; border-radius: 6px; padding: 12px 16px; margin-bottom: 10px;">
                                <div class="text-muted" style="font-size: 11px;">{{ 'Đăng nhập lần cuối' }}</div>
                                <div style="font-size: 15px; font-weight: 500;">
                                    {{ $admin->last_login_at ? $admin->last_login_at->format('d/m/Y H:i') : '---' }}
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-6">
                            <div class="tile-stats" style="background: #f8f9fa; border-radius: 6px; padding: 12px 16px; margin-bottom: 10px;">
                                <div class="text-muted" style="font-size: 11px;">{{ 'Trạng thái' }}</div>
                                <div style="font-size: 15px; font-weight: 500; color: #2e7d32;">{{ 'Hoạt Động' }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <div class="row">

        {{-- {{ 'Thông tin tài khoản' }} --}}
        <div class="col-md-7">
            <div class="x_panel">
                <div class="x_title">
                    <h2><i class="fa fa-user mr-2"></i>{{ 'Thông tin tài khoản' }}</h2>
                    <ul class="nav navbar-right panel_toolbox">
                        <li><a class="collapse-link" href="#"><i class="fa fa-chevron-up"></i></a></li>
                    </ul>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="text-muted" style="font-size: 11px; letter-spacing: .03em;">{{ 'Họ và tên' }}</label>
                                <p class="mb-0" style="font-size: 14px;">{{ $admin->name }}</p>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="text-muted" style="font-size: 11px; letter-spacing: .03em;">{{ 'Email' }}</label>
                                <p class="mb-0" style="font-size: 14px;">{{ $admin->email }}</p>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="text-muted" style="font-size: 11px; letter-spacing: .03em;">{{ 'Số điện thoại' }}</label>
                                <p class="mb-0" style="font-size: 14px;">{{ $admin->phone_number ?? '---' }}</p>
                            </div>
                        </div>
                        <div class="col-sm-6">
                            <div class="form-group">
                                <label class="text-muted" style="font-size: 11px; letter-spacing: .03em;">{{ 'Trạng thái' }}</label>
                                <p class="mb-0">
                                    @if($admin->status === 'active')
                                    <span class="badge badge-success">{{ 'Hoạt Động' }}</span>
                                    @elseif($admin->status === 'pending')
                                    <span class="badge badge-warning">{{ 'Chờ Kích Hoạt' }}</span>
                                    @elseif($admin->status === 'banned')
                                    <span class="badge badge-danger">{{ 'Bị Cấm' }}</span>
                                    @else
                                    <span class="badge badge-secondary">{{ ucfirst($admin->status) }}</span>
                                    @endif
                                </p>
                            </div>
                        </div>
                        <div class="col-sm-12">
                            <div class="form-group">
                                <label class="text-muted" style="font-size: 11px; letter-spacing: .03em;">{{ 'Địa chỉ' }}</label>
                                <p class="mb-0" style="font-size: 14px;">{{ $admin->address ?? '---' }}</p>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#editProfileModal">
                        <i class="fa fa-edit mr-1"></i> {{ 'Chỉnh sửa thông tin' }}
                    </button>
                </div>
            </div>
        </div>

        {{-- {{ 'Thông tin tài khoản' }} + Bảo mật --}}
        <div class="col-md-5">
            <div class="x_panel">
                <div class="x_title">
                    <h2><i class="fa fa-lock mr-2"></i>{{ 'Thông tin tài khoản' }}</h2>
                    <ul class="nav navbar-right panel_toolbox">
                        <li><a class="collapse-link" href="#"><i class="fa fa-chevron-up"></i></a></li>
                    </ul>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <div class="form-group">
                        <label class="text-muted" style="font-size: 11px; letter-spacing: .03em;">{{ 'Tên đăng nhập' }}</label>
                        <p class="mb-0" style="font-size: 14px;">{{ $admin->username ?? $admin->email }}</p>
                    </div>
                    <div class="form-group">
                        <label class="text-muted" style="font-size: 11px; letter-spacing: .03em;">{{ 'Vai trò' }}</label>
                        <p class="mb-0"><span class="badge badge-success">{{ $admin->roles->first()->name ?? 'Quản trị viên' }}</span></p>
                    </div>
                    <div class="form-group">
                        <label class="text-muted" style="font-size: 11px; letter-spacing: .03em;">{{ 'Ngày tạo' }}</label>
                        <p class="mb-0" style="font-size: 14px;">{{ $admin->created_at->format('d/m/Y H:i') }}</p>
                    </div>
                    <div class="form-group">
                        <label class="text-muted" style="font-size: 11px; letter-spacing: .03em;">{{ 'Đăng nhập lần cuối' }}</label>
                        <p class="mb-0" style="font-size: 14px;">
                            {{ $admin->last_login_at ? $admin->last_login_at->format('d/m/Y H:i') : '---' }}
                        </p>
                    </div>
                    <hr>
                    <div class="d-flex" style="gap: 8px;">
                        <button type="button" class="btn btn-warning btn-sm" data-toggle="modal" data-target="#changePasswordModal">
                            <i class="fa fa-key mr-1"></i> {{ 'Đổi mật khẩu' }}
                        </button>

                        <button type="submit" class="btn btn-danger btn-sm">
                            @php $logoutMsg = 'Bạn có muốn đăng xuất không?'; @endphp
                            <a href="{{ route('admin.logout')}}" onclick="return confirm('{{ $logoutMsg }}');">
                                <i class="fa fa-sign-out mr-1"></i> {{ 'Đăng xuất' }}
                            </a>
                        </button>

                        

                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

{{-- Modal: Chỉnh sửa hồ sơ --}}
<div class="modal fade" id="editProfileModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">{{ 'Chỉnh Sửa Hồ Sơ' }}</h4>
                <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('admin.profile.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf @method('PUT')
                    <div class="form-group">
                        <label>{{ 'Ảnh Đại Diện' }}</label>
                        <div class="text-center mb-3">
                            <img id="avatarPreview"
                                src="{{ $admin->avatar ? asset($admin->avatar) : asset('asset/admin/build/images/user.png') }}"
                                alt="Avatar"
                                style="width: 100px; height: 100px; object-fit: cover; border-radius: 50%; border: 2px solid #ddd;">
                        </div>
                        <input type="file" name="avatar" class="form-control @error('avatar') is-invalid @enderror" accept="image/*">
                        <small class="text-muted">{{ 'JPEG, PNG, JPG, GIF — tối đa 2MB' }}</small>
                        @error('avatar')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                    </div>
                    <div class="form-group">
                        <label>{{ 'Họ và tên' }} <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror"
                            value="{{ $admin->name }}" required>
                        @error('name')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                    </div>
                    <div class="form-group">
                        <label>{{ 'Số điện thoại' }}</label>
                        <input type="tel" name="phone_number" class="form-control @error('phone_number') is-invalid @enderror"
                            value="{{ $admin->phone_number }}">
                        @error('phone_number')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                    </div>
                    <div class="form-group">
                        <label>{{ 'Địa chỉ' }}</label>
                        <textarea name="address" class="form-control @error('address') is-invalid @enderror"
                            rows="3" placeholder="{{ 'Nhập địa chỉ...' }}">{{ $admin->address }}</textarea>
                        @error('address')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                    </div>

                    <div class="text-right">
                        <button type="button" class="btn btn-default" data-dismiss="modal">{{ 'Hủy' }}</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fa fa-save mr-1"></i> {{ 'Lưu thay đổi' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- Modal: {{ 'Đổi mật khẩu' }} --}}
<div class="modal fade" id="changePasswordModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">{{ 'Đổi mật khẩu' }}</h4>
                <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('admin.profile.change-password') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label>{{ 'Mật Khẩu Hiện Tại' }} <span class="text-danger">*</span></label>
                        <input type="password" name="current_password"
                            class="form-control @error('current_password') is-invalid @enderror" required>
                        @error('current_password')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                    </div>
                    <div class="form-group">
                        <label>{{ 'Mật Khẩu Mới' }} <span class="text-danger">*</span></label>
                        <input type="password" name="password"
                            class="form-control @error('password') is-invalid @enderror" required>
                        @error('password')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                    </div>
                    <div class="form-group">
                        <label>{{ 'Xác Nhận Mật Khẩu Mới' }} <span class="text-danger">*</span></label>
                        <input type="password" name="password_confirmation" class="form-control" required>
                    </div>
                    <div class="text-right">
                        <button type="button" class="btn btn-default" data-dismiss="modal">{{ 'Hủy' }}</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fa fa-save mr-1"></i> {{ 'Cập Nhật Mật Khẩu' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection