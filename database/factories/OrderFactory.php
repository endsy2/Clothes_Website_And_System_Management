<?php

namespace Database\Factories;

use App\Models\Customers;
use App\PayMethod;
use App\State;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Order>
 */
class OrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'customer_id' => Customers::factory(),
            'pay_method' => fake()->randomElement(array_column(PayMethod::cases(), 'value')),
            'state' => fake()->randomElement(array_column(State::cases(), 'value')),
        ];
    }
}
