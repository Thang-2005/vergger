<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Product;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    
    public function run(): void
    {
<<<<<<< HEAD
        $this->call([
            RoleSeeder::class,
            PermissionSeeder::class,
            RolePermissionSeeder::class,
            CategorySeeder::class,
            AdminUserSeeder::class,
            
        ]);
=======
       $this->call([
        RoleSeeder::class,
        CategorySeeder::class,
    ]);

    Product::factory(100)->create();
>>>>>>> e7351409f7ab6f1e413c46e3156063f849d60737

        
    }
}
