<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Category>
 */
class CategoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->words(2, true),
            'description' => fake()->sentence(),
            'imageUrl' => fake()->imageUrl(640, 480, 'categories', true),
            'status' => fake()->randomElement(['active', 'inactive']),
            'restaurant_id' => \App\Models\Restaurant::inRandomOrder()->first()?->id ?? \App\Models\Restaurant::factory()->create()->id,
        ];
    }
}
