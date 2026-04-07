@extends('layouts.client')

@section ('title','Tài khoản' . ' - ' . config('app.name'))
@section ('breadcrumb','Tài khoản')

@section ('content')

<div class="liton__wishlist-area pb-70">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <!-- PRODUCT TAB AREA START -->
                <div class="ltn__product-tab-area">
                    <div class="container">
                        <div class="row">
                            <div class="col-lg-4">
                                <div class="ltn__tab-menu-list mb-50">
                                    <div class="nav">
                                        <a class="active show" data-bs-toggle="tab" href="#liton_tab_1_1">
                                            Bảng điều khiển <i class="fas fa-home"></i>
                                        </a>
                                        <a data-bs-toggle="tab" href="#liton_tab_1_2">
                                            Đơn hàng <i class="fas fa-file-alt"></i>
                                        </a>

                                        <a data-bs-toggle="tab" href="#liton_tab_1_4" class="{{ request('tab') == 'addresses' ? 'active show' : '' }}">
                                            Địa chỉ <i class="fas fa-map-marker-alt"></i>
                                        </a>
                                        <a data-bs-toggle="tab" href="#liton_tab_1_5">
                                            Thông tin tài khoản <i class="fas fa-user"></i>
                                        </a>
                                        <a data-bs-toggle="tab" href="#liton_tab_1_6">
                                            Đổi mật khẩu <i class="fas fa-key"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-8">
                                <div class="tab-content">
                                    <div class="tab-pane fade active show" id="liton_tab_1_1">
                                        <div class="ltn__myaccount-tab-content-inner">
                                            <p>Xin chào <strong>{{ $user->name }}</strong> (không phải <strong>{{ $user->name }}</strong>? <small><a href="#" data-url="{{ route('logout.customer') }}" class="logoutBtn">Đăng xuất</a></small>)</p>
                                            <p>Từ bảng điều khiển tài khoản của bạn, bạn có thể xem
                                                <span>đơn hàng gần đây</span>, quản lý
                                                <span>địa chỉ giao hàng và thanh toán</span>, và
                                                <span>chỉnh sửa mật khẩu cũng như thông tin tài khoản</span>.
                                            </p>
                                        </div>
                                    </div>

                                    <div class="tab-pane fade" id="liton_tab_1_2">
                                        <div class="ltn__myaccount-tab-content-inner">
                                            <div class="table-responsive">
                                                <table class="table">
                                                    @if($orders->isEmpty())
                                                    <p>Bạn chưa có đơn hàng nào.</p>
                                                    @else
                                                    <thead>
                                                        <tr>
                                                            <th>Đơn hàng</th>
                                                            <th>Ngày</th>
                                                            <th>Trạng thái</th>
                                                            <th>Tổng tiền</th>
                                                            <th>Hành động</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach($orders as $order)
                                                        <tr>
                                                            <td>{{ $order->id }}</td>
                                                            <td>{{ $order->created_at->format('d/m/Y') }}</td>
                                                            <td>   
                                                                    @if ($order->status == 'pending')
                                                                    <span class="badge bg-warning text-dark">Chờ xác nhận</span>
                                                                    @elseif ($order->status == 'processing' || $order->status == 'confirmed')
                                                                    <span class="badge bg-primary">Đã xác nhận</span>
                                                                    @elseif ($order->status == 'shipped')
                                                                    <span class="badge bg-info text-dark">Đang giao hàng</span>
                                                                    @elseif ($order->status == 'delivered')
                                                                    <span class="badge bg-success">Đã giao hàng</span>
                                                                    @elseif ($order->status == 'completed')
                                                                    <span class="badge bg-success">Hoàn tất</span>
                                                                    @elseif ($order->status == 'canceled')
                                                                    <span class="badge bg-danger">Đã hủy</span>
                                                                    @else
                                                                    <span class="badge bg-secondary">{{ $order->status }}</span>
                                                                    @endif
                                                            
                                                            </td>
                                                            <td>{{ number_format($order->total_price, 0, ',', '.') }}đ</td>
                                                            <td>
                                                                <a href="{{ route('order.detail', $order->id) }}" class="btn btn-sm btn-outline-info">
                                                                    Xem chi tiết
                                                                </a>
                                                            </td>
                                                        </tr>
                                                        @endforeach
                                                    </tbody>
                                                    @endif
                                                </table>
                                            </div>
                                        </div>
                                    </div>



                                    <div class="tab-pane fade" id="liton_tab_1_4">
                                        <div class="ltn__myaccount-tab-content-inner">
                                            <h5 class="mb-4">Địa chỉ giao hàng mặc định</h5>

                                            <!-- Hiển thị địa chỉ mặc định -->
                                            @if($defaultAddress = $address->firstWhere('default', 1))
                                            <div class="card shadow-sm border-0 mb-4">
                                                <div class="card-body">
                                                    <div class="row">
                                                        <div class="col-md-8">
                                                            <h6 class="mb-2">{{ $defaultAddress->full_name }}</h6>
                                                            <p class="mb-2">
                                                                <i class="fas fa-map-marker-alt me-2"></i>{{ $defaultAddress->address }}, {{ $defaultAddress->city }}
                                                            </p>
                                                            <p class="mb-0">
                                                                <i class="fas fa-phone me-2"></i>{{ $defaultAddress->phone }}
                                                            </p>
                                                        </div>
                                                        <div class="col-md-4 text-end">
                                                            <span class="badge bg-success mb-2">Mặc định</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            @else
                                            <div class="alert alert-info">
                                                <i class="fas fa-info-circle me-2"></i>Bạn chưa có địa chỉ mặc định. Vui lòng thêm một địa chỉ.
                                            </div>
                                            @endif

                                            <!-- Nút xem tất cả địa chỉ và thêm mới -->
                                            <div class="btn-group mb-3" role="group">
                                                <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#view_all_addresses">
                                                    <i class="fas fa-list me-2"></i>Xem tất cả địa chỉ
                                                </button>
                                                <button type="button" class="btn theme-btn-1" data-bs-toggle="modal" data-bs-target="#add_address">
                                                    <i class="fas fa-plus-circle me-2"></i>Thêm địa chỉ mới
                                                </button>
                                            </div>
                                            <!-- Button trigger modal -->


                                            <!-- Modal: Xem tất cả địa chỉ -->
                                            <div class="modal fade" id="view_all_addresses" tabindex="-1" role="dialog" aria-labelledby="viewAddressesTitle" aria-hidden="true">
                                                <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header bg-light border-bottom">
                                                            <h5 class="modal-title" id="viewAddressesTitle">
                                                                <i class="fas fa-map-marker-alt me-2"></i>Tất cả địa chỉ của bạn
                                                            </h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body" style="max-height: 550px; overflow-y: auto;">
                                                            @if($address->isEmpty())
                                                            <div class="text-center py-5">
                                                                <i class="fas fa-inbox" style="font-size: 48px; color: #ddd;"></i>
                                                                <p class="text-muted mt-3 mb-0">Bạn chưa có địa chỉ nào. Hãy thêm một địa chỉ mới.</p>
                                                            </div>
                                                            @else
                                                            @foreach($address as $addr)
                                                            <div class="card mb-3 border shadow-sm border-start border-4 {{ $addr->default ? 'border-success' : 'border-secondary' }}">
                                                                <div class="card-body p-3">
                                                                    <div class="row">
                                                                        <div class="col-md-8">
                                                                            <div class="d-flex align-items-center mb-2">
                                                                                <h6 class="mb-0 fw-bold">{{ $addr->full_name }}</h6>
                                                                                @if($addr->default)
                                                                                <span class="badge bg-success ms-2">Mặc định</span>
                                                                                @endif
                                                                            </div>
                                                                            <p class="mb-2 text-muted small">
                                                                                <i class="fas fa-home me-2" style="width: 16px; color: #6c757d;"></i>{{ $addr->address }}
                                                                            </p>
                                                                            <p class="mb-2 text-muted small">
                                                                                <i class="fas fa-city me-2" style="width: 16px; color: #6c757d;"></i>{{ $addr->city }}
                                                                            </p>
                                                                            <p class="mb-0 text-muted small">
                                                                                <i class="fas fa-phone me-2" style="width: 16px; color: #6c757d;"></i>{{ $addr->phone }}
                                                                            </p>
                                                                        </div>
                                                                        <div class="col-md-4 d-flex gap-2 align-items-center" style="flex-wrap: wrap; justify-content: flex-end;">
                                                                            @if(!$addr->default)
                                                                            <button type="button"
                                                                                class="btn btn-sm btn-outline-success set-default-btn flex-grow-1"
                                                                                data-address-id="{{ $addr->id }}"
                                                                                title="Đặt làm địa chỉ mặc định">
                                                                                <i class="fas fa-check me-1"></i>Chọn
                                                                            </button>
                                                                            @else
                                                                            <button type="button"
                                                                                class="btn btn-sm btn-success flex-grow-1"
                                                                                disabled
                                                                                title="Đây là địa chỉ mặc định">
                                                                                <i class="fas fa-check me-1"></i>Mặc định
                                                                            </button>
                                                                            @endif
                                                                            <button type="button"
                                                                                class="btn btn-sm btn-outline-danger delete-address-btn flex-grow-1"
                                                                                data-address-id="{{ $addr->id }}"
                                                                                data-address-name="{{ $addr->address }}"
                                                                                title="Xóa địa chỉ này">
                                                                                <i class="fas fa-trash me-1"></i>Xóa
                                                                            </button>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            @endforeach
                                                            @endif
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Modal: Thêm địa chỉ mới -->
                                            <div class="modal fade" id="add_address" tabindex="-1" role="dialog" aria-labelledby="add_addressTitle" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="add_addressTitle">Thêm địa chỉ mới</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <form id="add_address_form"
                                                                method="POST"
                                                                action="{{ route('account.add_address') }}">

                                                                @csrf

                                                                <div class="mb-2">
                                                                    <label>Tên người nhận:</label>
                                                                    <input type="text" class="form-control" name="full_name">
                                                                    <div class="invalid-feedback"></div>
                                                                </div>

                                                                <div class="mb-2">
                                                                    <label>Địa chỉ:</label>
                                                                    <input type="text" class="form-control" name="address">
                                                                    <div class="invalid-feedback"></div>
                                                                </div>

                                                                <div class="mb-2">
                                                                    <label>Thành phố:</label>
                                                                    <input type="text" class="form-control" name="city">
                                                                    <div class="invalid-feedback"></div>
                                                                </div>

                                                                <div class="mb-2">
                                                                    <label>Số điện thoại:</label>
                                                                    <input type="text" class="form-control" name="phone">
                                                                    <div class="invalid-feedback"></div>
                                                                </div>

                                                                <div class="mb-2 form-check">
                                                                    <input type="checkbox"
                                                                        class="form-check-input"
                                                                        id="default"
                                                                        name="default">
                                                                    <label class="form-check-label">
                                                                        Đặt làm địa chỉ mặc định
                                                                    </label>
                                                                </div>

                                                                <button type="submit" class="btn theme-btn-1">
                                                                    Lưu địa chỉ
                                                                </button>

                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="tab-pane fade" id="liton_tab_1_5">
                                        <div class="ltn__myaccount-tab-content-inner">
                                            <p>Bạn có thể thay đổi thông tin tài khoản của mình ở đây.</p>
                                            <div class="ltn__form-box">
                                                <form action="{{ route('account.update_profile') }}" id="update_account_form" method="POST" enctype="multipart/form-data">
                                                    @csrf
                                                    @method('PUT')
                                                    <div class="row mb-50">

                                                        <div class="col-md-12 text-center mb-4">

                                                            <label class="profile-avatar">

                                                                {{-- AVATAR IMAGE --}}
                                                                <img id="avatarPreview"
                                                                    src="{{ $user->avatar 
                                                                            ? asset('storage/'.$user->avatar) 
                                                                            : asset('asset/client/img/avatar-placeholder.png') }}"
                                                                    alt="Avatar">

                                                                {{-- FILE INPUT --}}
                                                                <input type="file"
                                                                    id="avatarInput" name="avatar" accept="image/*">
                                                            </label>

                                                        </div>

                                                        <div class="col-md-6">
                                                            <label>Họ và tên:</label>
                                                            <input type="text" name="name" value="{{$user->name}}" required>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label>Email:</label>
                                                            <input type="email" name="email"
                                                                value="{{$user->email}}" readOnly>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label>Số điện thoại:</label>
                                                            <input type="number" name="phone" id="Itn_phone_number" value="{{$user->phone_number}}">
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label>Địa chỉ:</label>
                                                            <input type="text" name="address" value="{{$user->address}}">
                                                        </div>
                                                    </div>

                                                    <div class="btn-wrapper">
                                                        <button type="submit"
                                                            class="btn theme-btn-1 btn-effect-1 text-uppercase">
                                                            Lưu thay đổi
                                                        </button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="tab-pane fade" id="liton_tab_1_6">
                                        <div class="ltn__myaccount-tab-content-inner">
                                            <p>Bạn có thể thay đổi mật khẩu của mình ở đây.</p>
                                            <div class="ltn__form-box">
                                                <form action="{{ route('account.change_password') }}"
                                                    id="update_password_form"
                                                    method="POST">

                                                    @csrf
                                                    @method('PUT')

                                                    <div class="row">

                                                        <div class="col-md-6">
                                                            <label>Mật khẩu hiện tại</label>
                                                            <div class="password-wrapper">
                                                                <input type="password" id="current_password_field" name="current_password" class="form-control">
                                                                <button type="button" class="toggle-password-btn" onclick="window.togglePasswordVisibility('#current_password_field')">
                                                                    <i class="fas fa-eye toggle-password-icon"></i>
                                                                </button>
                                                            </div>
                                                            <small class="text-danger error-message"></small>
                                                        </div>

                                                        <div class="col-md-6">
                                                            <label>Mật khẩu mới</label>
                                                            <div class="password-wrapper">
                                                                <input type="password" id="new_password_field" name="new_password" class="form-control">
                                                                <button type="button" class="toggle-password-btn" onclick="window.togglePasswordVisibility('#new_password_field')">
                                                                    <i class="fas fa-eye toggle-password-icon"></i>
                                                                </button>
                                                            </div>
                                                            <small class="text-danger error-message"></small>
                                                        </div>

                                                        <div class="col-md-6">
                                                            <label>Nhập lại mật khẩu</label>
                                                            <div class="password-wrapper">
                                                                <input type="password" id="new_password_confirm_field" name="new_password_confirmation" class="form-control">
                                                                <button type="button" class="toggle-password-btn" onclick="window.togglePasswordVisibility('#new_password_confirm_field')">
                                                                    <i class="fas fa-eye toggle-password-icon"></i>
                                                                </button>
                                                            </div>

                                                        </div>

                                                        <button type="submit"
                                                            class="btn theme-btn-1 btn-effect-1 text-uppercase mt-3">
                                                            Cập nhật mật khẩu
                                                        </button>

                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- PRODUCT TAB AREA END -->
                </div>
            </div>
        </div>
    </div>


    @endsection