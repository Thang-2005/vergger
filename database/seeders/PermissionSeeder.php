<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $permissions = [
            'users.view',
            'users.manage',
            'categories.view',
            'categories.create',
            'categories.update',
            'categories.delete',
            'categories.toggle_status',
            'products.view',
            'products.create',
            'products.update',
            'products.delete',
            'orders.view',
            'orders.manage',
            'contacts.view',
            'contacts.manage',
            'permissions.view',
            'permissions.manage',
            'permissions.assign',
            'permissions.create',
            'manage_users',
            'manage_categories',
            'manage_products',
            'manage_orders',
            'manage_contacts',
            'manage_permissions',
            'manage_coupons',
        ];

        foreach ($permissions as $name) {
            Permission::firstOrCreate(['name' => $name]);
        }
    }
}
