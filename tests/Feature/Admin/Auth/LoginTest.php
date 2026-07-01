<?php

use App\Domain\Users\Models\Admin;

it('logs in a staff member with valid credentials', function () {
    Admin::factory()->create([
        'email' => 'staff@example.com',
        'password' => 'password123',
    ]);

    $this->postJson('/api/admin/login', [
        'email' => 'staff@example.com',
        'password' => 'password123',
    ])
        ->assertOk()
        ->assertJsonStructure(['admin', 'token']);
});

it('rejects login with an incorrect password', function () {
    Admin::factory()->create([
        'email' => 'staff@example.com',
        'password' => 'password123',
    ]);

    $this->postJson('/api/admin/login', [
        'email' => 'staff@example.com',
        'password' => 'wrong-password',
    ])->assertStatus(422);
});
