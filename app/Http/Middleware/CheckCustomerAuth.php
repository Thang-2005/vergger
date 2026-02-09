<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckCustomerAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Kiểm tra user đã đăng nhập chưa
        if (!Auth::check()) {
            // Lưu URL hiện tại để redirect về sau khi login
            session(['url.intended' => url()->current()]);
            
            return redirect()->route('login.customer')
                ->with('error', 'Vui lòng đăng nhập để tiếp tục');
        }

        // Kiểm tra role_id = 3 (customer)
        if (Auth::user()->role_id !== 3) {
            Auth::logout();
            return redirect()->route('login.customer')
                ->with('error', 'Bạn không có quyền truy cập');
        }

        // Kiểm tra status = active
        if (Auth::user()->status !== 'active') {
            Auth::logout();
            return redirect()->route('login.customer')
                ->with('error', 'Tài khoản chưa được kích hoạt');
        }

        // Cho phép tiếp tục request
        return $next($request);
    }
}