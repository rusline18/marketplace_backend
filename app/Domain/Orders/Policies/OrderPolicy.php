<?php

declare(strict_types=1);

namespace App\Domain\Orders\Policies;

use App\Domain\Orders\Enums\OrderStatus;
use App\Domain\Orders\Models\Order;
use App\Models\User;

class OrderPolicy
{
    /**
     * Determine whether the user can view the order.
     *
     * @param  User  $user  The user attempting the action.
     * @param  Order  $order  The order being viewed.
     */
    public function view(User $user, Order $order): bool
    {
        return $user->id === $order->user_id;
    }

    /**
     * Determine whether the user can cancel the order.
     *
     * A buyer may only cancel their own order while it is still pending,
     * i.e. before it has been confirmed by staff.
     *
     * @param  User  $user  The user attempting the action.
     * @param  Order  $order  The order being cancelled.
     */
    public function cancel(User $user, Order $order): bool
    {
        return $user->id === $order->user_id && $order->status === OrderStatus::Pending;
    }
}
