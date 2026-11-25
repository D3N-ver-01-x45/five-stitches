<?php

namespace Database\Factories;

use App\Enums\ProductCategory;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Product>
 */
class ProductFactory extends Factory
{
    public function definition(): array
    {
        $name = 'Crochet '.ucfirst(fake()->unique()->word());

        return [
            'category' => fake()->randomElement(ProductCategory::cases()),
            'name' => $name,
            'slug' => Str::slug($name),
            'description' => fake()->sentence(12),
            'options' => 'Color, size',
            'base_price' => fake()->randomElement([50, 80, 120, 250]),
            'ready_min_days' => 1,
            'ready_max_days' => 3,
            'image' => null,
            'is_active' => true,
            'sort_order' => 0,
        ];
    }

    public function hidden(): static
    {
        return $this->state(fn () => ['is_active' => false]);
    }
}
