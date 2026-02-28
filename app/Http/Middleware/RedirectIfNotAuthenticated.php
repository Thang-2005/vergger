<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfNotAuthenticated
{
    public function handle(Request $request, Closure $next): Response
    {
        // Nếu user chưa đăng nhập -> redirect về trang login với thông báo
        if (!Auth::check()) {
            return redirect()
                ->route('login')
                ->with('error', 'Vui lòng đăng nhập để truy cập trang này');
        }

        // Nếu đã đăng nhập nhưng không phải admin (nếu bạn dùng role_id để kiểm tra)
        // thay đổi điều kiện theo cấu trúc role của bạn (ví dụ role_id === 1 cho admin)
        if (isset(Auth::user()->role_id) && Auth::user()->role_id !== 3) {
            Auth::logout();
            return redirect()
                ->route('login')
                ->with('error', 'Bạn không có quyền truy cập');
        }

        return $next($request);
    }
}