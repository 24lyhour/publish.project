<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Menu>
 */
class MenuFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'menu_name' => fake()->words(3, true),
            'category_id' => \App\Models\Category::inRandomOrder()->first()?->id ?? \App\Models\Category::factory()->create()->id,
            'restaurant_id' => \App\Models\Restaurant::inRandomOrder()->first()?->id ?? \App\Models\Restaurant::factory()->create()->id,
            'price' => fake()->randomFloat(2, 5, 100),
            'description' => fake()->sentence(),
        ];
    }
}
