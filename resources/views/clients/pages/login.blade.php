@extends('layouts.client')

@section('title','Đăng nhập')
@section('breadcrumb','Đăng nhập')

@section('content')

<div class="ltn__login-area pb-65">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="section-title-area text-center">
                    <h1 class="section-title">
                        Đăng nhập <br> vào tài khoản của bạn
                    </h1>
                    <p>
                        Vui lòng nhập thông tin đăng nhập để tiếp tục.
                    </p>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- FORM ĐĂNG NHẬP -->
            <div class="col-lg-6">
                <div class="account-login-inner">

                    <form 
                        id="login_form"
                        action="{{ route('login.customer') }}"
                        method="POST"
                        class="ltn__form-box contact-form-box"
                    >
                        @csrf

                       <input type="email" name="email" placeholder="Email *">
                        <small class="text-danger" id="error_email"></small>

                        <input type="password" name="password" placeholder="Mật khẩu *">
                        <small class="text-danger" id="error_password"></small>


                        <div class="btn-wrapper mt-0">
                            <button 
                                class="theme-btn-1 btn btn-block" 
                                type="submit"
                            >
                                ĐĂNG NHẬP
                            </button>
                        </div>

                        <div class="go-to-btn mt-20 text-right">
                            <a href="{{ route('password.reset') }}">
                                <small>QUÊN MẬT KHẨU?</small>
                            </a>
                        </div>
                    </form>

                </div>
            </div>

            <!-- TẠO TÀI KHOẢN -->
            <div class="col-lg-6">
                <div class="account-create text-center pt-50">
                    <h4>BẠN CHƯA CÓ TÀI KHOẢN?</h4>
                    <p>
                        Đăng ký để nhận nhiều ưu đãi hơn, <br>
                        theo dõi đơn hàng và mua sắm nhanh chóng.
                    </p>

                    <div class="btn-wrapper">
                        <a 
                            href="{{ route('register') }}" 
                            class="theme-btn-1 btn black-btn"
                        >
                            TẠO TÀI KHOẢN
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
