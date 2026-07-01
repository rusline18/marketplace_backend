<?php

use App\Domain\Orders\Models\Order;
use App\Models\User;

it('lets the owner cancel their own pending order', function () {
    $buyer = User::factory()->create();
    $order = Order::factory()->for($buyer)->create();

    $this->actingAs($buyer, 'sanctum')
        ->postJson("/api/v1/orders/{$order->id}/cancel")
        ->assertOk()
        ->assertJsonFragment(['status' => 'cancelled']);
});

it('forbids cancelling someone else\'s order', function () {
    $owner = User::factory()->create();
    $other = User::factory()->create();
    $order = Order::factory()->for($owner)->create();

    $this->actingAs($other, 'sanctum')
        ->postJson("/api/v1/orders/{$order->id}/cancel")
        ->assertStatus(403);
});

it('forbids the owner from cancelling an order that has already been confirmed', function () {
    $buyer = User::factory()->create();
    $order = Order::factory()->for($buyer)->confirmed()->create();

    $this->actingAs($buyer, 'sanctum')
        ->postJson("/api/v1/orders/{$order->id}/cancel")
        ->assertStatus(403);
});
