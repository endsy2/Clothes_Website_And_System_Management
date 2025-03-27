<?php

namespace Database\Factories;

use App\Models\ProductImage;
use App\Models\ProductVariant;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductImageFactory extends Factory
{
    protected $model = ProductImage::class;

    public function definition()
    {
        // Get all files from 'public/images'
        $imageFiles = glob(public_path('images') . '/*.{jpg,jpeg,png,gif,webp}', GLOB_BRACE);

        // Pick a random image if the folder is not empty, else use a default placeholder
        $imagePath = !empty($imageFiles)
            ? basename($this->faker->randomElement($imageFiles)) // Pick an existing file
            : 'default.jpg'; // Fallback in case the folder is empty

        return [
            'product_variant_id' => ProductVariant::factory(),
            'images' => 'images/' . $imagePath, // Save only the relative path
        ];
    }
}
