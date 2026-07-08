<?php

use App\Domain\Listings\Enums\ListingStatus;
use App\Domain\Listings\Models\Listing;
use App\Domain\Partners\Models\Partner;

it('lets the owner publish their own draft listing', function () {
    $partner = Partner::factory()->approved()->create();
    $listing = Listing::factory()->for($partner)->create(['status' => ListingStatus::Draft]);

    $this->actingAs($partner, 'partner')
        ->postJson("/api/partner/listings/{$listing->id}/publish")
        ->assertOk()
        ->assertJsonFragment(['status' => 'pending_review']);
});

it('forbids publishing someone else\'s listing', function () {
    $owner = Partner::factory()->approved()->create();
    $other = Partner::factory()->approved()->create();
    $listing = Listing::factory()->for($owner)->create(['status' => ListingStatus::Draft]);

    $this->actingAs($other, 'partner')
        ->postJson("/api/partner/listings/{$listing->id}/publish")
        ->assertStatus(403);
});
