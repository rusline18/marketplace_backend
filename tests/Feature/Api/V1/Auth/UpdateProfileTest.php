<?php

use App\Models\User;

it('rejects an unauthenticated request to update the profile', function () {
    $this->patchJson('/api/v1/me', ['name' => 'New Name'])->assertStatus(401);
});

it('lets an authenticated buyer update their profile', function () {
    $user = User::factory()->create();

    $this->actingAs($user, 'sanctum')
        ->patchJson('/api/v1/me', ['name' => 'New Name'])
        ->assertOk()
        ->assertJsonFragment(['name' => 'New Name']);

    expect($user->fresh()->name)->toBe('New Name');
});

it('rejects updating to an email already taken by another buyer', function () {
    User::factory()->create(['email' => 'taken@example.com']);
    $user = User::factory()->create();

    $this->actingAs($user, 'sanctum')
        ->patchJson('/api/v1/me', ['email' => 'taken@example.com'])
        ->assertStatus(422);
});
