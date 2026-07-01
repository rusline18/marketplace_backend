<?php

declare(strict_types=1);

namespace App\Domain\Checkins\Actions;

use App\Domain\Checkins\Enums\CheckinStatus;
use App\Domain\Checkins\Models\Checkin;
use App\Domain\Checkins\Repositories\CheckinRepositoryInterface;
use App\Domain\Orders\Models\Order;
use InvalidArgumentException;

class RecordCheckinAction
{
    public function __construct(
        private readonly CheckinRepositoryInterface $checkins,
    ) {}

    /**
     * Record a checkin confirming receipt or handover of an order's listing.
     *
     * @param  int  $userId  The id of the user recording the checkin.
     * @param  Order  $order  The order the checkin belongs to.
     * @param  array{listing_id: int}  $data  The requested checkin attributes.
     * @return Checkin The newly created checkin.
     *
     * @throws InvalidArgumentException If the listing is not part of the order.
     */
    public function handle(int $userId, Order $order, array $data): Checkin
    {
        $belongsToOrder = $order->items()->where('listing_id', $data['listing_id'])->exists();

        if (! $belongsToOrder) {
            throw new InvalidArgumentException('Listing does not belong to this order.');
        }

        $status = $order->user_id === $userId ? CheckinStatus::Received : CheckinStatus::HandedOver;

        return $this->checkins->save(new Checkin([
            'order_id' => $order->id,
            'listing_id' => $data['listing_id'],
            'user_id' => $userId,
            'status' => $status,
        ]));
    }
}
