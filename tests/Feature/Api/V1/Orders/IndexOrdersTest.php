<?php

use App\Domain\Orders\Models\Order;
use App\Models\User;

it('rejects an unauthenticated request to list orders', function () {
    $this->getJson('/api/v1/orders')
        ->assertStatus(401);
});

it('only lists orders belonging to the authenticated buyer', function () {
    $buyer = User::factory()->create();
    $other = User::factory()->create();

    Order::factory()->for($buyer)->create();
    Order::factory()->for($other)->create();

    $this->actingAs($buyer, 'sanctum')
        ->getJson('/api/v1/orders')
        ->assertOk()
        ->assertJsonCount(1, 'data');
});
