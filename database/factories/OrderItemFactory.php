<?php

namespace Database\Factories;

use App\Domain\Orders\Models\Order;
use App\Domain\Orders\Models\OrderItem;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<OrderItem>
 */
class OrderItemFactory extends Factory
{
    protected $model = OrderItem::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'order_id' => Order::factory(),
            'listing_id' => ListingFactory::new()->active(),
            'quantity' => fake()->numberBetween(1, 3),
            'unit_price' => fake()->randomFloat(2, 1, 500),
        ];
    }
}
