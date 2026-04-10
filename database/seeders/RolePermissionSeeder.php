<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;

class RolePermissionSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $adminRole = Role::where('name', 'Admin')->first();
        $staffRole = Role::where('name', 'Staff')->first();

        if (!$adminRole) {
            return;
        }

        $allPermissionIds = Permission::pluck('id')->all();
        $adminRole->permissions()->sync($allPermissionIds);

        if ($staffRole) {
            $staffPermissionIds = Permission::whereIn('name', [
                'manage_products',
                'manage_orders',
                'manage_contacts',
            ])->pluck('id')->all();

            $staffRole->permissions()->sync($staffPermissionIds);
        }
    }
}
