<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

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
        return [
            'name' => fake()->words(3, true),
            'description' => fake()->paragraph(),
            'price' => fake()->randomFloat(2, 10, 1000),
            'status' => fake()->randomElement(['active', 'inactive']),
            'imageUrl' => fake()->imageUrl(640, 480, 'products', true),
            'sku' => fake()->unique()->bothify('SKU-#####'),
            'stock' => fake()->numberBetween(0, 100),
            'category_id' => \App\Models\Category::inRandomOrder()->first()?->id ?? \App\Models\Category::factory()->create()->id,
        ];
    }
}