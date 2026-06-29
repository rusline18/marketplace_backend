<?php

declare(strict_types=1);

namespace App\Domain\Listings\Actions;

use App\Domain\Listings\Enums\ListingStatus;
use App\Domain\Listings\Models\Listing;
use App\Domain\Listings\Repositories\ListingRepositoryInterface;
use InvalidArgumentException;

class ArchiveListingAction
{
    public function __construct(
        private readonly ListingRepositoryInterface $listings,
    ) {}

    /**
     * Owner archives an active listing.
     *
     * @param  Listing  $listing  The currently active listing.
     * @return Listing The listing with status set to archived.
     *
     * @throws InvalidArgumentException If the listing is not active.
     */
    public function handle(Listing $listing): Listing
    {
        if ($listing->status !== ListingStatus::Active) {
            throw new InvalidArgumentException('Only active listings can be archived.');
        }

        $listing->status = ListingStatus::Archived;

        return $this->listings->save($listing);
    }
}
