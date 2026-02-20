<?php

    namespace App\Http\Controllers\Client;
    use App\Http\Controllers\Controller;
    use Illuminate\Http\Request; 
    use Illuminate\Support\Facades\DB;
    use Illuminate\Support\Facades\Hash;
    use Illuminate\Support\Facades\Validator; 
    use Illuminate\Support\Str;
    use Illuminate\Support\Facades\Auth;
    use Illuminate\Support\Facades\Mail;
    use App\Mail\ActivationMail;

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
    ]);

    if ($validator->fails()) {
        return response()->json([
            'status' => 'error',
            'errors' => $validator->errors()
        ], 422);
    }

    DB::beginTransaction();

    try {

        $token = Str::random(64);

        $user = User::create([
            'name' => $request->full_name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'status' => 'pending',
            'role_id' => 3,
            'activation_token' => $token,
        ]);

        Mail::to($user->email)
            ->send(new ActivationMail($token, $user));

        DB::commit();

        return response()->json([
            'status' => 'success',
            'message' => 'Đăng ký thành công! Kiểm tra email để kích hoạt.',
            'redirect' => route('login.customer')
        ]);

    } catch (\Exception $e) {

        DB::rollBack();

        // \Log::error($e->getMessage());

        return response()->json([
            'status' => 'error',
            'message' => 'Có lỗi xảy ra, vui lòng thử lại'
        ], 500);
    }
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

    public function logout_customer(Request $request){
    Auth::logout();

    $request->session()->invalidate();
    $request->session()->regenerateToken();

    return response()->json([
        'message' => 'Đăng xuất thành công',
        'redirect' => route('login.customer'),
    ]);
}

  public function show_account(){
    if (!Auth::check()) {
        return redirect()->route('login.customer')->with('error', 'Vui lòng đăng nhập');
    }
    
    $user = Auth::user();
    return view('clients.pages.account', compact('user'));
}

    public function activate($token)
    {
        $user = User::where('activation_token', $token)->first();

        if (!$user) {
            return redirect()->route('login.customer')->with('error', 'Token kích hoạt không hợp lệ');
        }

        $user->status = 'active';
        $user->activation_token = null;
        $user->save();

        return redirect()->route('login.customer')->with('success', 'Kích hoạt tài khoản thành công! Bạn có thể đăng nhập ngay bây giờ.');
    }
}
