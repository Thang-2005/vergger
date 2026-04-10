<?php

namespace App\Http\Controllers\Client;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Order;
use App\Models\ShippingAddress;

use Illuminate\Http\Request;

class AccountController extends Controller
{
    public function show_account(Request $request)
    {


        if (!Auth::check()) {
            flash('Vui lòng đăng nhập', 'error');
            return redirect()->route('login.customer');
        }
        /** @var User $user */
        $user = Auth::user();
        $orders = $user->orders()->with('orderItems.product')->latest()->get();
        $address = ShippingAddress::where('user_id', Auth::id())->get();
        $redirectUrl = $request->query('redirect_url');

        return view('clients.pages.account', compact('user', 'address', 'redirectUrl', 'orders'));
    }

    public function update_profile(Request $request)
    {
        // Xử lý cập nhật thông tin cá nhân
        $request->validate([
            'name' => 'required|string|max:255',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
        ]);
        /** @var User $user */
        $user = Auth::user();
        $user->name = $request->input('name');
        $user->phone_number = $request->input('phone');
        $user->address = $request->input('address');

        if ($request->hasFile('avatar')) {
            // Xóa ảnh cũ nếu có
            if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
                Storage::disk('public')->delete($user->avatar);
            }
            // Lưu ảnh mới
            $file = $request->file('avatar');
            $fileName = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            // Lưu ảnh vào thư mục 'uploads/avatars' trong storage/app/public
            $avatarPath = $file->storeAs('uploads/avatars', $fileName, 'public');
            $user->avatar = $avatarPath;
        }


        $user->save();
        return response()->json([
            'message' => 'Cập nhật thông tin cá nhân thành công',
            'success' => true,
            'avatar' => $user->avatar ? asset('storage/' . $user->avatar) : asset('asset/client/img/avatar-placeholder.png')
        ]);
    }

    public function change_password(Request $request)
    {
        // Xử lý thay đổi mật khẩu
        $validator = Validator::make($request->all(), [
            'current_password' => 'required',
            'new_password' => 'required|min:6|confirmed',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 422);
        }

        $user = Auth::user();

        if (!Hash::check($request->current_password, $user->password)) {
            return response()->json([
                'errors' => [
                    'current_password' => ['Mật khẩu hiện tại không đúng']
                ]
            ], 422);
        }
        /** @var User $user */
        $user->password = Hash::make($request->new_password);
        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'Đổi mật khẩu thành công'
        ]);
    }


    public function add_address(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'full_name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'address' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'is_default' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user = Auth::user();
        $is_default = $request->input('is_default', false);

        // Nếu địa chỉ mới được đặt làm mặc định, hãy bỏ mặc định tất cả các địa chỉ khác
        if ($is_default) {
            ShippingAddress::where('user_id', $user->id)->update(['default' => false]);
        }

        $address = ShippingAddress::create([
            'user_id' => $user->id,
            'full_name' => $request->full_name,
            'phone' => $request->phone,
            'address' => $request->address,
            'city' => $request->city,
            'default' => $is_default,
        ]);

        // Kiểm tra xem có redirect_url không
        if ($request->has('redirect_url')) {
            return redirect($request->input('redirect_url'))->with('success', 'Thêm địa chỉ thành công!');
        }

        return redirect()->back()->with('message', 'Thêm địa chỉ thành công!');
    }

    public function delete_address($id)
    {
        // Xử lý xóa địa chỉ
        $address = ShippingAddress::findOrFail($id);

        if ($address->user_id != Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'Bạn không có quyền xóa địa chỉ này'
            ], 403);
        }

        $address->delete();

        return response()->json([
            'success' => true,
            'message' => 'Xóa địa chỉ thành công'
        ]);
    }

    public function set_default_address($id)
    {
        $address = ShippingAddress::findOrFail($id);

        if ($address->user_id != Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'Bạn không có quyền thiết lập địa chỉ này làm mặc định'
            ], 403);
        }

        // Cập nhật tất cả địa chỉ của người dùng thành không mặc định
        ShippingAddress::where('user_id', Auth::id())->update(['default' => 0]);

        // Cập nhật địa chỉ được chọn thành mặc định
        $address->update(['default' => 1]);

        return response()->json([
            'success' => true,
            'message' => 'Đã thiết lập địa chỉ mặc định thành công'
        ]);
    }


    public function show_orders()
    {
        if (!Auth::check()) {
            return redirect()->route('login.customer')->with('error', 'Vui lòng đăng nhập');
        }

        /** @var User $user */
        $user = Auth::user();
        $orders = $user->orders()->with('orderItems.product')->orderBy('created_at', 'desc')->get();
        $address = ShippingAddress::where('user_id', Auth::id())->get();
        $redirectUrl = request()->query('redirect_url');

        return view('clients.pages.account', compact('user', 'address', 'redirectUrl', 'orders'));
    }

    
    
}
