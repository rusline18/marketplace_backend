<?php

declare(strict_types=1);

namespace App\Domain\Orders\Actions;

use App\Domain\Orders\Enums\OrderStatus;
use App\Domain\Orders\Models\Order;
use App\Domain\Orders\Repositories\OrderRepositoryInterface;
use InvalidArgumentException;

class CancelOrderAction
{
    public function __construct(
        private readonly OrderRepositoryInterface $orders,
    ) {}

    /**
     * Cancel an order that has not already been cancelled.
     *
     * @param  Order  $order  The order to cancel.
     * @return Order The order with status set to cancelled.
     *
     * @throws InvalidArgumentException If the order is already cancelled.
     */
    public function handle(Order $order): Order
    {
        if ($order->status === OrderStatus::Cancelled) {
            throw new InvalidArgumentException('Order is already cancelled.');
        }

        $order->statusHistories()->create([
            'from_status' => $order->status,
            'to_status' => OrderStatus::Cancelled,
        ]);

        $order->status = OrderStatus::Cancelled;

        return $this->orders->save($order);
    }
}
