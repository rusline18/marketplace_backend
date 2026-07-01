<?php

use App\Domain\Orders\Models\Order;
use App\Domain\Users\Models\Admin;
use App\Models\User;

it('rejects a sanctum user token on the admin orders queue', function () {
    $user = User::factory()->create();

    $this->actingAs($user, 'sanctum')
        ->getJson('/api/admin/orders')
        ->assertStatus(401);
});

it('lists orders filtered by status for an admin', function () {
    $admin = Admin::factory()->create();
    Order::factory()->create();
    Order::factory()->confirmed()->create();

    $this->actingAs($admin, 'admin')
        ->getJson('/api/admin/orders?status=confirmed')
        ->assertOk()
        ->assertJsonCount(1, 'data');
});

it('shows an order detail for an admin', function () {
    $admin = Admin::factory()->create();
    $order = Order::factory()->create();

    $this->actingAs($admin, 'admin')
        ->getJson("/api/admin/orders/{$order->id}")
        ->assertOk()
        ->assertJsonFragment(['id' => $order->id]);
});

it('lets an admin confirm a pending order', function () {
    $admin = Admin::factory()->create();
    $order = Order::factory()->create();

    $this->actingAs($admin, 'admin')
        ->postJson("/api/admin/orders/{$order->id}/confirm")
        ->assertOk()
        ->assertJsonFragment(['status' => 'confirmed']);
});

it('lets an admin cancel an order', function () {
    $admin = Admin::factory()->create();
    $order = Order::factory()->confirmed()->create();

    $this->actingAs($admin, 'admin')
        ->postJson("/api/admin/orders/{$order->id}/cancel")
        ->assertOk()
        ->assertJsonFragment(['status' => 'cancelled']);
});
