<?php

use App\Domain\Partners\Models\Partner;

it('rejects an unauthenticated request to view the partner profile', function () {
    $this->getJson('/api/partner/me')->assertStatus(401);
});

it('returns the authenticated partner profile', function () {
    $partner = Partner::factory()->approved()->create(['name' => 'Partner One']);

    $this->actingAs($partner, 'partner')
        ->getJson('/api/partner/me')
        ->assertOk()
        ->assertJsonFragment(['name' => 'Partner One', 'email' => $partner->email]);
});
