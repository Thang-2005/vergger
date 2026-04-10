<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Role;
use App\Models\User;

class UserController extends Controller
{
    private function ensureAdminAccess(): ?\Illuminate\Http\JsonResponse
    {
       $adminUser = auth('admin')->user();

       if (!$adminUser || !$adminUser->role || $adminUser->role->name !== 'Admin') {
           return response()->json([
               'message' => 'Chỉ Admin mới có quyền thực hiện thao tác này.',
           ], 403);
       }

       return null;
    }

    private function ensureTargetCanBeManaged(User $targetUser): ?\Illuminate\Http\JsonResponse
    {
       $adminUser = auth('admin')->user();

       if ($adminUser && (int) $adminUser->id === (int) $targetUser->id) {
           return response()->json([
               'message' => 'Bạn không thể tự thay đổi vai trò/trạng thái của chính mình.',
           ], 422);
       }

       if (!$targetUser->role || $targetUser->role->name === 'Admin') {
           return response()->json([
               'message' => 'Không thể thao tác trên tài khoản Admin.',
           ], 422);
       }

       return null;
    }

    public function index()
    {
       $users = User::with('role')->paginate(9);

        return view('admin.pages.users', compact('users'));
    }

    public function upgradeRole(Request $request)
    {
       if ($error = $this->ensureAdminAccess()) {
           return $error;
       }

       $validated = $request->validate([
           'user_id' => ['required', 'integer', 'exists:users,id'],
       ]);

       $user = User::with('role')->findOrFail($validated['user_id']);

       if ($error = $this->ensureTargetCanBeManaged($user)) {
           return $error;
       }

       if (!$user->role || $user->role->name !== 'Customer') {
           return response()->json([
               'message' => 'Người dùng không phải là Khách hàng hoặc đã là Nhân viên.',
           ], 422);
       }

       $staffRole = Role::where('name', 'Staff')->first();

       if (!$staffRole) {
           return response()->json([
               'message' => 'Không tìm thấy vai trò Staff.',
           ], 422);
       }

       $user->role_id = $staffRole->id;
       $user->save();

       return response()->json([
           'message' => "Người dùng {$user->name} đã được lên nhân viên.",
           'redirect' => route('admin.users'),
       ]);
    }

    public function downgradeRole(Request $request)
    {
       if ($error = $this->ensureAdminAccess()) {
           return $error;
       }

       $validated = $request->validate([
           'user_id' => ['required', 'integer', 'exists:users,id'],
       ]);

       $user = User::with('role')->findOrFail($validated['user_id']);

       if ($error = $this->ensureTargetCanBeManaged($user)) {
           return $error;
       }

       if (!$user->role || $user->role->name !== 'Staff') {
           return response()->json([
               'message' => 'Chỉ có thể hạ quyền từ Staff xuống Customer.',
           ], 422);
       }

       $customerRole = Role::where('name', 'Customer')->first();

       if (!$customerRole) {
           return response()->json([
               'message' => 'Không tìm thấy vai trò Customer.',
           ], 422);
       }

       $user->role_id = $customerRole->id;
       $user->save();

       return response()->json([
           'message' => "Người dùng {$user->name} đã được hạ quyền xuống Customer.",
           'redirect' => route('admin.users'),
       ]);
    }

    public function changeStatus(Request $request)
    {
       if ($error = $this->ensureAdminAccess()) {
           return $error;
       }

       $validated = $request->validate([
           'user_id' => ['required', 'integer', 'exists:users,id'],
           'status' => ['required', 'in:active,banned,deleted'],
       ]);

       $user = User::with('role')->findOrFail($validated['user_id']);

       if ($error = $this->ensureTargetCanBeManaged($user)) {
           return $error;
       }

       $user->status = $validated['status'];
       $user->save();

       $statusText = match ($validated['status']) {
           'active' => 'hoạt động',
           'banned' => 'bị chặn',
           'deleted' => 'đã xóa',
           default => $validated['status'],
       };

       return response()->json([
           'message' => "Đã cập nhật trạng thái người dùng {$user->name}: {$statusText}.",
           'redirect' => route('admin.users'),
       ]);
    }
}
