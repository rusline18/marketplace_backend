<?php

use App\Domain\Listings\Actions\ArchiveListingAction;
use App\Domain\Listings\Enums\ListingStatus;
use App\Domain\Listings\Models\Listing;
use App\Domain\Listings\Policies\ListingPolicy;
use App\Models\User;

it('archives an active listing', function () {
    $listing = Listing::factory()->active()->create();

    $result = app(ArchiveListingAction::class)->handle($listing);

    expect($result->status)->toBe(ListingStatus::Archived);
});

it('refuses to archive a listing that is not active', function () {
    $listing = Listing::factory()->create(['status' => ListingStatus::Draft]);

    app(ArchiveListingAction::class)->handle($listing);
})->throws(InvalidArgumentException::class);

it('denies archiving someone else\'s listing via the policy', function () {
    $owner = User::factory()->create();
    $other = User::factory()->create();
    $listing = Listing::factory()->for($owner)->active()->create();

    expect((new ListingPolicy)->archive($other, $listing))->toBeFalse();
});
