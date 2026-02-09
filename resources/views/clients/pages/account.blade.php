@extends('layouts.client')

@section ('title','Tài khoản')
@section ('breadcrumb','Tài khoản')

@section ('content')

<div id="ltn__utilize-cart-menu" class="ltn__utilize ltn__utilize-cart-menu">
    <div class="ltn__utilize-menu-inner ltn__scrollbar">
        <div class="ltn__utilize-menu-head">
            <span class="ltn__utilize-menu-title">Giỏ hàng</span>
            <button class="ltn__utilize-close">×</button>
        </div>
        <div class="mini-cart-product-area ltn__scrollbar">
            <div class="mini-cart-item clearfix">
                <div class="mini-cart-img">
                    <a href="#"><img src="{{ asset('asset/client/img/product/1.png') }}" alt="Hình ảnh"></a>
                    <span class="mini-cart-item-delete"><i class="icon-cancel"></i></span>
                </div>
                <div class="mini-cart-info">
                    <h6><a href="#">Cà chua đỏ</a></h6>
                    <span class="mini-cart-quantity">1 x 65.000đ</span>
                </div>
            </div>
            <div class="mini-cart-item clearfix">
                <div class="mini-cart-img">
                    <a href="#"><img src="{{ asset('asset/client/img/product/2.png') }}" alt="Hình ảnh"></a>
                    <span class="mini-cart-item-delete"><i class="icon-cancel"></i></span>
                </div>
                <div class="mini-cart-info">
                    <h6><a href="#">Nước ép rau củ</a></h6>
                    <span class="mini-cart-quantity">1 x 85.000đ</span>
                </div>
            </div>
            <div class="mini-cart-item clearfix">
                <div class="mini-cart-img">
                    <a href="#"><img src="{{ asset('asset/client/img/product/3.png') }}" alt="Hình ảnh"></a>
                    <span class="mini-cart-item-delete"><i class="icon-cancel"></i></span>
                </div>
                <div class="mini-cart-info">
                    <h6><a href="#">Cam lát trộn</a></h6>
                    <span class="mini-cart-quantity">1 x 92.000đ</span>
                </div>
            </div>
            <div class="mini-cart-item clearfix">
                <div class="mini-cart-img">
                    <a href="#"><img src="{{ asset('asset/client/img/product/4.png') }}" alt="Hình ảnh"></a>
                    <span class="mini-cart-item-delete"><i class="icon-cancel"></i></span>
                </div>
                <div class="mini-cart-info">
                    <h6><a href="#">Nước cam tươi</a></h6>
                    <span class="mini-cart-quantity">1 x 68.000đ</span>
                </div>
            </div>
        </div>
        <div class="mini-cart-footer">
            <div class="mini-cart-sub-total">
                <h5>Tạm tính: <span>310.000đ</span></h5>
            </div>
            <div class="btn-wrapper">
                <a href="cart.html" class="theme-btn-1 btn btn-effect-1">Xem giỏ hàng</a>
                <a href="cart.html" class="theme-btn-2 btn btn-effect-2">Thanh toán</a>
            </div>
            <p>Miễn phí vận chuyển cho đơn hàng trên 100.000đ!</p>
        </div>
    </div>
