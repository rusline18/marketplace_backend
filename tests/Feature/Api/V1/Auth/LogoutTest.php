<?php

use App\Models\User;

it('revokes the current token on logout', function () {
    $user = User::factory()->create();
    $token = $user->createToken('api')->plainTextToken;

    $this->withHeader('Authorization', "Bearer {$token}")
        ->postJson('/api/v1/logout')
        ->assertOk();

    expect($user->tokens()->count())->toBe(0);
});
