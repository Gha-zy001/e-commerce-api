<?php

namespace Database\Factories\Store;

use App\Models\ProductVariant;
use App\Models\store\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ProductVariant>
 */
class ProductVariantFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'product_id' => Product::factory(),
            'price' => fake()->randomNumber(2),
            'stock' => fake()->numberBetween(1, 100),
            'attributes' => [
                'color' => fake()->randomElement(['Red', 'Blue', 'Black']),
                'size' => fake()->randomElement(['S', 'M', 'L']),
            ],
            'sku' => fake()->unique()->bothify('SKU-#####'),
        ];
    }
}
