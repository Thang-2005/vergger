@extends('layouts.client')

@section ('title','Tài khoản')
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
                                        
                                        <a data-bs-toggle="tab" href="#liton_tab_1_4">
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
                                                <p>Xin chào <strong>{{ $user->name }}</strong></p> 
                                                (không phải <strong>{{ Auth::user()->name }}</strong>?
                                                <small><a href="#" data-url="{{ route('logout.customer') }}" class="logoutBtn">Đăng xuất</a></small>)
                                            </p>
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
                                                        <tr>
                                                            <td>1</td>
                                                            <td>22/06/2019</td>
                                                            <td>Đang chờ</td>
                                                            <td>3.000.000đ</td>
                                                            <td><a href="cart.html">Xem</a></td>
                                                        </tr>
                                                        
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    
                                    
                                    <div class="tab-pane fade" id="liton_tab_1_4">
                                        <div class="ltn__myaccount-tab-content-inner">
                                            <p>Các địa chỉ sau sẽ được sử dụng mặc định trên trang thanh toán.</p>
                                           <div class="table-responsive">
                                                <table class="table">
                                                    <thead>
                                                        <tr>
                                                            <th>Tên người nhận</th>
                                                            <th>Địa chỉ</th>
                                                            <th>Thành phố</th>
                                                            <th>Số điện thoại</th>
                                                            <th>Mặc định </th>
                                                            <th>Hành động</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach($address as $addr)
                                                        <tr>
                                                            <td>{{ $addr->full_name }}</td>
                                                            <td>{{ $addr->address }}</td>
                                                            <td>{{ $addr->city }}</td>
                                                            <td>{{ $addr->phone }}</td>
                                                            <td>
                                                                @if($addr->default)
                                                                    <span class="badge bg-success">Mặc định</span>
                                                                @else
                                                                    <button type="button" class="btn btn-sm btn-outline-primary set-default-btn" data-address-id="{{ $addr->id }}">
                                                                        Đặt làm địa chỉ mặc định
                                                                    </button>
                                                                @endif
                                                            </td>
                                                            <td>
                                                                <button type="button" class="btn btn-sm btn-outline-danger delete-address-btn" data-address-id="{{ $addr->id }}" data-address-name="{{ $addr->address }}">
                                                                    Xóa
                                                                </button>
                                                            </td>
                                                        </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                               <button class="btn theme-btn-1"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#add_address">
                                                    Thêm địa chỉ mới
                                                </button>
                                            </div>
                                            <!-- Button trigger modal -->
                                            

                                            <!-- Modal -->
                                            <div class="modal fade" id="add_address" tabindex="-1" role="dialog" aria-labelledby="add_addressTitle" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered" role="document">
                                                <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="exampleModalLongTitle">Thêm địa chỉ mới</h5>
                                                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                    </button>
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

@section('scripts')
@endsection
