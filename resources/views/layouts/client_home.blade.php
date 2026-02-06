<!doctype html>
<html class="no-js" lang="zxx">
<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>@yield('title')</title>
    <meta name="robots" content="noindex, follow" />
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

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
</head>

<body>
   
    <div class="body-wrapper">
    @include('clients.partials.header_home')

    <main>
        @yield('content')
    </main>

    @include('clients.partials.footer_home')

        
    </div>
   
    <div class="preloader d-none" id="preloader">
        <div class="preloader-inner">
            <div class="spinner">
                <div class="dot1"></div>
                <div class="dot2"></div>
            </div>
        </div>
    </div>
    
    <script src="{{asset('asset/client/js/plugins.js')}}"></script>
    <!-- Main JS -->
    <script src="{{asset('asset/client/js/main.js')}}"></script>

</body>

</html>