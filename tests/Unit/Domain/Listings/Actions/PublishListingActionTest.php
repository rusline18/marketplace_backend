<?php

use App\Domain\Listings\Actions\PublishListingAction;
use App\Domain\Listings\Enums\ListingStatus;
use App\Domain\Listings\Models\Listing;
use App\Domain\Listings\Repositories\EloquentListingRepository;
use App\Domain\Listings\Repositories\ListingRepositoryInterface;

it('moves a draft listing to pending review', function () {
    $listing = Listing::factory()->create(['status' => ListingStatus::Draft]);

    $result = app(PublishListingAction::class)->handle($listing);

    expect($result->status)->toBe(ListingStatus::PendingReview);
});

it('refuses to submit a listing that is not a draft', function () {
    $listing = Listing::factory()->active()->create();

    app(PublishListingAction::class)->handle($listing);
})->throws(InvalidArgumentException::class);

it('depends on the bound repository implementation', function () {
    expect(app(ListingRepositoryInterface::class))->toBeInstanceOf(
        EloquentListingRepository::class,
    );
});
