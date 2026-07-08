<?php

use App\Domain\Listings\Models\Listing;
use App\Domain\Partners\Models\Partner;

it('lets the owner archive their own active listing', function () {
    $partner = Partner::factory()->approved()->create();
    $listing = Listing::factory()->for($partner)->active()->create();

    $this->actingAs($partner, 'partner')
        ->postJson("/api/partner/listings/{$listing->id}/archive")
        ->assertOk()
        ->assertJsonFragment(['status' => 'archived']);
});

it('forbids archiving someone else\'s listing', function () {
    $owner = Partner::factory()->approved()->create();
    $other = Partner::factory()->approved()->create();
    $listing = Listing::factory()->for($owner)->active()->create();

    $this->actingAs($other, 'partner')
        ->postJson("/api/partner/listings/{$listing->id}/archive")
        ->assertStatus(403);
});
