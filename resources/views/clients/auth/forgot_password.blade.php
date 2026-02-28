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

                @if ($errors->any())
                    <x-alert type="danger" dismissible>
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </x-alert>
                @endif

                @if (session('success'))
                    <x-alert type="success" :message="session('success')" />
                @endif

                @if (session('error'))
                    <x-alert type="danger" :message="session('error')" />
                @endif

                <form action="{{ route('password.email') }}" method="POST" onsubmit="return confirmEmail()">
                    @csrf
                    <input type="email" name="email" placeholder="Email *" value="{{ old('email') }}" required>
                    <small class="text-danger" id="error_email"></small>

                    <button class="theme-btn-1 btn btn-block" type="submit">GỬI LINK ĐẶT LẠI MẬT KHẨU</button>
                </form>

                <script>
                    function confirmEmail() {
                        const emailInput = document.querySelector('input[name="email"]');
                        const email = emailInput.value.trim();
                        
                        if (!email) {
                            alert('Vui lòng nhập email');
                            return false;
                        }
                        
                        return confirm(`Có chắc chắn gửi link đặt lại mật khẩu đến email:\n${email}?`);
                    }
                </script>
            </div>
        </div>
    </div>
</div>


@endsection