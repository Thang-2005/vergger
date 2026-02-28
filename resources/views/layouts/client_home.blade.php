<!doctype html>
<html class="no-js" lang="zxx">
<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>@yield('title')</title>
    <meta name="robots" content="noindex, follow" />
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    

    <!-- Place favicon.png in the root directory -->
    <link rel="shortcut icon" href="{{asset('asset/client/img/favicon.png')}}" type="image/x-icon" />
    <!-- Font Icons css -->
    <link rel="stylesheet" href="{{asset('asset/client/css/font-icons.css')}}">
    <!-- plugins css -->
    <link rel="stylesheet" href="{{asset('asset/client/css/plugins.css')}}">
    <!-- Main Stylesheet -->
    <link rel="stylesheet" href="{{asset('asset/client/css/style.css')}}">
    <!-- Responsive css -->
    <link rel="stylesheet" href="{{asset('asset/client/css/responsive.css')}}">
    <!-- customer css -->
    <link rel="stylesheet" href="{{asset('asset/client/css/customer.css')}}">

</head>

<body>
   
    <div class="body-wrapper">
    @include('clients.partials.header')

    <main>
        @yield('content')
    </main>

    @include('clients.partials.footer')

        
    </div>
   
    <div class="preloader d-none" id="preloader">
        <div class="preloader-inner">
            <div class="spinner">
                <div class="dot1"></div>
                <div class="dot2"></div>
            </div>
        </div>
    </div>
    
    <!-- 1. jQuery PHẢI load trước tất cả -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

<!-- 2. Plugins (Slick, ...) cần jQuery -->
<script src="{{asset('asset/client/js/plugins.js')}}"></script>

<!-- 3. Main & Custom JS của theme -->
<script src="{{asset('asset/client/js/main.js')}}"></script>
<script src="{{asset('asset/client/js/custom.js')}}"></script>

<!-- 4. Bootstrap Bundle -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<!-- 5. Toastr -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

<!-- 6. SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

@stack('scripts')
  

</body>

</html>