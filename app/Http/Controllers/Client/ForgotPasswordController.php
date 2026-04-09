<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;
use App\Models\User;
use Illuminate\Http\Request;

class ForgotPasswordController extends Controller
{
    public function show_forgot_password()
    {
        return view('clients.auth.forgot_password');
    }

    public function send_reset_link(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ],
        [
            'email.exists' => 'Email không tồn tại trong hệ thống.',
            'email.required' => 'Vui lòng nhập email.',
            'email.email' => 'Vui lòng nhập đúng định dạng email.',
        ]);

        $status = Password::sendResetLink(
            $request->only('email')
        );
        if ($status === Password::RESET_LINK_SENT) {
            flash('Đường dẫn đặt lại mật khẩu đã được gửi đến email của bạn.', 'success');
            return back();
        } else {
            flash('Không thể gửi đường dẫn đặt lại mật khẩu. Vui lòng thử lại sau.', 'error');
            return back()
                ->withInput($request->only('email'));
        }
    }
}
