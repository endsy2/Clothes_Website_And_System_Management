<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\ProductVariant;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\OrderItems>
 */
class OrderItemsFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // Create an instance of ProductVariant
        $productVariant = ProductVariant::factory()->create();

        // Calculate the amount (price * quantity)
        $quantity = $this->faker->numberBetween(1, 10);
        $amount = $productVariant->price * $quantity;

        return [
            'order_id' => Order::factory(),
            'product_variant_id' => $productVariant->id,
            'quantity' => $quantity,
            'amount' => $amount,
        ];
    }
}
