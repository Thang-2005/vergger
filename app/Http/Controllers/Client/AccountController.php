<?php

namespace App\Http\Controllers\Client;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\ShippingAddress;

use Illuminate\Http\Request;

class AccountController extends Controller
{
    public function show_account(){
        
    
        if (!Auth::check()) {
            return redirect()->route('login.customer')->with('error', 'Vui lòng đăng nhập');
        }
        $user = Auth::user();
        $address = ShippingAddress::where('user_id', Auth::id())->get();
    
        return view('clients.pages.account', compact('user', 'address'));
}

    public function update_profile(Request $request)
    {
        // Xử lý cập nhật thông tin cá nhân
        $request ->validate([
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

        if($request->hasFile('avatar')){
            // Xóa ảnh cũ nếu có
            if($user->avatar && Storage::disk('public')->exists($user->avatar)){
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
            'avatar'=> $user->avatar ? asset('storage/' . $user->avatar) : asset('asset/client/img/avatar-placeholder.png')
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
        // Xử lý thêm địa chỉ mới
        $request->validate([
            'full_name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'address' => 'required|string|max:255',
            'city' => 'required|string|max:255',
        ]);

        $user = Auth::user();

        // Nếu có đánh dấu là địa chỉ mặc định, cập nhật các địa chỉ khác thành không mặc định
        if ($request->has('default')) {
           ShippingAddress::where('user_id', Auth::id())->update(['default' => 0]);
        }

        // Thêm địa chỉ mới
        ShippingAddress::create([
            'full_name' => $request->input('full_name'),
            'phone' => $request->input('phone'),
            'address' => $request->input('address'),
            'city' => $request->input('city'),
            'default' => $request->has('default') ? 1 : 0,
            'user_id' => Auth::id()
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Thêm địa chỉ mới thành công'
        ]);
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
}
