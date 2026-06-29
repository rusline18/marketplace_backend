<?php

declare(strict_types=1);

namespace App\Domain\Listings\Actions;

use App\Domain\Listings\Models\Listing;
use App\Domain\Listings\Repositories\ListingRepositoryInterface;

class UpdateListingAction
{
    public function __construct(
        private readonly ListingRepositoryInterface $listings,
    ) {}

    /**
     * Update the attributes of an existing listing.
     *
     * @param  Listing  $listing  The listing to update.
     * @param  array{category_id?: int, title?: string, description?: string, price?: float}  $data  Attributes to fill.
     * @return Listing The updated listing.
     */
    public function handle(Listing $listing, array $data): Listing
    {
        $listing->fill($data);

        return $this->listings->save($listing);
    }
}
