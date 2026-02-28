<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            ['name' => 'Rau củ quả', 'slug' => 'rau-cu-qua', 'description' => 'Các loại rau củ quả tươi ngon, giàu dinh dưỡng.','image' => 'uploads/categories/rau_cu.png'],
            ['name' => 'Trái cây', 'slug' => 'trai-cay', 'description' => 'Các loại trái cây tươi ngon, giàu vitamin.','image' => 'uploads/categories/trai_cay.png'],
            ['name' => 'Thịt', 'slug' => 'thit-ca', 'description' => 'Các loại thịt tươi ngon, giàu protein.','image' => 'uploads/categories/thit.png'],
            ['name' => 'cá', 'slug' => 'ca', 'description' => 'Các loại cá tươi ngon, giàu protein.','image' => 'uploads/categories/ca.png'],
            ['name' => 'Thực phẩm khác', 'slug' => 'thuc-pham-khac', 'description' => 'Các loại thực phẩm khác tươi ngon, giàu dinh dưỡng.','image' => 'uploads/categories/thuc_pham_khac.png'],
    ];
        foreach ($categories as $category) {
            \App\Models\Category::create($category);
        }
    }
}
