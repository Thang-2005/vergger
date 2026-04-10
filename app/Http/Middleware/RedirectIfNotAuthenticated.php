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
        // Nếu chưa đăng nhập admin thì chuyển về login và flash thông báo.
        if (!Auth::guard('admin')->check()) {
            flash('Vui lòng đăng nhập để truy cập trang quản trị.', 'error');

            return redirect()->route('admin.login');
        }

        // Nếu đã đăng nhập nhưng không đúng quyền admin/staff thì ép logout.
        $adminUser = Auth::guard('admin')->user();
        if (!$adminUser || !$adminUser->role || !in_array($adminUser->role->name, ['Admin', 'Staff'], true)) {
            Auth::guard('admin')->logout();
            flash('Bạn không có quyền truy cập.', 'error');

            return redirect()->route('admin.login');
        }

        return $next($request);
    }
}