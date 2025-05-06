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
        $order = \App\Models\Order::factory()->create();
        $productVariant = \App\Models\ProductVariant::factory()->create();

        $quantity = $this->faker->numberBetween(1, 10);
        $amount = $productVariant->price * $quantity;

        return [
            'order_id' => $order->id,
            'product_variant_id' => $productVariant->id,
            'quantity' => $quantity,
            'amount' => $amount,
        ];
    }
}
