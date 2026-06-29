<?php

use App\Domain\Listings\Models\Listing;
use App\Domain\Users\Models\Admin;
use App\Models\User;

it('rejects a sanctum user token on the admin listings queue', function () {
    $user = User::factory()->create();

    $this->actingAs($user, 'sanctum')
        ->getJson('/api/admin/listings')
        ->assertStatus(401);
});

it('lists listings pending review for an admin', function () {
    $admin = Admin::factory()->create();
    Listing::factory()->pendingReview()->create();
    Listing::factory()->active()->create();

    $this->actingAs($admin, 'admin')
        ->getJson('/api/admin/listings?status=pending_review')
        ->assertOk()
        ->assertJsonCount(1, 'data');
});

it('lets an admin approve a pending listing', function () {
    $admin = Admin::factory()->create();
    $listing = Listing::factory()->pendingReview()->create();

    $this->actingAs($admin, 'admin')
        ->postJson("/api/admin/listings/{$listing->id}/approve")
        ->assertOk()
        ->assertJsonFragment(['status' => 'active']);
});

it('lets an admin reject a pending listing', function () {
    $admin = Admin::factory()->create();
    $listing = Listing::factory()->pendingReview()->create();

    $this->actingAs($admin, 'admin')
        ->postJson("/api/admin/listings/{$listing->id}/reject")
        ->assertOk()
        ->assertJsonFragment(['status' => 'rejected']);
});
