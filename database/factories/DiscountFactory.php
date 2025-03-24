<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Discount>
 */
class DiscountFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $startDate = fake()->dateTimeBetween('now', '+1 month');
        $endDate = fake()->dateTimeBetween($startDate, '+3 months');

        return [
            'discount_name' => fake()->sentence(3), // Generates discount names like "Limited Summer Offer"
            'discount' => fake()->numberBetween(5, 50), // Generates discount percentages (e.g., 10%, 20%)
            'start_date' => $startDate,
            'end_date' => $endDate,
        ];
    }
}
