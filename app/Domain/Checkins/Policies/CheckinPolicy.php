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
     * Only the buyer may record a checkin — sellers are partners, a
     * separate account type that never authenticates as a `User`, so they
     * cannot reach this endpoint at all.
     *
     * @param  User  $user  The user attempting the action.
     * @param  Order  $order  The order being checked in against.
     */
    public function create(User $user, Order $order): bool
    {
        return $user->id === $order->user_id;
    }
}
