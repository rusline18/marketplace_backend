<?php

use App\Domain\Users\Models\Admin;

it('rejects an unauthenticated request to view the admin profile', function () {
    $this->getJson('/api/admin/me')->assertStatus(401);
});

it('returns the authenticated admin profile', function () {
    $admin = Admin::factory()->create(['name' => 'Staff Member']);

    $this->actingAs($admin, 'admin')
        ->getJson('/api/admin/me')
        ->assertOk()
        ->assertJsonFragment(['name' => 'Staff Member', 'email' => $admin->email]);
});
