<?php

use App\Domain\Listings\Models\Listing;
use App\Models\User;

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

it('lets the owner view their own draft listing', function () {
    $user = User::factory()->create();
    $listing = Listing::factory()->for($user)->create();

    $this->actingAs($user, 'sanctum')
        ->getJson("/api/v1/listings/{$listing->id}")
        ->assertOk();
});
