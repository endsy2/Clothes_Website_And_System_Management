<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Brand>
 */
class BrandFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $brands = ['Nike', 'Adidas', 'Puma', 'Levi’s', 'Uniqlo', 'Zara', 'H&M', 'Gucci', 'Supreme'];
        return [
            'brand_name' => fake()->randomElement($brands)
        ];
    }
}
