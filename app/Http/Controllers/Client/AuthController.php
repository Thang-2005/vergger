<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request; 
use DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator; 
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;


use App\Models\Register;
use App\Models\User;


class AuthController extends Controller
{
    public function show_register_form(){
        return view('clients.pages.register');
    }

public function register(Request $request)
{
    $validator = Validator::make($request->all(), [
        'full_name' => 'required|min:3',
        'email' => 'required|email|unique:users,email',
        'password' => 'required|min:6|confirmed',
    ], [
        'full_name.required' => 'Vui lòng nhập họ tên',
        'full_name.min' => 'Họ tên ít nhất 3 ký tự',
        'email.required' => 'Vui lòng nhập email',
        'email.email' => 'Email không hợp lệ',
        'email.unique' => 'Email đã tồn tại',
        'password.required' => 'Vui lòng nhập mật khẩu',
        'password.min' => 'Mật khẩu ít nhất 6 ký tự',
        'password.confirmed' => 'Mật khẩu xác nhận không khớp',
    ]);

    if ($validator->fails()) {
        return response()->json([
            'status' => 'error',
            'errors' => $validator->errors()
        ], 422);
    }

    $token = Str::random(64);

    User::create([
        'name' => $request->full_name,
        'email' => $request->email,
        'password' => Hash::make($request->password),
        'status' => 'pending',
        'role_id' => 1, // đảm bảo tồn tạiP
        'phone_number' => null,
        'activation_token' => $token,
    ]);

  
    return response()->json([
    'status' => 'success',
    'message' => 'Đăng ký thành công! Vui lòng chờ kích hoạt.',
    'redirect' => route('login.customer')
    ]);


    }

    public function show_login_customer(){
        return view('clients.pages.login');
    }

   

  

public function login_customer(Request $request)
{
    $request->validate([
        'email' => 'required|email',
        'password' => 'required|min:6'
    ], [
        'email.required' => 'Vui lòng nhập email',
        'email.email' => 'Email không hợp lệ',
        'password.required' => 'Vui lòng nhập mật khẩu',
    ]);

    // ❌ Sai email / mật khẩu (KHÔNG tạo session)
    if (!Auth::validate([
        'email' => $request->email,
        'password' => $request->password
    ])) {
        return response()->json([
            'status' => 'error',
            'message' => 'Email hoặc mật khẩu không đúng'
        ], 401);
    }

    // Lấy user ra để kiểm tra
    $user = \App\Models\User::where('email', $request->email)->first();

    // ❌ Chưa kích hoạt
    if ($user->status !== 'active') {
        return response()->json([
            'status' => 'warning',
            'message' => 'Tài khoản chưa được kích hoạt. Vui lòng kiểm tra email.'
        ], 403);
    }

    // ❌ Sai role
    if ($user->role->name !== 'customer') {
        return response()->json([
            'status' => 'error',
            'message' => 'Tài khoản không có quyền truy cập'
        ], 403);
    }

    // ✅ ĐỦ ĐIỀU KIỆN → login thật sự
    Auth::login($user);
    

    // 🔐 QUAN TRỌNG: regenerate session + csrf
    $request->session()->regenerate();

    return response()->json([
        'status' => 'success',
        'message' => 'Đăng nhập thành công',
        'redirect' => route('home')
    ]);
}



}
