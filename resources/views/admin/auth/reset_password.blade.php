@extends('layouts.client')

@section('title','Đặt lại mật khẩu')
@section('breadcrumb','Đặt lại mật khẩu')

@section('content')

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">

            <div class="card shadow">
                <div class="card-header text-center">
                    <h4>Đặt lại mật khẩu</h4>
                </div>

                <div class="card-body">

                    @if (session('error'))
                        <x-alert type="danger" :message="session('error')" />
                    @endif

                    @if (session('success'))
                        <x-alert type="success">
                            <strong>✓ Thành công!</strong> {{ session('success') }}
                        </x-alert>
                        <script>
                            setTimeout(function() {
                                window.location.href = "{{ route('login') }}";
                            }, 2000);
                        </script>
                    @endif

                    @if ($errors->any())
                        <x-alert type="danger" dismissible>
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </x-alert>
                    @endif

                    <form method="POST" action="{{ route('password.update') }}" id="reset_password_form">
                        @csrf

                        {{-- TOKEN --}}
                        <input type="hidden" name="token" value="{{ $token }}">

                        {{-- EMAIL --}}
                        <div class="mb-3">
                            <label>Email</label>
                            <input type="email"
                                   name="email"
                                   id="email"
                                   class="form-control"
                                   value="{{ $email ?? old('email') }}"
                                   required>

                            <small class="text-danger error" id="error_email"></small>
                        </div>

                        {{-- PASSWORD --}}
                        <div class="mb-3">
                            <label>Mật khẩu mới</label>
                            <div class="password-wrapper" style="margin-bottom: 0;">
                                <input type="password"
                                       name="password"
                                       id="reset_password"
                                       class="form-control"
                                       required>
                                <button class="toggle-password-btn" type="button" onclick="window.togglePasswordVisibility('#reset_password')">
                                    <i class="fas fa-eye toggle-password-icon"></i>
                                </button>
                            </div>

                            <small class="text-danger error" id="error_password"></small>
                        </div>

                        {{-- CONFIRM PASSWORD --}}
                        <div class="mb-3">
                            <label>Nhập lại mật khẩu</label>
                            <div class="password-wrapper" style="margin-bottom: 0;">
                                <input type="password"
                                       name="password_confirmation"
                                       id="reset_password_confirm"
                                       class="form-control"
                                       required>
                                <button class="toggle-password-btn" type="button" onclick="window.togglePasswordVisibility('#reset_password_confirm')">
                                    <i class="fas fa-eye toggle-password-icon"></i>
                                </button>
                            </div>

                            <small class="text-danger error" id="error_password_confirmation"></small>
                        </div>

                        <button type="submit"
                                class="btn btn-success w-100">
                            Cập nhật mật khẩu
                        </button>

                    </form>

                </div>
            </div>

        </div>
    </div>
</div>

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

<script>
    function togglePassword(fieldId) {
        const field = document.getElementById(fieldId);
        const button = event.target.closest('button');
        const icon = button.querySelector('i');
        
        if (field.type === 'password') {
            field.type = 'text';
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        } else {
            field.type = 'password';
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        }
    }
</script>

@endsection