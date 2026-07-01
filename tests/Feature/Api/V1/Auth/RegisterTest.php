<?php

use App\Models\User;

it('registers a new buyer and issues a token', function () {
    $this->postJson('/api/v1/register', [
        'name' => 'Jane Buyer',
        'email' => 'jane@example.com',
        'password' => 'password123',
        'password_confirmation' => 'password123',
    ])
        ->assertCreated()
        ->assertJsonFragment(['email' => 'jane@example.com'])
        ->assertJsonStructure(['user', 'token']);

    expect(User::where('email', 'jane@example.com')->exists())->toBeTrue();
});

it('rejects registration with a duplicate email', function () {
    User::factory()->create(['email' => 'jane@example.com']);

    $this->postJson('/api/v1/register', [
        'name' => 'Jane Buyer',
        'email' => 'jane@example.com',
        'password' => 'password123',
        'password_confirmation' => 'password123',
    ])->assertStatus(422);
});

it('rejects registration with missing fields', function () {
    $this->postJson('/api/v1/register', [])->assertStatus(422);
});
