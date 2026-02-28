<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .button { display: inline-block; padding: 12px 30px; background-color: #28a745; color: white; text-decoration: none; border-radius: 4px; }
        .footer { margin-top: 30px; padding-top: 20px; border-top: 1px solid #eee; font-size: 12px; color: #666; }
    </style>
</head>
<body>
    <div class="container">
        <h2>Đặt lại mật khẩu</h2>

        <p>Bạn nhận được email này vì chúng tôi nhận được yêu cầu đặt lại mật khẩu cho tài khoản của bạn.</p>

        <p style="margin: 30px 0;">
            <a href="{{ url(route('password.reset', ['token' => $token], false) . '?email=' . urlencode($email)) }}" class="button">
                Đặt lại mật khẩu
            </a>
        </p>

        <p>Đường dẫn đặt lại mật khẩu này sẽ hết hạn sau 60 phút.</p>

        <p>Nếu bạn không yêu cầu đặt lại mật khẩu, hãy bỏ qua email này.</p>

        <div class="footer">
            <p>Cảm ơn,<br>
            {{ config('app.name') }}</p>
        </div>
    </div>
</body>
</html>
