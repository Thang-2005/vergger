<p>Xin chào {{ $user->name }},</p>

<p>Cảm ơn bạn đã đăng ký. Vui lòng bấm vào link sau để kích hoạt tài khoản:</p>

<p><a href="{{ route('activate', ['token' => $token]) }}">Kích hoạt tài khoản</a></p>

<p>Nếu link không hoạt động, sao chép URL dưới đây vào trình duyệt:</p>
<p>{{ route('activate', ['token' => $token]) }}</p>

<p>Trân trọng,<br>Đội ngũ Veggie</p>