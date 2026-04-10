<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckAdminAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Vui lòng đăng nhập để truy cập trang quản trị.',
                ], 401);
            }

            return redirect()->route('admin.login')
                ->with('error', 'Vui lòng đăng nhập để truy cập trang quản trị.');
        }

        $user = Auth::user();
        $roleName = strtolower(trim((string) optional($user->role)->name));

        $allowedRoleIds = [1, 2]; // 1: Admin, 2: Staff
        $allowedRoleNames = ['admin', 'staff', 'manager'];

        $hasAllowedId = in_array((int) $user->role_id, $allowedRoleIds, true);
        $hasAllowedName = in_array($roleName, $allowedRoleNames, true)
            || str_contains($roleName, 'staff')
            || str_contains($roleName, 'manager');

        if (!($hasAllowedId || $hasAllowedName)) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Bạn không có quyền truy cập khu vực quản trị (Admin/Staff).',
                ], 403);
            }

            return redirect()->route('home')
                ->with('error', 'Bạn không có quyền truy cập khu vực quản trị (Admin/Staff).');
        }

        return $next($request);
    }
}
