<?php

namespace Database\Seeders;

use Database\Factories\ProductImageFactory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

namespace Database\Seeders;

use App\Models\ProductImage;
use Illuminate\Database\Seeder;

class ProductImageSeeder extends Seeder
{
    public function run(): void
    {
        ProductImage::factory()->count(100)->create(); // ✅ Correct usage
    }
}
