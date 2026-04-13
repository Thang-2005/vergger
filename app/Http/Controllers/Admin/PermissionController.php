<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;

class PermissionController extends Controller
{
    private function denyIfNoPermission()
    {
        /** @var \App\Models\User|null $adminUser */
        $adminUser = auth('admin')->user();

        if (!$adminUser || !$adminUser->hasPermission('manage_permissions')) {
            return true;
        }

        return false;
    }

    /**
     * Hiển thị trang quản lý quyền hạn cho nhân viên/roles
     */
    public function index()
    {
        if ($this->denyIfNoPermission()) {
            return back()->with('error', 'Bạn không có quyền truy cập trang này.');
        }

        $permissions = Permission::where('name', 'not like', 'manage\_%')->orderBy('name')->get();
        $roles = Role::orderBy('name')->get();

        $selectedRoleId = (int) request('role_id', $roles->first()?->id ?? 0);
        $selectedRole = $roles->firstWhere('id', $selectedRoleId) ?? $roles->first();
        $selectedRolePermissions = $selectedRole
            ? $selectedRole->permissions()->pluck('id')->toArray()
            : [];

        return view('admin.pages.permissions.index', compact(
            'permissions',
            'roles',
            'selectedRole',
            'selectedRolePermissions'
        ));
    }

    /**
     * Trang quản lý vai trò
     */
    public function roleIndex()
    {
        if ($this->denyIfNoPermission()) {
            return back()->with('error', 'Bạn không có quyền truy cập trang này.');
        }

        $roles = Role::withCount('permissions', 'users')->orderBy('name')->get();

        return view('admin.pages.roles.index', compact('roles'));
    }

    /**
     * Tạo vai trò mới
     */
    public function storeRole(Request $request)
    {
        if ($this->denyIfNoPermission()) {
            return response()->json(['message' => 'Bạn không có quyền tạo vai trò.'], 403);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:roles,name',
        ]);

        $role = Role::create([
            'name' => trim($validated['name']),
        ]);

        return response()->json([
            'message' => "Đã tạo vai trò {$role->name}.",
            'status' => true,
        ]);
    }

    /**
     * Đổi tên vai trò
     */
    public function updateRole(Request $request, Role $role)
    {
        if ($this->denyIfNoPermission()) {
            return response()->json(['message' => 'Bạn không có quyền sửa vai trò.'], 403);
        }

        if (strtolower($role->name) === 'admin') {
            return response()->json(['message' => 'Không thể đổi tên vai trò Admin.'], 422);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:roles,name,' . $role->id,
        ]);

        $role->update([
            'name' => trim($validated['name']),
        ]);

        return response()->json([
            'message' => "Đã cập nhật vai trò thành {$role->name}.",
            'status' => true,
        ]);
    }

    /**
     * Xóa vai trò
     */
    public function destroyRole(Role $role)
    {
        if ($this->denyIfNoPermission()) {
            return response()->json(['message' => 'Bạn không có quyền xóa vai trò.'], 403);
        }

        if (strtolower($role->name) === 'admin') {
            return response()->json(['message' => 'Không thể xóa vai trò Admin.'], 422);
        }

        if ($role->users()->exists()) {
            return response()->json(['message' => 'Vai trò đang có người dùng, không thể xóa.'], 422);
        }

        $role->permissions()->detach();
        $role->delete();

        return response()->json([
            'message' => 'Đã xóa vai trò thành công.',
            'status' => true,
        ]);
    }

    /**
     * Cập nhật quyền hạn cho một role
     */
    public function updateRolePermissions(Request $request, Role $role)
    {
        if ($this->denyIfNoPermission()) {
            return response()->json(['message' => 'Bạn không có quyền cấp phát quyền hạn.'], 403);
        }

        // Admin không thể thay đổi quyền
        if (strtolower($role->name) === 'admin') {
            return response()->json(['message' => 'Không thể thay đổi quyền hạn của Admin.'], 422);
        }

        $validated = $request->validate([
            'permissions' => 'array',
            'permissions.*' => 'integer|exists:permissions,id',
        ]);

        // Đồng bộ quyền hạn
        $role->permissions()->sync($validated['permissions'] ?? []);

        return response()->json([
            'message' => "Cập nhật quyền hạn cho {$role->name} thành công.",
            'status' => true,
        ]);
    }

    /**
     * Quản lý quyền cho nhân viên cụ thể
     */
    public function manageUserPermissions(User $user)
    {
        if ($this->denyIfNoPermission()) {
            return back()->with('error', 'Bạn không có quyền truy cập trang này.');
        }

        $adminUser = auth('admin')->user();

        // Không cho phép quản lý Admin hoặc chính mình
        if (!$user->role || strtolower($user->role->name) === 'admin') {
            return back()->with('error', 'Không thể quản lý quyền hạn cho Admin.');
        }

        if ($adminUser->id === $user->id) {
            return back()->with('error', 'Không thể thay đổi quyền hạn của chính mình.');
        }

        $permissions = Permission::where('name', 'not like', 'manage\_%')->orderBy('name')->get();
        $userPermissions = $user->role->permissions()->pluck('id')->toArray();

        return view('admin.pages.permissions.user', compact('user', 'permissions', 'userPermissions'));
    }

    /**
     * Hiển thị form tạo quyền mới
     */
    public function createPermission()
    {
        if ($this->denyIfNoPermission()) {
            return back()->with('error', 'Bạn không có quyền truy cập trang này.');
        }

        return view('admin.pages.permissions.create');
    }

    /**
     * Lưu quyền mới
     */
    public function storePermission(Request $request)
    {
        if ($this->denyIfNoPermission()) {
            return response()->json(['message' => 'Bạn không có quyền tạo quyền hạn mới.'], 403);
        }

        $validated = $request->validate([
            'name' => 'required|string|unique:permissions,name|max:255',
            'description' => 'nullable|string|max:500',
        ]);

        $permission = Permission::create($validated);

        return response()->json([
            'message' => "Quyền hạn '{$permission->name}' đã được tạo thành công.",
            'redirect' => route('admin.permissions'),
        ]);
    }
}
