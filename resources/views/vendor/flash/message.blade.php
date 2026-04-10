@foreach (session('flash_notification', []) as $message)
    @php
        $level = $message['level'];
        $text = $message['message'];
        $title = $message['title'] ?? '';
    @endphp
    <script>
        document.addEventListener('DOMContentLoaded', function() {
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

            const level = <?php echo json_encode($level); ?>;
            const message = <?php echo json_encode(strip_tags($text)); ?>;
            const title = <?php echo json_encode($title); ?>;

            if (level === 'success') {
                toastr.success(message, title);
            } else if (level === 'error' || level === 'danger') {
                toastr.error(message, title);
            } else if (level === 'warning') {
                toastr.warning(message, title);
            } else if (level === 'info') {
                toastr.info(message, title);
            }
        });
    </script>
@endforeach

@if ($errors->any())
    @php
        $validationErrors = $errors->all();
    @endphp
    <script>
        document.addEventListener('DOMContentLoaded', function() {
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

            const validationErrors = <?php echo json_encode($validationErrors); ?>;
            validationErrors.forEach(function (error) {
                toastr.error(error);
            });
        });
    </script>
@endif

{{ session()->forget('flash_notification') }}
