<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Đăng nhập trang quản trị</title>

    <link rel="shortcut icon" href="{{ asset('asset/client/img/favicon.png') }}" type="image/x-icon" />
    <link href="{{ asset('asset/admin/vendors/bootstrap/dist/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('asset/admin/vendors/font-awesome/css/font-awesome.min.css') }}" rel="stylesheet">
    <link href="{{ asset('asset/admin/vendors/nprogress/nprogress.css') }}" rel="stylesheet">
    <link href="{{ asset('asset/admin/build/css/custom.min.css') }}" rel="stylesheet">
</head>

<body class="login">
    <div>
        <a class="hiddenanchor" id="signin"></a>

        <div class="login_wrapper">
            <div class="animate form login_form">
                <section class="login_content">
                    <form action="{{ route('admin.login.submit') }}" method="POST">
                        @csrf
                        <h1>Đăng Nhập Quản Trị</h1>

                        <div>
                            <input type="email" class="form-control" name="email" value="{{ old('email') }}" placeholder="Email" required>
                        </div>
                        <div>
                            <input type="password" class="form-control" name="password" placeholder="Mật khẩu" required>
                        </div>
                        <div>
                            <button type="submit" class="btn btn-default submit">Đăng nhập</button>
                        </div>

                        <div class="clearfix"></div>

                        <div class="separator">
                            <div class="clearfix"></div>
                            <br>
                            <div>
                                <h1><i class="fa fa-leaf"></i> Veggie Admin</h1>
                                <p>©{{ date('Y') }} All Rights Reserved.</p>
                            </div>
                        </div>
                    </form>
                </section>
            </div>
        </div>
    </div>

    <!-- Toastr CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <!-- Toastr JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    @include('flash::message')
</body>

</html>