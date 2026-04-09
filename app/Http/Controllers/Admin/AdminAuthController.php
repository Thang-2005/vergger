<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminAuthController extends Controller
{
    public function showLoginForm()
    {
        return view('admin.pages.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string|min:6',
        ], [
            'email.required' => 'Vui lòng nhập email.',
            'email.email' => 'Vui lòng nhập đúng định dạng email.',
            'password.required' => 'Vui lòng nhập mật khẩu.',
            'password.min' => 'Mật khẩu phải có ít nhất 6 ký tự.',
        ]);

        $credentials = $request->only('email', 'password');

        if (auth()->guard('admin')->attempt($credentials)) {
            $request->session()->regenerate();

            $user = Auth::guard('admin')->user();

            if ($user && $user->role && in_array($user->role->name, ['Admin', 'Staff'], true)) {
                flash('Đăng nhập admin thành công', 'success');
                return redirect()->route('admin.dashboard');
            }

            Auth::guard('admin')->logout();

            flash('Bạn không có quyền truy cập admin.', 'error');
            return back()->withInput($request->only('email'));
        }

        flash('Email hoặc mật khẩu không đúng.', 'error');
        return back()->withInput($request->only('email'));
    }

    public function logout(Request $request)
    {
        Auth::guard('admin')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();
        flash('Đăng xuất thành công.', 'success');
        return redirect()->route('admin.login');
    }
}
