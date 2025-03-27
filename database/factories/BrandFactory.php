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
        $imageFiles = glob(public_path('images') . '/*.{jpg,jpeg,png,gif,webp}', GLOB_BRACE);

        // Pick a random image if the folder is not empty, else use a default placeholder
        $imagePath = !empty($imageFiles)
            ? basename($this->faker->randomElement($imageFiles)) // Pick an existing file
            : 'default.jpg'; // Fallback in case the folder is empty
        return [
            'brand_name' => fake()->randomElement($brands),
            'images' => 'brands/' . $imagePath
        ];
    }
}
