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
            'role_id' => 3, // đảm bảo tồn tạiP
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
            'password' => 'required|min:6',
        ]);

        // 1. Check email + password (KHÔNG tạo session)
        if (!Auth::guard('web')->validate($request->only('email', 'password'))) {
            return response()->json([
                'message' => 'Email hoặc mật khẩu không đúng'
            ], 401);
        }

        // 2. Lấy user
        $user = User::where('email', $request->email)->first();

        // 3. Check active
        if ($user->status !== 'active') {
            return response()->json([
                'message' => 'Tài khoản chưa được kích hoạt'
            ], 403);
        }

        // 4. Check role
        if ($user->role_id !== 3) {
            return response()->json([
                'message' => 'Tài khoản không có quyền truy cập'
            ], 403);
        }

        // 5. LOGIN THẬT
        Auth::guard('web')->login($user);

        // 6. Regenerate session + CSRF
        $request->session()->regenerate();

        return response()->json([
            'message' => 'Đăng nhập thành công',
            'redirect' => route('home'),
        ]);
    }

    // public function login_customer(Request $request)
    // {



    //     $request->validate([
    //         'email' => 'required|email',
    //         'password' => 'required|min:6'
    //     ], [
    //         'email.required' => 'Vui lòng nhập email',
    //         'email.email' => 'Email không hợp lệ',
    //         'password.required' => 'Vui lòng nhập mật khẩu',
    //     ]);

    //     // ❌ Sai email / mật khẩu (KHÔNG tạo session)
    //     if (!Auth::validate([
    //         'email' => $request->email,
    //         'password' => $request->password
    //     ])) {
    //         return response()->json([
    //             'status' => 'error',
    //             'message' => 'Email hoặc mật khẩu không đúng'
    //         ], 401);
    //     }
    

    //     // Lấy user ra để kiểm tra
    //     $user = \App\Models\User::where('email', $request->email)->first();

    //     // ❌ Chưa kích hoạt
    //     if ($user->status !== 'active') {
    //         return response()->json([
    //             'status' => 'warning',
    //             'message' => 'Tài khoản chưa được kích hoạt. Vui lòng kiểm tra email.'
    //         ], 403);
    //     }

    //     // ❌ Sai role
    //     if ($user->role->name !== 3) {
    //         return response()->json([
    //             'status' => 'error',
    //             'message' => 'Tài khoản không có quyền truy cập'
    //         ], 403);
    //     }

    //     // ✅ ĐỦ ĐIỀU KIỆN → login thật sự
    //     Auth::login($user);
        

    //     // 🔐 QUAN TRỌNG: regenerate session + csrf
    //     $request->session()->regenerate();

    //     return response()->json([
    //         'status' => 'success',
    //         'message' => 'Đăng nhập thành công',
    //         'redirect' => route('home')
    //     ]);
        
    // }

    public function logout_customer(Request $request){
    Auth::logout();

    $request->session()->invalidate();
    $request->session()->regenerateToken();

    return redirect()->route('login.customer')->with('success', 'Đăng xuất thành công');
}

  public function show_account(){
    if (!Auth::check()) {
        return redirect()->route('login.customer')->with('error', 'Vui lòng đăng nhập');
    }
    
    $user = Auth::user();
    return view('clients.pages.account', compact('user'));
}

}
