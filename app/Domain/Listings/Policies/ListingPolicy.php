<?php

declare(strict_types=1);

namespace App\Domain\Listings\Policies;

use App\Domain\Listings\Models\Listing;
use App\Domain\Partners\Models\Partner;

class ListingPolicy
{
    /**
     * Determine whether the partner can update the listing.
     *
     * @param  Partner  $partner  The partner attempting the action.
     * @param  Listing  $listing  The listing being updated.
     */
    public function update(Partner $partner, Listing $listing): bool
    {
        return $partner->id === $listing->partner_id;
    }

    /**
     * Determine whether the partner can archive the listing.
     *
     * @param  Partner  $partner  The partner attempting the action.
     * @param  Listing  $listing  The listing being archived.
     */
    public function archive(Partner $partner, Listing $listing): bool
    {
        return $partner->id === $listing->partner_id;
    }
}
