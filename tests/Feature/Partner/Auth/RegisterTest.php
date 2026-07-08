<?php

use App\Domain\Partners\Enums\PartnerStatus;
use App\Domain\Partners\Models\Partner;

it('registers a new partner as pending, without a token', function () {
    $this->postJson('/api/partner/register', [
        'name' => 'Jane Partner',
        'email' => 'jane@example.com',
        'password' => 'password123',
        'password_confirmation' => 'password123',
    ])
        ->assertCreated()
        ->assertJsonFragment(['email' => 'jane@example.com', 'status' => 'pending'])
        ->assertJsonMissingPath('token');

    expect(Partner::where('email', 'jane@example.com')->first()->status)->toBe(PartnerStatus::Pending);
});

it('rejects registration with a duplicate email', function () {
    Partner::factory()->create(['email' => 'jane@example.com']);

    $this->postJson('/api/partner/register', [
        'name' => 'Jane Partner',
        'email' => 'jane@example.com',
        'password' => 'password123',
        'password_confirmation' => 'password123',
    ])->assertStatus(422);
});

it('rejects registration with missing fields', function () {
    $this->postJson('/api/partner/register', [])->assertStatus(422);
});
