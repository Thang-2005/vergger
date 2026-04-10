<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $adminRole = Role::firstOrCreate(['name' => 'Admin']);
        $staffRole = Role::firstOrCreate(['name' => 'Staff']);

        User::updateOrCreate(
            ['email' => 'admin@gmail.com'],
            [
                'name' => 'Admin',
                'password' => Hash::make('123456'),
                'status' => 'active',
                'phone_number' => null,
                'avatar' => null,
                'address' => null,
                'activation_token' => null,
                'role_id' => $adminRole->id,
                'google_id' => null,
            ]
        );

        User::updateOrCreate(
            ['email' => 'staff@gmail.com'],
            [
                'name' => 'Staff',
                'password' => Hash::make('123456'),
                'status' => 'active',
                'phone_number' => null,
                'avatar' => null,
                'address' => null,
                'activation_token' => null,
                'role_id' => $staffRole->id,
                'google_id' => null,
            ]
        );
    }
}