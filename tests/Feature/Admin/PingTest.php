<?php

use App\Domain\Users\Models\Admin;
use App\Models\User;

it('responds to a public admin ping', function () {
    $this->getJson('/api/admin/ping')
        ->assertOk()
        ->assertJson(['message' => 'pong']);
});

it('rejects an unauthenticated request to a protected admin route with a JSON envelope', function () {
    $this->getJson('/api/admin/me')
        ->assertStatus(401)
        ->assertJsonStructure(['message', 'errors']);
});

it('allows an authenticated admin to reach a protected admin route', function () {
    $admin = Admin::factory()->create();

    $this->actingAs($admin, 'admin')
        ->getJson('/api/admin/me')
        ->assertOk()
        ->assertJsonFragment(['id' => $admin->id]);
});

it('rejects a public-api user token on the admin guard', function () {
    $user = User::factory()->create();

    $this->actingAs($user, 'sanctum')
        ->getJson('/api/admin/me')
        ->assertStatus(401);
});
