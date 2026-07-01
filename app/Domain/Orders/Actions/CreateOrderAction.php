<?php

declare(strict_types=1);

namespace App\Domain\Orders\Actions;

use App\Domain\Listings\Enums\ListingStatus;
use App\Domain\Listings\Models\Listing;
use App\Domain\Orders\Enums\OrderStatus;
use App\Domain\Orders\Models\Order;
use App\Domain\Orders\Repositories\OrderRepositoryInterface;
use InvalidArgumentException;

class CreateOrderAction
{
    public function __construct(
        private readonly OrderRepositoryInterface $orders,
    ) {}

    /**
     * Place a new order for the given buyer.
     *
     * @param  int  $userId  The id of the buyer placing the order.
     * @param  array{items: array<int, array{listing_id: int, quantity: int}>}  $data  The requested line items.
     * @return Order The newly created pending order.
     *
     * @throws InvalidArgumentException If a listing is unavailable or owned by the buyer.
     */
    public function handle(int $userId, array $data): Order
    {
        $listings = Listing::query()
            ->whereIn('id', collect($data['items'])->pluck('listing_id'))
            ->get()
            ->keyBy('id');

        $order = $this->orders->save(new Order([
            'user_id' => $userId,
            'status' => OrderStatus::Pending,
            'total' => 0,
        ]));

        $total = 0;

        foreach ($data['items'] as $item) {
            $listing = $listings->get($item['listing_id']);

            if (! $listing || $listing->status !== ListingStatus::Active) {
                throw new InvalidArgumentException("Listing {$item['listing_id']} is not available for purchase.");
            }

            if ($listing->user_id === $userId) {
                throw new InvalidArgumentException('You cannot order your own listing.');
            }

            $order->items()->create([
                'listing_id' => $listing->id,
                'quantity' => $item['quantity'],
                'unit_price' => $listing->price,
            ]);

            $total += $listing->price * $item['quantity'];
        }

        $order->total = $total;
        $order->statusHistories()->create([
            'from_status' => null,
            'to_status' => OrderStatus::Pending,
        ]);

        return $this->orders->save($order);
    }
}
