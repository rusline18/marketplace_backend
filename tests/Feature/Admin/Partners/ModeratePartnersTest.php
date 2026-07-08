<?php

use App\Domain\Partners\Models\Partner;
use App\Domain\Users\Models\Admin;

it('rejects a partner token on the admin partners queue', function () {
    $partner = Partner::factory()->approved()->create();

    $this->actingAs($partner, 'partner')
        ->getJson('/api/admin/partners')
        ->assertStatus(401);
});

it('lists partners pending review for an admin', function () {
    $admin = Admin::factory()->create();
    Partner::factory()->create();
    Partner::factory()->approved()->create();

    $this->actingAs($admin, 'admin')
        ->getJson('/api/admin/partners?status=pending')
        ->assertOk()
        ->assertJsonCount(1, 'data');
});

it('lets an admin approve a pending partner', function () {
    $admin = Admin::factory()->create();
    $partner = Partner::factory()->create();

    $this->actingAs($admin, 'admin')
        ->postJson("/api/admin/partners/{$partner->id}/approve")
        ->assertOk()
        ->assertJsonFragment(['status' => 'approved']);
});

it('lets an admin reject a pending partner', function () {
    $admin = Admin::factory()->create();
    $partner = Partner::factory()->create();

    $this->actingAs($admin, 'admin')
        ->postJson("/api/admin/partners/{$partner->id}/reject")
        ->assertOk()
        ->assertJsonFragment(['status' => 'rejected']);
});
