@extends('layouts.client')

@section ('title','Đăng ký')
@section ('breadcrumb','Đăng ký')

@section ('content')

<div class="ltn__login-area pb-110">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="section-title-area text-center">
                    <h1 class="section-title">Đăng ký <br>Tài khoản</h1>
                    <p>
                        Tham gia cùng chúng tôi để mua sắm rau củ, thực phẩm sạch <br>
                        nhanh chóng, tiện lợi và an toàn mỗi ngày.
                    </p>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-6 offset-lg-3">
                <div class="account-login-inner">
                   <form action="{{ route('register') }}" id="register_form" method="POST">
                    @csrf

                    <input type="text" name="full_name" placeholder="Họ và tên"
                        value="{{ old('full_name') }}" required>
                    <small class="text-danger error" id="error_full_name"></small>

                    <input type="email" name="email" placeholder="Email *"
                        value="{{ old('email') }}" required>
                    <small class="text-danger error" id="error_email"></small>

                    <input type="password" name="password" placeholder="Mật khẩu *" required>
                    <small class="text-danger error" id="error_password"></small>

                    <input type="password" name="password_confirmation"
                        placeholder="Xác nhận mật khẩu *" required>
                    <small class="text-danger error" id="error_password_confirmation"></small>

                    <label class="checkbox-inline">
                        <input type="checkbox" name="checkbox1" id="checkbox1" required>
                        Tôi đồng ý cho phép cửa hàng xử lý thông tin cá nhân
                    </label>
                    <small class="text-danger error" id="error_checkbox1"></small>

                    <label class="checkbox-inline">
                        <input type="checkbox" name="checkbox2" id="checkbox2" required>
                        Tôi đã đọc và đồng ý với chính sách bảo mật
                    </label>
                    <small class="text-danger error" id="error_checkbox2"></small>

                    <button type="submit">TẠO TÀI KHOẢN</button>
                </form>




                    <div class="by-agree text-center">
                        <p>Khi tạo tài khoản, bạn đồng ý với:</p>
                        <p>
                            <a href="#">
                                ĐIỀU KHOẢN SỬ DỤNG &nbsp; | &nbsp; CHÍNH SÁCH BẢO MẬT
                            </a>
                        </p>

                        <div class="go-to-btn mt-50">
                            <a href="{{ route('login') }}">ĐÃ CÓ TÀI KHOẢN? ĐĂNG NHẬP</a>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

@endsection
<style>
.error {
    display: block;
    font-size: 13px;
    margin-top: 4px;
}
input.is-invalid {
    border: 1px solid #dc3545;
}
</style>
