<?php

use App\Domain\Listings\Models\Category;
use App\Models\User;

it('rejects an unauthenticated request to create a listing', function () {
    $this->postJson('/api/v1/listings', [])
        ->assertStatus(401);
});

it('lets an authenticated user create a draft listing', function () {
    $user = User::factory()->create();
    $category = Category::factory()->create();

    $this->actingAs($user, 'sanctum')
        ->postJson('/api/v1/listings', [
            'category_id' => $category->id,
            'title' => 'My listing',
            'description' => 'A description',
            'price' => 12.5,
        ])
        ->assertCreated()
        ->assertJsonFragment(['title' => 'My listing', 'status' => 'draft']);
});
