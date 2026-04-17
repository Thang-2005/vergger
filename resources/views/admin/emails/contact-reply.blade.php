<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Phản hồi liên hệ</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f5f5f5;
        }

        .email-container {
            max-width: 600px;
            margin: 20px auto;
            background: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 40px 20px;
            text-align: center;
        }

        .header h1 {
            font-size: 28px;
            margin-bottom: 10px;
        }

        .header p {
            font-size: 14px;
            opacity: 0.9;
        }

        .content {
            padding: 40px 30px;
        }

        .greeting {
            margin-bottom: 20px;
            font-size: 16px;
            color: #333;
        }

        .greeting strong {
            color: #667eea;
        }

        .message {
            margin: 30px 0;
            padding: 20px;
            background-color: #f9f9f9;
            border-left: 4px solid #667eea;
            border-radius: 4px;
            line-height: 1.8;
        }

        .message p {
            margin-bottom: 12px;
        }

        .message p:last-child {
            margin-bottom: 0;
        }

        .thank-you {
            margin: 30px 0;
            padding: 20px;
            background-color: #f0f7ff;
            border-radius: 4px;
            border: 1px solid #b3d9ff;
            text-align: center;
        }

        .thank-you h3 {
            color: #667eea;
            margin-bottom: 10px;
            font-size: 18px;
        }

        .thank-you p {
            color: #555;
            font-size: 14px;
        }

        .footer {
            background-color: #f5f5f5;
            padding: 30px;
            border-top: 1px solid #ddd;
            text-align: center;
            font-size: 12px;
            color: #666;
        }

        .company-info {
            margin: 20px 0;
            padding: 15px;
            background: white;
            border-radius: 4px;
            border: 1px solid #eee;
        }

        .company-info h4 {
            color: #667eea;
            margin-bottom: 10px;
            font-size: 14px;
        }

        .company-info p {
            margin: 5px 0;
            font-size: 13px;
            color: #555;
        }

        .company-info a {
            color: #667eea;
            text-decoration: none;
        }

        .company-info a:hover {
            text-decoration: underline;
        }

        .divider {
            height: 1px;
            background-color: #ddd;
            margin: 20px 0;
        }

        .cta-button {
            display: inline-block;
            margin-top: 20px;
            padding: 12px 30px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            text-decoration: none;
            border-radius: 4px;
            font-size: 14px;
            font-weight: bold;
        }

        .cta-button:hover {
            opacity: 0.9;
            text-decoration: none;
            color: white;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <!-- Header -->
        <div class="header">
            <h1>🌿 {{ $companyName }}</h1>
            <p>Phản hồi cho yêu cầu của bạn</p>
        </div>

        <!-- Content -->
        <div class="content">
            <!-- Greeting -->
            <div class="greeting">
                Xin chào <strong>{{ $customerName }}</strong>,
            </div>
            <p>Cảm ơn bạn đã liên hệ với chúng tôi! Chúng tôi đã nhận được tin nhắn của bạn là:</p>
           
            <p><em>"{{ $contactData->message ?? 'Không có nội dung tin nhắn.' }}"</em></p>

            <h3>📬 Phản hồi của chúng tôi:</h3>

            <!-- Reply Message -->
            <div class="message">
                {!! nl2br(e($replyContent)) !!}
            </div>

            <!-- Thank You Section -->
            <div class="thank-you">
                <h3>🙏 Cảm ơn bạn đã tin tưởng chúng tôi</h3>
                <p>Chúng tôi luôn sẵn sàng hỗ trợ bạn bất kỳ lúc nào. Nếu bạn có thêm câu hỏi, vui lòng liên hệ với chúng tôi!</p>
            </div>

            <div class="divider"></div>

            <!-- Company Info -->
            <div class="company-info">
                <h4>📞 Thông tin liên hệ</h4>
                <p><strong>{{ $companyName }}</strong></p>
                <p>📧 {{ __('messages.email') }}: <a href="mailto:{{ $companyEmail }}">{{ $companyEmail }}</a></p>
                <p>☎️ Điện thoại: {{ $companyPhone }}</p>
                <p>🌐 Website: <a href="{{ url('/') }}">{{ url('/') }}</a></p>
            </div>

            <!-- Closing -->
            <p style="margin-top: 30px; text-align: center; color: #666; font-size: 14px;">
                Trân trọng,<br>
                <strong>{{ $companyName }} Team</strong>
            </p>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p>© {{ date('Y') }} {{ $companyName }}. Tất cả quyền được bảo lưu.</p>
            <p style="margin-top: 10px; color: #999;">Đây là email tự động, vui lòng không trả lời trực tiếp.</p>
        </div>
    </div>
</body>
</html>
