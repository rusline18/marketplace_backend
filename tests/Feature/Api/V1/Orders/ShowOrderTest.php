<?php

use App\Domain\Orders\Models\Order;
use App\Models\User;

it('lets the owner view their own order', function () {
    $buyer = User::factory()->create();
    $order = Order::factory()->for($buyer)->create();

    $this->actingAs($buyer, 'sanctum')
        ->getJson("/api/v1/orders/{$order->id}")
        ->assertOk()
        ->assertJsonFragment(['id' => $order->id]);
});

it('forbids viewing someone else\'s order', function () {
    $owner = User::factory()->create();
    $other = User::factory()->create();
    $order = Order::factory()->for($owner)->create();

    $this->actingAs($other, 'sanctum')
        ->getJson("/api/v1/orders/{$order->id}")
        ->assertStatus(403);
});

it('rejects an unauthenticated request to show an order', function () {
    $order = Order::factory()->create();

    $this->getJson("/api/v1/orders/{$order->id}")
        ->assertStatus(401);
});
