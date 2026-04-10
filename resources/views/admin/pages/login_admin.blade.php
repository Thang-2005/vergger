<!DOCTYPE html>
<html lang="vi">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="csrf-token" content="{{ csrf_token() }}">
	<title>Đăng nhập quản trị</title>

	<link rel="stylesheet" href="{{ asset('backend/css/bootstrap.min.css') }}">
	<link rel="stylesheet" href="{{ asset('backend/css/font-awesome.css') }}">
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&display=swap" rel="stylesheet">

	<style>
		:root {
			--admin-olive: #3b6e3f;
			--admin-deep: #163524;
			--admin-accent: #f4a825;
			--admin-bg: #eff6ea;
			--admin-surface: #ffffff;
		}

		* {
			font-family: 'Manrope', sans-serif;
		}

		body {
			min-height: 100vh;
			margin: 0;
			background:
				radial-gradient(circle at 8% 12%, rgba(68, 132, 76, 0.2), transparent 34%),
				radial-gradient(circle at 90% 22%, rgba(255, 181, 44, 0.22), transparent 38%),
				linear-gradient(140deg, #f8fcf4 0%, #edf6eb 45%, #eef4ff 100%);
			display: flex;
			align-items: center;
			justify-content: center;
			padding: 28px;
			color: #1f2f23;
		}

		.admin-auth {
			width: min(980px, 100%);
			display: grid;
			grid-template-columns: 1.1fr 0.9fr;
			border-radius: 24px;
			overflow: hidden;
			box-shadow: 0 24px 56px rgba(20, 43, 27, 0.2);
			background: var(--admin-surface);
		}

		.admin-auth-hero {
			background: linear-gradient(155deg, #163524 0%, #29543c 45%, #3f724f 100%);
			color: #eefded;
			padding: 44px;
			display: flex;
			flex-direction: column;
			justify-content: space-between;
		}

		.admin-auth-hero h1 {
			font-size: 34px;
			font-weight: 800;
			line-height: 1.25;
			margin: 0 0 14px;
		}

		.admin-auth-hero p {
			opacity: 0.9;
			margin-bottom: 0;
		}

		.admin-auth-stats {
			display: grid;
			grid-template-columns: repeat(3, 1fr);
			gap: 10px;
			margin-top: 22px;
		}

		.admin-auth-stats .item {
			background: rgba(255, 255, 255, 0.14);
			border: 1px solid rgba(255, 255, 255, 0.18);
			border-radius: 12px;
			padding: 12px;
			text-align: center;
		}

		.admin-auth-stats strong {
			display: block;
			font-size: 18px;
			font-weight: 800;
		}

		.admin-auth-form {
			padding: 44px 36px;
			background: var(--admin-surface);
		}

		.admin-brand {
			display: inline-flex;
			align-items: center;
			gap: 8px;
			margin-bottom: 20px;
			font-weight: 700;
			color: var(--admin-deep);
		}

		.admin-brand i {
			color: var(--admin-accent);
		}

		.admin-auth-form h2 {
			margin: 0;
			font-size: 28px;
			color: #1d2f22;
			font-weight: 800;
		}

		.admin-auth-form .subtitle {
			margin: 10px 0 24px;
			color: #5f6d61;
		}

		.field {
			margin-bottom: 16px;
		}

		.field label {
			display: block;
			margin-bottom: 8px;
			font-weight: 600;
			color: #35533d;
		}

		.field input {
			width: 100%;
			height: 48px;
			border: 1px solid #d6e4d8;
			border-radius: 12px;
			padding: 0 14px;
			outline: none;
			transition: 0.2s ease;
		}

		.field input:focus {
			border-color: #6aa95f;
			box-shadow: 0 0 0 4px rgba(106, 169, 95, 0.16);
		}

		.btn-login {
			width: 100%;
			border: none;
			border-radius: 12px;
			height: 50px;
			background: linear-gradient(135deg, #376b3d 0%, #4b8a57 100%);
			color: #fff;
			font-weight: 700;
			letter-spacing: 0.4px;
			margin-top: 8px;
		}

		.btn-login:hover {
			opacity: 0.92;
		}

		.auth-note {
			margin-top: 16px;
			font-size: 13px;
			color: #6d7d71;
			text-align: center;
		}

		@media (max-width: 991.98px) {
			.admin-auth {
				grid-template-columns: 1fr;
			}

			.admin-auth-hero,
			.admin-auth-form {
				padding: 28px 22px;
			}
		}
	</style>
</head>
<body>
	<div class="admin-auth">
		<div class="admin-auth-hero">
			<div>
				<h1>Bảng điều khiển quản trị Veggie</h1>
				<p>Quản lý sản phẩm, đơn hàng và khách hàng trên một không gian làm việc tập trung.</p>
			</div>

			<div class="admin-auth-stats">
				<div class="item">
					<strong>24/7</strong>
					<small>Giám sát</small>
				</div>
				<div class="item">
					<strong>100%</strong>
					<small>Bảo mật</small>
				</div>
				<div class="item">
					<strong>1 chạm</strong>
					<small>Vận hành</small>
				</div>
			</div>
		</div>

		<div class="admin-auth-form">
			<div class="admin-brand">
				<i class="fa fa-leaf"></i>
				<span>Veggie Admin</span>
			</div>

			<h2>Đăng nhập quản trị</h2>
			<p class="subtitle">Sử dụng tài khoản Admin hoặc Staff để truy cập hệ thống.</p>

			@if (session('error'))
				<div class="alert alert-danger" role="alert">{{ session('error') }}</div>
			@endif

			@if (session('success'))
				<div class="alert alert-success" role="alert">{{ session('success') }}</div>
			@endif

			<form method="POST" action="{{ route('admin.login.submit') }}">
				@csrf
				<div class="field">
					<label for="email">Email</label>
					<input type="email" id="email" name="email" value="{{ old('email') }}" placeholder="admin@veggie.com" required>
				</div>

				<div class="field">
					<label for="password">Mật khẩu</label>
					<input type="password" id="password" name="password" placeholder="Nhập mật khẩu" required>
				</div>

				<button type="submit" class="btn-login">Đăng nhập</button>
			</form>

			<p class="auth-note">© {{ date('Y') }} Veggie. Admin workspace.</p>
		</div>
	</div>
</body>
</html>
