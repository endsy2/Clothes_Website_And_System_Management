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
        $shirtCategories = [
            'Casual Shirts',
            'Formal Shirts',
            'T-Shirts',
            'Polo Shirts',
            'Hawaiian Shirts',
            'Flannel Shirts',
            'Denim Shirts',
            'Sleeveless Shirts',
            'Graphic Tees',
            'Sports Jerseys',
            'Henley Shirts',
            'Oversized Shirts',
            'Turtleneck Shirts',
            'Baseball Shirts',
            'V-Neck T-Shirts',
            'Round Neck T-Shirts',
            'Long Sleeve Shirts',
            'Short Sleeve Shirts',
            'Compression Shirts',
            'Vintage Shirts'
        ];
        return [
            'category_name' => fake()->randomElement($shirtCategories)
        ];
    }
}
