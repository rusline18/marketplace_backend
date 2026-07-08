<?php

use App\Domain\Listings\Models\Category;
use App\Domain\Partners\Models\Partner;

it('rejects an unauthenticated request to create a listing', function () {
    $this->postJson('/api/partner/listings', [])
        ->assertStatus(401);
});

it('lets an approved partner create a draft listing', function () {
    $partner = Partner::factory()->approved()->create();
    $category = Category::factory()->create();

    $this->actingAs($partner, 'partner')
        ->postJson('/api/partner/listings', [
            'category_id' => $category->id,
            'title' => 'My listing',
            'description' => 'A description',
            'price' => 12.5,
        ])
        ->assertCreated()
        ->assertJsonFragment(['title' => 'My listing', 'status' => 'draft']);
});
