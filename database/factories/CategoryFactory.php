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
        $imageFiles = glob(public_path('images') . '/*.{jpg,jpeg,png,gif,webp}', GLOB_BRACE);

        // Pick a random image if the folder is not empty, else use a default placeholder
        $imagePath = !empty($imageFiles)
            ? basename($this->faker->randomElement($imageFiles)) // Pick an existing file
            : 'default.jpg'; // Fallback in case the folder is empty
        return [
            'category_name' => fake()->randomElement($shirtCategories),
            'images' => 'images/' . $imagePath, // Save only the relative path
        ];
    }
}
