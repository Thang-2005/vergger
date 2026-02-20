@component('mail::message')
# Đặt lại mật khẩu

Bạn nhận được email này vì chúng tôi nhận được yêu cầu đặt lại mật khẩu cho tài khoản của bạn.

@component('mail::button', ['url' => url(route('password.reset', ['token' => $token], false) . '?email=' . urlencode($email))])
Đặt lại mật khẩu
@endcomponent

Đường dẫn đặt lại mật khẩu này sẽ hết hạn sau 60 phút.

Nếu bạn không yêu cầu đặt lại mật khẩu, hãy bỏ qua email này.

Cảm ơn,<br>
{{ config('app.name') }}
@endcomponent
