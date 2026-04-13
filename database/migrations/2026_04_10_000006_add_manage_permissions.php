<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
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
        // Kiểm tra và thêm manage_permissions nếu chưa có
        if (!Permission::where('name', 'manage_permissions')->exists()) {
            Permission::create([
                'name' => 'manage_permissions',
            ]);
        }

        // Gán manage_permissions cho Admin role nếu chưa có
        $adminRole = Role::where('name', 'Admin')->first();
        if ($adminRole) {
            $permission = Permission::where('name', 'manage_permissions')->first();
            if ($permission && !$adminRole->permissions()->where('permission_id', $permission->id)->exists()) {
                $adminRole->permissions()->attach($permission->id);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Xóa manage_permissions permission
        Permission::where('name', 'manage_permissions')->delete();
    }
};
