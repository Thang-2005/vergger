<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (Auth::check() && $this->hasAdminAccess(Auth::user())) {
            return redirect()->route('admin.dashboard');
        }

        return view('admin.pages.login_admin');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'min:6'],
        ], [
            'email.required' => 'Vui lòng nhập email.',
            'password.required' => 'Vui lòng nhập mật khẩu.',
        ]);

        if (!Auth::guard('web')->validate($credentials)) {
            return back()->withInput($request->only('email'))
                ->with('error', 'Email hoặc mật khẩu không đúng.');
        }

        $user = User::where('email', $credentials['email'])->first();

        if (!$user) {
            return back()->withInput($request->only('email'))
                ->with('error', 'Tài khoản không tồn tại.');
        }

        if ($user->status !== 'active') {
            return back()->withInput($request->only('email'))
                ->with('error', 'Tài khoản chưa được kích hoạt hoặc đã bị khóa.');
        }

        if (!$this->hasAdminAccess($user)) {
            return back()->withInput($request->only('email'))
                ->with('error', 'Tài khoản không có quyền quản trị.');
        }

        Auth::guard('web')->login($user);
        $request->session()->regenerate();

        return redirect()->intended(route('admin.dashboard'));
    }

    public function logout(Request $request)
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login')->with('success', 'Đăng xuất admin thành công.');
    }

    private function hasAdminAccess(User $user): bool
    {
        $roleName = strtolower(trim((string) optional($user->role)->name));
        $allowedRoleIds = [1, 2]; // 1: Admin, 2: Staff
        $allowedRoleNames = ['admin', 'staff', 'manager'];

        return in_array((int) $user->role_id, $allowedRoleIds, true)
            || in_array($roleName, $allowedRoleNames, true)
            || str_contains($roleName, 'staff')
            || str_contains($roleName, 'manager');
    }
}
