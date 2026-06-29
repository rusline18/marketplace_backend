<?php

use App\Domain\Listings\Models\Listing;
use App\Models\User;

it('lets the owner update their own listing', function () {
    $user = User::factory()->create();
    $listing = Listing::factory()->for($user)->create();

    $this->actingAs($user, 'sanctum')
        ->patchJson("/api/v1/listings/{$listing->id}", ['title' => 'Updated title'])
        ->assertOk()
        ->assertJsonFragment(['title' => 'Updated title']);
});

it('forbids updating someone else\'s listing', function () {
    $owner = User::factory()->create();
    $other = User::factory()->create();
    $listing = Listing::factory()->for($owner)->create();

    $this->actingAs($other, 'sanctum')
        ->patchJson("/api/v1/listings/{$listing->id}", ['title' => 'Hijacked'])
        ->assertStatus(403);
});

it('rejects an unauthenticated update', function () {
    $listing = Listing::factory()->create();

    $this->patchJson("/api/v1/listings/{$listing->id}", ['title' => 'Nope'])
        ->assertStatus(401);
});
