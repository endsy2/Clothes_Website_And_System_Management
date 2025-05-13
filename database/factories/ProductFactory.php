<?php

namespace Database\Factories;

use App\Models\Brand;
use App\Models\Category;
use App\Models\productType as ModelsProductType;
use App\Models\ProductType as AppModelsProductType;
use App\productType;
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
        return [
            'category_id' => Category::factory(),
            'brand_id' => Brand::factory(),
            'product_type_id' => \App\Models\ProductType::factory(),
            'description' => fake()->sentence(6),
            'name' => fake()->sentence(3),
        ];
    }
}
