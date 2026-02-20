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

                    <form method="POST" action="{{ route('password.update') }}">
                        @csrf

                        {{-- TOKEN --}}
                        <input type="hidden" name="token" value="{{ $token }}">

                        {{-- EMAIL --}}
                        <div class="mb-3">
                            <label>Email</label>
                            <input type="email"
                                   name="email"
                                   class="form-control"
                                   value="{{ $email ?? old('email') }}"
                                   required>

                            @error('email')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        {{-- PASSWORD --}}
                        <div class="mb-3">
                            <label>Mật khẩu mới</label>
                            <input type="password"
                                   name="password"
                                   class="form-control"
                                   required>

                            @error('password')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        {{-- CONFIRM PASSWORD --}}
                        <div class="mb-3">
                            <label>Nhập lại mật khẩu</label>
                            <input type="password"
                                   name="password_confirmation"
                                   class="form-control"
                                   required>

                            @error('password_confirmation')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
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

@endsection