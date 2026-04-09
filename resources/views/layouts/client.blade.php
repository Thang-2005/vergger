<!doctype html>
<html class="no-js" lang="zxx">
<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>@yield('title')</title>
    <meta name="robots" content="noindex, follow" />
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    {{-- CSRF token cho AJAX --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="shortcut icon" href="{{ asset('asset/client/img/favicon.png') }}" type="image/x-icon" />

    {{-- CSS --}}
    <link rel="stylesheet" href="{{ asset('asset/client/css/font-icons.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('asset/client/css/plugins.css') }}">
    <link rel="stylesheet" href="{{ asset('asset/client/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('asset/client/css/responsive.css') }}">
    <link rel="stylesheet" href="{{ asset('asset/client/css/customer.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">

    @yield('meta')
</head>

<body>
    <div class="wrapper">
        @include('clients.partials.header')
        @include('clients.partials.breadcrumb')

        <main>
            @yield('content')
        </main>

        @include('clients.partials.fearture')
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

    {{-- ===== JAVASCRIPT - ĐÚNG THỨ TỰ ===== --}}

    {{-- 1. jQuery load ĐẦU TIÊN --}}
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>

    {{-- 2. Bootstrap --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    {{-- 3. Toastr + SweetAlert --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    {{-- 4. Flash Messages --}}
    @include('flash::message')

    {{-- 4. Plugins (cần jQuery) --}}
    <script src="{{ asset('asset/client/js/plugins.js') }}"></script>

    {{-- 5. Main + Custom --}}
    <script src="{{ asset('asset/client/js/main.js') }}"></script>
    <script src="{{ asset('asset/client/js/custom.js') }}"></script>

    {{-- 6. Global config - CSRF + Toastr --}}
    <script>
        $(document).ready(function () {

            // ✅ CSRF cho tất cả AJAX request
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            // Config Toastr
            toastr.options = {
                "closeButton": true,
                "progressBar": true,
                "positionClass": "toast-top-right",
                "timeOut": "4000",
                "extendedTimeOut": "1000",
                "showEasing": "swing",
                "hideEasing": "linear",
                "showMethod": "fadeIn",
                "hideMethod": "fadeOut"
            };
        });
    </script>

    {{-- 7. Page-specific scripts --}}
    @stack('scripts')
    @yield('scripts')

    
</body>
</html>