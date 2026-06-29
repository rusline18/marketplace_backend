<?php

use App\Models\User;

it('responds to a public ping', function () {
    $this->getJson('/api/v1/ping')
        ->assertOk()
        ->assertJson(['message' => 'pong']);
});

it('rejects an unauthenticated request to a protected v1 route with a JSON envelope', function () {
    $this->getJson('/api/v1/me')
        ->assertStatus(401)
        ->assertJsonStructure(['message', 'errors']);
});

it('allows an authenticated user to reach a protected v1 route', function () {
    $user = User::factory()->create();

    $this->actingAs($user, 'sanctum')
        ->getJson('/api/v1/me')
        ->assertOk()
        ->assertJsonFragment(['id' => $user->id]);
});