</div>

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
                                            Tổng quan <i class="fas fa-home"></i>
                                        </a>
                                        <a data-bs-toggle="tab" href="#liton_tab_1_2">
                                            Đơn hàng <i class="fas fa-file-alt"></i>
                                        </a>
                                        <a data-bs-toggle="tab" href="#liton_tab_1_3">
                                            Tải xuống <i class="fas fa-arrow-down"></i>
                                        </a>
                                        <a data-bs-toggle="tab" href="#liton_tab_1_4">
                                            Địa chỉ <i class="fas fa-map-marker-alt"></i>
                                        </a>
                                        <a data-bs-toggle="tab" href="#liton_tab_1_5">
                                            Thông tin tài khoản <i class="fas fa-user"></i>
                                        </a>
                                        <a href="{{ route('logout.customer') }}" id="logoutBtn">
                                            Đăng xuất <i class="fas fa-sign-out-alt"></i>
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
                                                <small><a href="{{ route('logout.customer') }}">Đăng xuất</a></small>)
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
                                                        <tr>
                                                            <td>2</td>
                                                            <td>22/11/2019</td>
                                                            <td>Đã duyệt</td>
                                                            <td>200.000đ</td>
                                                            <td><a href="cart.html">Xem</a></td>
                                                        </tr>
                                                        <tr>
                                                            <td>3</td>
                                                            <td>12/01/2020</td>
                                                            <td>Đang giữ</td>
                                                            <td>990.000đ</td>
                                                            <td><a href="cart.html">Xem</a></td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="tab-pane fade" id="liton_tab_1_3">
                                        <div class="ltn__myaccount-tab-content-inner">
                                            <div class="table-responsive">
                                                <table class="table">
                                                    <thead>
                                                        <tr>
                                                            <th>Sản phẩm</th>
                                                            <th>Ngày</th>
                                                            <th>Hết hạn</th>
                                                            <th>Tải xuống</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <td>Carsafe - Mẫu PSD dịch vụ xe hơi</td>
                                                            <td>22/11/2020</td>
                                                            <td>Có</td>
                                                            <td>
                                                                <a href="#">
                                                                    <i class="far fa-arrow-to-bottom mr-1"></i>
                                                                    Tải xuống
                                                                </a>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>Carsafe - Mẫu HTML dịch vụ xe hơi</td>
                                                            <td>10/11/2020</td>
                                                            <td>Có</td>
                                                            <td>
                                                                <a href="#">
                                                                    <i class="far fa-arrow-to-bottom mr-1"></i>
                                                                    Tải xuống
                                                                </a>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td>Carsafe - Theme WordPress dịch vụ xe hơi</td>
                                                            <td>12/11/2020</td>
                                                            <td>Có</td>
                                                            <td>
                                                                <a href="#">
                                                                    <i class="far fa-arrow-to-bottom mr-1"></i>
                                                                    Tải xuống
                                                                </a>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="tab-pane fade" id="liton_tab_1_4">
                                        <div class="ltn__myaccount-tab-content-inner">
                                            <p>Các địa chỉ sau sẽ được sử dụng mặc định trên trang thanh toán.</p>
                                            <div class="row">
                                                <div class="col-md-6 col-12 learts-mb-30">
                                                    <h4>Địa chỉ thanh toán <small><a href="#">sửa</a></small></h4>
                                                    <address>
                                                        <p><strong>Alex Tuntuni</strong></p>
                                                        <p>1355 Market St, Suite 900 <br>
                                                            San Francisco, CA 94103</p>
                                                        <p>Di động: (123) 456-7890</p>
                                                    </address>
                                                </div>
                                                <div class="col-md-6 col-12 learts-mb-30">
                                                    <h4>Địa chỉ giao hàng <small><a href="#">sửa</a></small></h4>
                                                    <address>
                                                        <p><strong>Alex Tuntuni</strong></p>
                                                        <p>1355 Market St, Suite 900 <br>
                                                            San Francisco, CA 94103</p>
                                                        <p>Di động: (123) 456-7890</p>
                                                    </address>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="tab-pane fade" id="liton_tab_1_5">
                                        <div class="ltn__myaccount-tab-content-inner">
                                            <p>Các thông tin sau sẽ được sử dụng mặc định trên trang thanh toán.</p>
                                            <div class="ltn__form-box">
                                                <form action="#">
                                                    <div class="row mb-50">
                                                        <div class="col-md-6">
                                                            <label>Họ:</label>
                                                            <input type="text" name="ltn__name">
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label>Tên:</label>
                                                            <input type="text" name="ltn__lastname">
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label>Tên hiển thị:</label>
                                                            <input type="text" name="ltn__displayname" placeholder="Ethan">
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label>Email hiển thị:</label>
                                                            <input type="email" name="ltn__email" 
                                                                   placeholder="minhdien.dev@gmail.com">
                                                        </div>
                                                    </div>
                                                    <fieldset>
                                                        <legend>Đổi mật khẩu</legend>
                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <label>Mật khẩu hiện tại (để trống nếu không thay đổi):</label>
                                                                <input type="password" name="current_password">
                                                                <label>Mật khẩu mới (để trống nếu không thay đổi):</label>
                                                                <input type="password" name="new_password">
                                                                <label>Xác nhận mật khẩu mới:</label>
                                                                <input type="password" name="confirm_password">
                                                            </div>
                                                        </div>
                                                    </fieldset>
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
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
$(document).ready(function() {
    $('#logoutBtn').on('click', function(e) {
        e.preventDefault();
        let url = $(this).attr('href');
        
        Swal.fire({
            title: 'Xác nhận đăng xuất?',
            text: "Bạn có chắc chắn muốn đăng xuất?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Đăng xuất',
            cancelButtonText: 'Hủy'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: url,
                    type: 'POST',
                    success: function(res) {
                        Swal.fire({
                            title: 'Thành công!',
                            text: res.message,
                            icon: 'success',
                            timer: 1500,
                            showConfirmButton: false
                        }).then(() => {
                            window.location.href = res.redirect;
                        });
                    },
                    error: function(xhr) {
                        Swal.fire('Lỗi!', 'Có lỗi xảy ra khi đăng xuất', 'error');
                    }
                });
            }
        });
    });
});
</script>
@endsection