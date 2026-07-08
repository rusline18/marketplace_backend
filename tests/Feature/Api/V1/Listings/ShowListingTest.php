<?php

use App\Domain\Listings\Models\Listing;

it('shows an active listing to a guest', function () {
    $listing = Listing::factory()->active()->create();

    $this->getJson("/api/v1/listings/{$listing->id}")
        ->assertOk()
        ->assertJsonFragment(['id' => $listing->id]);
});

it('hides a draft listing from a guest', function () {
    $listing = Listing::factory()->create();

    $this->getJson("/api/v1/listings/{$listing->id}")
        ->assertStatus(404);
});
