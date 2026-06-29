<?php

declare(strict_types=1);

namespace App\Domain\Listings\Policies;

use App\Domain\Listings\Models\Listing;
use App\Models\User;

class ListingPolicy
{
    /**
     * Determine whether the user can update the listing.
     *
     * @param  User  $user  The user attempting the action.
     * @param  Listing  $listing  The listing being updated.
     * @return bool
     */
    public function update(User $user, Listing $listing): bool
    {
        return $user->id === $listing->user_id;
    }

    /**
     * Determine whether the user can archive the listing.
     *
     * @param  User  $user  The user attempting the action.
     * @param  Listing  $listing  The listing being archived.
     * @return bool
     */
    public function archive(User $user, Listing $listing): bool
    {
        return $user->id === $listing->user_id;
    }
}
