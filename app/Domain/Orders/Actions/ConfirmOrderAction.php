<?php

declare(strict_types=1);

namespace App\Domain\Orders\Actions;

use App\Domain\Orders\Enums\OrderStatus;
use App\Domain\Orders\Models\Order;
use App\Domain\Orders\Repositories\OrderRepositoryInterface;
use InvalidArgumentException;

class ConfirmOrderAction
{
    public function __construct(
        private readonly OrderRepositoryInterface $orders,
    ) {}

    /**
     * Staff confirms a pending order.
     *
     * @param  Order  $order  The order currently pending confirmation.
     * @return Order The order with status set to confirmed.
     *
     * @throws InvalidArgumentException If the order is not pending.
     */
    public function handle(Order $order): Order
    {
        if ($order->status !== OrderStatus::Pending) {
            throw new InvalidArgumentException('Only pending orders can be confirmed.');
        }

        $order->statusHistories()->create([
            'from_status' => $order->status,
            'to_status' => OrderStatus::Confirmed,
        ]);

        $order->status = OrderStatus::Confirmed;

        return $this->orders->save($order);
    }
}
