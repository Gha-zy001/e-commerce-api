<?php

namespace Database\Factories\store;

use App\Models\Model;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Model>
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
            'name' => fake()->name(),
            'stock' => fake()->randomNumber(2),
            'category_id' => 1,
            'description' => fake()->text(50),
            'is_active' => fake()->boolean(),
            'is_featured' => fake()->boolean(),
            'slug' => fake()->slug(),

        ];
    }
}
