<?php

use App\Domain\Listings\Models\Listing;
use App\Domain\Partners\Models\Partner;

it('lets the owner update their own listing', function () {
    $partner = Partner::factory()->approved()->create();
    $listing = Listing::factory()->for($partner)->create();

    $this->actingAs($partner, 'partner')
        ->patchJson("/api/partner/listings/{$listing->id}", ['title' => 'Updated title'])
        ->assertOk()
        ->assertJsonFragment(['title' => 'Updated title']);
});

it('forbids updating someone else\'s listing', function () {
    $owner = Partner::factory()->approved()->create();
    $other = Partner::factory()->approved()->create();
    $listing = Listing::factory()->for($owner)->create();

    $this->actingAs($other, 'partner')
        ->patchJson("/api/partner/listings/{$listing->id}", ['title' => 'Hijacked'])
        ->assertStatus(403);
});

it('rejects an unauthenticated update', function () {
    $listing = Listing::factory()->create();

    $this->patchJson("/api/partner/listings/{$listing->id}", ['title' => 'Nope'])
        ->assertStatus(401);
});
