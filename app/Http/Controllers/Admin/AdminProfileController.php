<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AdminProfileController extends Controller
{
    // Hiển thị trang hồ sơ cá nhân
    public function show()
    {
        $user = Auth::guard('admin')->user();
        return view('admin.pages.profile', compact('user'));
    }

    // Cập nhật hồ sơ cá nhân
    public function update(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone_number' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ], [
            'name.required' => 'Vui lòng nhập họ và tên.',
            'name.max' => 'Họ và tên không được vượt quá 255 ký tự.',
            'phone_number' => 'Số điện thoại không hợp lệ.',
            'address.max' => 'Địa chỉ không được vượt quá 500 ký tự.',
            'avatar.image' => 'File phải là hình ảnh.',
            'avatar.mimes' => 'Chỉ hỗ trợ định dạng: jpeg, png, jpg, gif.',
            'avatar.max' => 'Kích thước ảnh không được vượt quá 2MB.',
        ]);

        $user = Auth::guard('admin')->user();

        // Xử lý upload avatar
        if ($request->hasFile('avatar')) {
            // Xóa avatar cũ nếu tồn tại
            if ($user->avatar && file_exists(public_path($user->avatar))) {
                unlink(public_path($user->avatar));
            }

            // Upload avatar mới
            $file = $request->file('avatar');
            $filename = 'avatar_' . $user->id . '_' . time() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('admin/avatars', $filename, 'public');
            $validated['avatar'] = '/storage/' . $path;
        }

        $user->update($validated);
        flash('Cập nhật thông tin cá nhân thành công.', 'success');
        return redirect()->back();
    }

    // Đổi mật khẩu
    public function changePassword(Request $request)
    {
        $validated = $request->validate([
            'current_password' => 'required',
            'password' => 'required|string|min:8|confirmed',
        ], [
            'current_password.required' => 'Vui lòng nhập mật khẩu hiện tại.',
            'password.required' => 'Vui lòng nhập mật khẩu mới.',
            'password.min' => 'Mật khẩu phải có ít nhất 8 ký tự.',
            'password.confirmed' => 'Mật khẩu xác nhận không khớp.',
        ]);

        $user = Auth::guard('admin')->user();

        // Kiểm tra mật khẩu hiện tại
        if (!Hash::check($request->current_password, $user->password)) {
            flash('Mật khẩu hiện tại không đúng.', 'error');
            return redirect()->back();
        }

        // Cập nhật mật khẩu mới
        $user->update([
            'password' => Hash::make($validated['password'])
        ]);

        flash('Mật khẩu đã được thay đổi thành công.', 'success');
        return redirect()->back();
    }
}