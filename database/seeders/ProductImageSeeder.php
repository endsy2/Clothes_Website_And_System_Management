<?php

namespace Database\Seeders;

use App\Models\ProductImage;
use App\Models\ProductVariant;
use Illuminate\Database\Seeder;

class ProductImageSeeder extends Seeder
{
    public function run(): void
    {


        // Seed ProductImages related to ProductVariants
        ProductImage::factory(100)->create(); // Create 200 ProductImages
    }
}
