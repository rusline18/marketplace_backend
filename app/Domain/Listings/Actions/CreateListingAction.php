<?php

declare(strict_types=1);

namespace App\Domain\Listings\Actions;

use App\Domain\Listings\Enums\ListingStatus;
use App\Domain\Listings\Models\Listing;
use App\Domain\Listings\Repositories\ListingRepositoryInterface;
use Illuminate\Support\Str;

class CreateListingAction
{
    public function __construct(
        private readonly ListingRepositoryInterface $listings,
    ) {}

    /**
     * Create a new draft listing owned by the given user.
     *
     * @param  int  $userId  The id of the user who owns the listing.
     * @param  array{category_id: int, title: string, description: string, price: float}  $data  Listing attributes.
     * @return Listing The newly created draft listing.
     */
    public function handle(int $userId, array $data): Listing
    {
        $listing = new Listing([
            'user_id' => $userId,
            'category_id' => $data['category_id'],
            'title' => $data['title'],
            'slug' => Str::slug($data['title']).'-'.Str::random(6),
            'description' => $data['description'],
            'price' => $data['price'],
            'status' => ListingStatus::Draft,
        ]);

        return $this->listings->save($listing);
    }
}
