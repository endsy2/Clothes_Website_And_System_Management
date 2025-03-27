<?php

namespace Database\Factories;

use App\Models\Discount;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ProductVariant>
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
            'discount_id' => Discount::factory(),
            'size' => fake()->numberBetween(20, 50),
            'price' => fake()->numberBetween(100, 1000),
            'stock' => fake()->numberBetween(10, 300),
            'color' => fake()->colorName(),
        ];
    }
}
