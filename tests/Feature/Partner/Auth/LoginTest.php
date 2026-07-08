<?php

use App\Domain\Partners\Models\Partner;

it('rejects login while the partner account is pending approval', function () {
    Partner::factory()->create([
        'email' => 'partner@example.com',
        'password' => 'password123',
    ]);

    $this->postJson('/api/partner/login', [
        'email' => 'partner@example.com',
        'password' => 'password123',
    ])
        ->assertStatus(422)
        ->assertJsonFragment(['email' => ['Your partner account is pending approval.']]);
});

it('logs in an approved partner with valid credentials', function () {
    Partner::factory()->approved()->create([
        'email' => 'partner@example.com',
        'password' => 'password123',
    ]);

    $this->postJson('/api/partner/login', [
        'email' => 'partner@example.com',
        'password' => 'password123',
    ])
        ->assertOk()
        ->assertJsonStructure(['partner', 'token']);
});

it('rejects login with an incorrect password', function () {
    Partner::factory()->approved()->create([
        'email' => 'partner@example.com',
        'password' => 'password123',
    ]);

    $this->postJson('/api/partner/login', [
        'email' => 'partner@example.com',
        'password' => 'wrong-password',
    ])->assertStatus(422);
});
