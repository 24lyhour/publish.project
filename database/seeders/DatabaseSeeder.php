<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Lyhour Kouch', // cspell:disable-line
            'email' => 'lyhour.kouch@gmail.com',
            'password' => bcrypt('12345678'),
        ]);

        $this->call([
            CategorySeeder::class,
            RestaurantSeeder::class,
            ProductSeeder::class,
            MenuSeeder::class,
        ]);
    }
}
