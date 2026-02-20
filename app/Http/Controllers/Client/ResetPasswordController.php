<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class ResetPasswordController extends Controller
{
    // hiển thị form reset
    public function show_reset_form(Request $request, $token)
    {
        return view('clients.auth.reset_password', [
            'token' => $token,
            'email' => $request->email
        ]);
    }

   public function reset_password(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:6|confirmed',
        ]);

        $status = Password::reset(
            $request->only(
                'email',
                'password',
                'password_confirmation',
                'token'
            ),
            function (User $user, $password) {
                $user->password = Hash::make($password);
                $user->save();
            }
        );

        if ($status === Password::PASSWORD_RESET) {
            return redirect()->route('login.customer')
                ->with('success', 'Đặt lại mật khẩu thành công! Vui lòng đăng nhập với mật khẩu mới.');
        } else {
            return back()
                ->withInput($request->only('email'))
                ->with('error', 'Đường dẫn đặt lại mật khẩu không hợp lệ hoặc đã hết hạn. Vui lòng thử lại.');
        }
    }
}