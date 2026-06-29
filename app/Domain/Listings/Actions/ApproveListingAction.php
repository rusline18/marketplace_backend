<?php

declare(strict_types=1);

namespace App\Domain\Listings\Actions;

use App\Domain\Listings\Enums\ListingStatus;
use App\Domain\Listings\Models\Listing;
use App\Domain\Listings\Repositories\ListingRepositoryInterface;
use InvalidArgumentException;

class ApproveListingAction
{
    public function __construct(
        private readonly ListingRepositoryInterface $listings,
    ) {}

    /**
     * Admin approves a listing pending moderation.
     *
     * @param  Listing  $listing  The listing currently pending review.
     * @return Listing The listing with status set to active and published_at stamped.
     *
     * @throws InvalidArgumentException If the listing is not pending review.
     */
    public function handle(Listing $listing): Listing
    {
        if ($listing->status !== ListingStatus::PendingReview) {
            throw new InvalidArgumentException('Only listings pending review can be approved.');
        }

        $listing->status = ListingStatus::Active;
        $listing->published_at = now();

        return $this->listings->save($listing);
    }
}
