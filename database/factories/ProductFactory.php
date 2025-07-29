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

            $imageId = rand(1, 1000);
            $imageUrl = "https://picsum.photos/id/{$imageId}/400/300";
      return [
            'name' => fake()->words(3, true),
            'description' => fake()->paragraph(),
            'price' => fake()->randomFloat(2, 10, 1000),
            'category_id' => \App\Models\Category::inRandomOrder()->first()?->id ?? \App\Models\Category::factory()->create()->id,
            'imageUrl' => $imageUrl,
            'quantity' => fake()->numberBetween(0, 100),
            'status' => fake()->randomElement(['active', 'inactive']),
            'size' => fake()->randomElement(['small', 'medium', 'large']),
            'ingredients' => fake()->words(5, true), // e.g. "chicken tomato cheese basil salt"
            'allergens' => fake()->randomElement([
                'None', 
                'Gluten', 
                'Peanuts', 
                'Dairy', 
                'Eggs', 
                'Soy'
            ]),
            'extras' => fake()->words(3, true), // e.g. "extra cheese bacon olives"
            'type' => fake()->randomElement(['food', 'drink', 'snack']),
        ];

    }
}