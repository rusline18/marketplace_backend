<?php

use App\Domain\Listings\Models\Listing;
use App\Models\User;

it('rejects an unauthenticated request to create an order', function () {
    $this->postJson('/api/v1/orders', [])
        ->assertStatus(401);
});

it('lets an authenticated buyer place an order for active listings', function () {
    $buyer = User::factory()->create();
    $listing = Listing::factory()->active()->create(['price' => 25]);

    $this->actingAs($buyer, 'sanctum')
        ->postJson('/api/v1/orders', [
            'items' => [
                ['listing_id' => $listing->id, 'quantity' => 2],
            ],
        ])
        ->assertCreated()
        ->assertJsonFragment(['status' => 'pending', 'total' => 50])
        ->assertJsonCount(1, 'data.items');
});

it('rejects an order with a missing items payload', function () {
    $buyer = User::factory()->create();

    $this->actingAs($buyer, 'sanctum')
        ->postJson('/api/v1/orders', [])
        ->assertStatus(422);
});
