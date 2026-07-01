<?php

use App\Domain\Listings\Models\Listing;
use App\Domain\Orders\Models\Order;
use App\Domain\Orders\Models\OrderItem;
use App\Models\User;

it('rejects an unauthenticated request to record a checkin', function () {
    $this->postJson('/api/v1/checkins', [])
        ->assertStatus(401);
});

it('lets the buyer record a checkin confirming receipt', function () {
    $buyer = User::factory()->create();
    $seller = User::factory()->create();
    $order = Order::factory()->for($buyer)->create();
    $listing = Listing::factory()->for($seller)->active()->create();
    OrderItem::factory()->for($order)->create(['listing_id' => $listing->id]);

    $this->actingAs($buyer, 'sanctum')
        ->postJson('/api/v1/checkins', [
            'order_id' => $order->id,
            'listing_id' => $listing->id,
        ])
        ->assertCreated()
        ->assertJsonFragment(['status' => 'received']);
});

it('lets the seller record a checkin confirming handover', function () {
    $buyer = User::factory()->create();
    $seller = User::factory()->create();
    $order = Order::factory()->for($buyer)->create();
    $listing = Listing::factory()->for($seller)->active()->create();
    OrderItem::factory()->for($order)->create(['listing_id' => $listing->id]);

    $this->actingAs($seller, 'sanctum')
        ->postJson('/api/v1/checkins', [
            'order_id' => $order->id,
            'listing_id' => $listing->id,
        ])
        ->assertCreated()
        ->assertJsonFragment(['status' => 'handed_over']);
});

it('forbids an unrelated user from recording a checkin', function () {
    $buyer = User::factory()->create();
    $other = User::factory()->create();
    $order = Order::factory()->for($buyer)->create();
    $listing = Listing::factory()->active()->create();
    OrderItem::factory()->for($order)->create(['listing_id' => $listing->id]);

    $this->actingAs($other, 'sanctum')
        ->postJson('/api/v1/checkins', [
            'order_id' => $order->id,
            'listing_id' => $listing->id,
        ])
        ->assertStatus(403);
});

it('rejects a checkin request with a missing payload', function () {
    $buyer = User::factory()->create();

    $this->actingAs($buyer, 'sanctum')
        ->postJson('/api/v1/checkins', [])
        ->assertStatus(422);
});
