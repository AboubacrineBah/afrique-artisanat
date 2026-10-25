<?php

namespace Database\Factories;

use App\Models\Artisan;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Product>
 */
class ProductFactory extends Factory
{
    public function definition(): array
    {
        return [
            'category_id' => Category::factory(),
            'artisan_id' => Artisan::factory(),
            'name' => ucfirst(fake()->unique()->words(3, true)),
            'description' => fake()->paragraph(),
            'price' => fake()->numberBetween(3, 60) * 1000,
            'stock' => fake()->numberBetween(1, 20),
            'material' => fake()->randomElement(['Coton', 'Cuir', 'Bois', 'Terre cuite', 'Laiton', 'Raphia']),
            'origin' => 'Mali',
            'is_active' => true,
            'is_featured' => false,
        ];
    }

    public function featured(): static
    {
        return $this->state(fn () => ['is_featured' => true]);
    }

    public function outOfStock(): static
    {
        return $this->state(fn () => ['stock' => 0]);
    }

    public function inactive(): static
    {
        return $this->state(fn () => ['is_active' => false]);
    }
}
