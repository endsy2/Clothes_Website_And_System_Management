<?php

namespace Database\Seeders;

use App\Models\Customers;
use App\Models\productType;
use App\Models\User;
use Database\Seeders\ProductType as SeedersProductType;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();


        $this->call([
            AdminSeeder::class,
            BrandSeeder::class,
            CategorySeeder::class,
            CustomersSeeder::class,
            DiscountSeeder::class,
            OrderItemsSeeder::class,
            OrderSeeder::class,
            ProductImageSeeder::class,
            ProductSeeder::class,
            SeedersProductType::class
            // ProductVariantSeeder::class

        ]);
    }
}
