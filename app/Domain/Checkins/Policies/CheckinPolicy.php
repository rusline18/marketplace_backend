<?php

declare(strict_types=1);

namespace App\Domain\Checkins\Policies;

use App\Domain\Orders\Models\Order;
use App\Models\User;

class CheckinPolicy
{
    /**
     * Determine whether the user may record a checkin for the given order.
     *
     * Only a participant of the order — the buyer or the seller of one of
     * its line items — may record a checkin for it.
     *
     * @param  User  $user  The user attempting the action.
     * @param  Order  $order  The order being checked in against.
     */
    public function create(User $user, Order $order): bool
    {
        if ($user->id === $order->user_id) {
            return true;
        }

        return $order->items()
            ->whereHas('listing', fn ($query) => $query->where('user_id', $user->id))
            ->exists();
    }
}
