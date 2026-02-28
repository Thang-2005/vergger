<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use App\Models\Category;
/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $name = $this->faker->randomElement([
            'Rau củ quả', 'Trái cây', 'Thịt', 'Cá', 'Thực phẩm khác',
            'sup', 'bắp cải', 'cà rốt', 'dưa hấu', 'táo', 'thịt bò', 'thịt gà', 'cá hồi', 'cá thu', 'mì tôm', 'gạo', 'đường',

            ]) . ' ' . $this->faker->word();
        return [
            'name' => ucfirst($name),
            'slug' => Str::slug($name).'-' . $this->faker->unique()->randomNumber(5),
            'price' => $this->faker->randomFloat(2, 10000, 200000),
            'description' => $this->faker->paragraph(),
            
            'category_id' => Category::inRandomOrder()->first()->id,    
            'stock' => $this->faker->numberBetween(0, 100),
            'status' => $this->faker->randomElement(['in_stock', 'out_of_stock']),
            'unit' => $this->faker->randomElement(['kg', 'bó', 'hộp', 'túi', 'chai', 'lon']),

        ];
    }
}
