@extends('layouts.client')

@section('title','Quên mật khẩu')
@section('breadcrumb','Quên mật khẩu')

@section('content')

<div class="container">
    <div class="row">
        <div class="col-lg-6 offset-lg-3">
            <div class="account-login-inner">
                <h1 class="section-title">Quên mật khẩu</h1>
                <p>Vui lòng nhập email của bạn để nhận link đặt lại mật khẩu.</p>

                <form action="{{ route('password.email') }}" method="POST">
                    @csrf
                    <input type="email" name="email" placeholder="Email *" value="{{ old('email') }}">
                    <small class="text-danger" id="error_email"></small>

                    <button class="theme-btn-1 btn btn-block" type="submit">GỬI LINK ĐẶT LẠI MẬT KHẨU</button>
                </form>
            </div>
        </div>
    </div>
</div>


@endsection