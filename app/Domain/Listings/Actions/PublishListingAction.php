<?php

declare(strict_types=1);

namespace App\Domain\Listings\Actions;

use App\Domain\Listings\Enums\ListingStatus;
use App\Domain\Listings\Models\Listing;
use App\Domain\Listings\Repositories\ListingRepositoryInterface;
use InvalidArgumentException;

class PublishListingAction
{
    public function __construct(
        private readonly ListingRepositoryInterface $listings,
    ) {}

    /**
     * Owner submits a draft listing for moderation.
     *
     * @param  Listing  $listing  The draft listing to submit.
     * @return Listing The listing with status set to pending review.
     *
     * @throws InvalidArgumentException If the listing is not a draft.
     */
    public function handle(Listing $listing): Listing
    {
        if ($listing->status !== ListingStatus::Draft) {
            throw new InvalidArgumentException('Only draft listings can be submitted for review.');
        }

        $listing->status = ListingStatus::PendingReview;

        return $this->listings->save($listing);
    }
}
