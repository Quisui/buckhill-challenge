<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

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
    public function definition()
    {
        $title = $this->faker->sentence(4);
        $categories = Category::all();

        return [
            'title' => $title,
            'description' => $this->faker->paragraph(),
            'category_uuid' => $categories->random()->uuid,
            'price' => $this->faker->randomFloat(2, 10, 100),
            'metadata' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
