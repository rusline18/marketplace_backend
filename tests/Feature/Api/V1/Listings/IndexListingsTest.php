<?php

use App\Domain\Listings\Models\Listing;

it('lists only active listings to a guest', function () {
    Listing::factory()->active()->create(['title' => 'Active listing']);
    Listing::factory()->create(['title' => 'Draft listing']);

    $this->getJson('/api/v1/listings')
        ->assertOk()
        ->assertJsonCount(1, 'data')
        ->assertJsonFragment(['title' => 'Active listing']);
});

it('filters listings by category', function () {
    $active = Listing::factory()->active()->create();
    Listing::factory()->active()->create();

    $this->getJson('/api/v1/listings?category_id='.$active->category_id)
        ->assertOk()
        ->assertJsonCount(1, 'data');
});
