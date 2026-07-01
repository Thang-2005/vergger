<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;
use App\Models\Permission;
use App\Models\Role;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Tìm hoặc tự tạo quyền nếu chưa có
        $permission = Permission::firstOrCreate(['name' => 'manage_permissions']);

        // Gán manage_permissions cho Admin role nếu tồn tại
        $adminRole = Role::where('name', 'Admin')->first();

        if ($adminRole) {
            // syncWithoutDetaching giúp gán quyền mà không sợ bị trùng lặp dữ liệu
            $adminRole->permissions()->syncWithoutDetaching([$permission->id]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $permission = Permission::where('name', 'manage_permissions')->first();

        if ($permission) {
            // Gỡ liên kết ở bảng trung gian trước khi xóa quyền
            $permission->roles()->detach();
            $permission->delete();
        }
    }
};
