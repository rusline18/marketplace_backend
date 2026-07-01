<?php

use App\Domain\Users\Models\Admin;

it('revokes the current token on logout', function () {
    $admin = Admin::factory()->create();
    $token = $admin->createToken('admin-api')->plainTextToken;

    $this->withHeader('Authorization', "Bearer {$token}")
        ->postJson('/api/admin/logout')
        ->assertOk();

    expect($admin->tokens()->count())->toBe(0);
});
