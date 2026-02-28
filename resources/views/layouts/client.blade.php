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
    <!-- FontAwesome CDN -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- plugins css -->
    <link rel="stylesheet" href="{{asset('asset/client/css/plugins.css')}}">
    <!-- Main Stylesheet -->
    <link rel="stylesheet" href="{{asset('asset/client/css/style.css')}}">
    <!-- Responsive css -->
    <link rel="stylesheet" href="{{asset('asset/client/css/responsive.css')}}">
    <link rel="stylesheet" href="{{asset('asset/client/css/customer.css')}}">

    <!-- Toastr -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <!-- ui -->
    <link rel="stylesheet"href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">

</head>

<body>
   
    <div class="wrapper">
    @include('clients.partials.header')
    <!-- @hasSession('breadcrumb') -->
        @include('clients.partials.breadcrumb')
    <!-- @endhasSession -->


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
    
    <script src="{{asset('asset/client/js/plugins.js')}}"></script>
    <!-- Main JS -->
    <script src="{{asset('asset/client/js/main.js')}}"></script>
    <script src="{{asset('asset/client/js/custom.js')}}"></script>

    <!-- jQuery -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

    <!-- Toastr -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <!-- Convert Bootstrap Alerts to Toastr -->
    <script>
        $(document).ready(function() {
            // Config toastr
            toastr.options = {
                "closeButton": true,
                "progressBar": true,
                "positionClass": "toast-top-right",
                "timeOut": "4000"
            };

            // Convert alert-success to toastr
            $('.alert-success').each(function() {
                const message = $(this).text().trim();
                if (message) {
                    toastr.success(message);
                    $(this).remove();
                }
            });

            // Convert alert-danger to toastr
            $('.alert-danger').each(function() {
                const content = $(this).html().trim();
                if (content) {
                    toastr.error(content);
                    $(this).remove();
                }
            });

            // Convert alert-warning to toastr
            $('.alert-warning').each(function() {
                const message = $(this).text().trim();
                if (message) {
                    toastr.warning(message);
                    $(this).remove();
                }
            });

            // Convert alert-info to toastr
            $('.alert-info').each(function() {
                const message = $(this).text().trim();
                if (message) {
                    toastr.info(message);
                    $(this).remove();
                }
            });
        });
    </script>
    
    <script src="{{asset('asset/client/js/custom.js')}}"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <!-- ui -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>

</body>

</html>