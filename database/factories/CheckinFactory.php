<?php

namespace Database\Factories;

use App\Domain\Checkins\Enums\CheckinStatus;
use App\Domain\Checkins\Models\Checkin;
use App\Domain\Orders\Models\Order;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Checkin>
 */
class CheckinFactory extends Factory
{
    protected $model = Checkin::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'order_id' => Order::factory(),
            'listing_id' => ListingFactory::new()->active(),
            'user_id' => User::factory(),
            'status' => CheckinStatus::Received,
        ];
    }

    /**
     * @return $this
     */
    public function handedOver(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => CheckinStatus::HandedOver,
        ]);
    }
}
