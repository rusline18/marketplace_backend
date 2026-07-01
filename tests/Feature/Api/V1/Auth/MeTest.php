<?php

use App\Models\User;

it('rejects an unauthenticated request to view the profile', function () {
    $this->getJson('/api/v1/me')->assertStatus(401);
});

it('returns the authenticated buyer profile', function () {
    $user = User::factory()->create(['name' => 'Jane Buyer']);

    $this->actingAs($user, 'sanctum')
        ->getJson('/api/v1/me')
        ->assertOk()
        ->assertJsonFragment(['name' => 'Jane Buyer', 'email' => $user->email]);
});
