<?php

use App\Models\User;

it('logs in a buyer with valid credentials', function () {
    User::factory()->create([
        'email' => 'jane@example.com',
        'password' => 'password123',
    ]);

    $this->postJson('/api/v1/login', [
        'email' => 'jane@example.com',
        'password' => 'password123',
    ])
        ->assertOk()
        ->assertJsonStructure(['user', 'token']);
});

it('rejects login with an incorrect password', function () {
    User::factory()->create([
        'email' => 'jane@example.com',
        'password' => 'password123',
    ]);

    $this->postJson('/api/v1/login', [
        'email' => 'jane@example.com',
        'password' => 'wrong-password',
    ])->assertStatus(422);
});

it('rejects login for an unknown email', function () {
    $this->postJson('/api/v1/login', [
        'email' => 'missing@example.com',
        'password' => 'password123',
    ])->assertStatus(422);
});
