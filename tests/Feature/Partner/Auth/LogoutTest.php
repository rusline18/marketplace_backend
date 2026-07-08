<?php

use App\Domain\Partners\Models\Partner;

it('revokes the current token on logout', function () {
    $partner = Partner::factory()->approved()->create();
    $token = $partner->createToken('partner-api')->plainTextToken;

    $this->withHeader('Authorization', "Bearer {$token}")
        ->postJson('/api/partner/logout')
        ->assertOk();

    expect($partner->tokens()->count())->toBe(0);
});
