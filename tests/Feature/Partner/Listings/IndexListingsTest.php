<?php

use App\Domain\Listings\Models\Listing;
use App\Domain\Partners\Models\Partner;
use App\Models\User;

it('rejects a sanctum user token on the partner listings queue', function () {
    $user = User::factory()->create();

    $this->actingAs($user, 'sanctum')
        ->getJson('/api/partner/listings')
        ->assertStatus(401);
});

it('lists only the authenticated partner\'s own listings, across all statuses', function () {
    $partner = Partner::factory()->approved()->create();
    $other = Partner::factory()->approved()->create();

    Listing::factory()->for($partner)->create();
    Listing::factory()->for($partner)->active()->create();
    Listing::factory()->for($other)->create();

    $this->actingAs($partner, 'partner')
        ->getJson('/api/partner/listings')
        ->assertOk()
        ->assertJsonCount(2, 'data');
});

it('filters the partner\'s listings by status', function () {
    $partner = Partner::factory()->approved()->create();

    Listing::factory()->for($partner)->create();
    Listing::factory()->for($partner)->active()->create();

    $this->actingAs($partner, 'partner')
        ->getJson('/api/partner/listings?status=active')
        ->assertOk()
        ->assertJsonCount(1, 'data');
});
