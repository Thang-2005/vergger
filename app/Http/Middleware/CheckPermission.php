<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckPermission
{
    public function handle(Request $request, Closure $next, string $permission): Response
    {
        /** @var User|null $adminUser */
        $adminUser = Auth::guard('admin')->user();

        if (!$adminUser) {
            flash('Vui long dang nhap de truy cap.', 'error');
            return redirect()->route('admin.login');
        }

        $isAdmin = optional($adminUser->roles)->contains('name', 'Admin');

        if (!$isAdmin && !$adminUser->hasPermission($permission)) {
            flash('Ban khong co quyen thuc hien chuc nang nay.', 'error');
            return redirect()->route('admin.dashboard');
        }

        return $next($request);
    }
}
