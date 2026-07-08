<?php

use App\Domain\Listings\Models\Listing;
use App\Domain\Orders\Actions\CreateOrderAction;
use App\Domain\Orders\Enums\OrderStatus;
use App\Domain\Orders\Repositories\EloquentOrderRepository;
use App\Domain\Orders\Repositories\OrderRepositoryInterface;
use App\Models\User;

it('creates a pending order with a total computed from the listing prices', function () {
    $buyer = User::factory()->create();
    $listing = Listing::factory()->active()->create(['price' => 40]);

    $order = app(CreateOrderAction::class)->handle($buyer->id, [
        'items' => [
            ['listing_id' => $listing->id, 'quantity' => 3],
        ],
    ]);

    expect($order->status)->toBe(OrderStatus::Pending)
        ->and((float) $order->total)->toBe(120.0)
        ->and($order->items)->toHaveCount(1);
});

it('refuses to order a listing that is not active', function () {
    $buyer = User::factory()->create();
    $listing = Listing::factory()->create();

    app(CreateOrderAction::class)->handle($buyer->id, [
        'items' => [
            ['listing_id' => $listing->id, 'quantity' => 1],
        ],
    ]);
})->throws(InvalidArgumentException::class);

it('depends on the bound repository implementation', function () {
    expect(app(OrderRepositoryInterface::class))->toBeInstanceOf(
        EloquentOrderRepository::class,
    );
});
