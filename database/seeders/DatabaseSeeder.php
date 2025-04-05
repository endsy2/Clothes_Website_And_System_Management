<?php

namespace Database\Seeders;

use App\Models\Customers;
use App\Models\User;
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
            // ProductVariantSeeder::class

        ]);
    }
}
