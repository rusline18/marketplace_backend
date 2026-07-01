<?php

use App\Domain\Checkins\Actions\RecordCheckinAction;
use App\Domain\Checkins\Enums\CheckinStatus;
use App\Domain\Checkins\Repositories\CheckinRepositoryInterface;
use App\Domain\Checkins\Repositories\EloquentCheckinRepository;
use App\Domain\Listings\Models\Listing;
use App\Domain\Orders\Models\Order;
use App\Domain\Orders\Models\OrderItem;
use App\Models\User;

it('records a received checkin when the buyer checks in', function () {
    $buyer = User::factory()->create();
    $seller = User::factory()->create();
    $order = Order::factory()->for($buyer)->create();
    $listing = Listing::factory()->for($seller)->active()->create();
    OrderItem::factory()->for($order)->create(['listing_id' => $listing->id]);

    $checkin = app(RecordCheckinAction::class)->handle($buyer->id, $order, ['listing_id' => $listing->id]);

    expect($checkin->status)->toBe(CheckinStatus::Received)
        ->and($checkin->user_id)->toBe($buyer->id)
        ->and($checkin->order_id)->toBe($order->id);
});

it('records a handed-over checkin when the seller checks in', function () {
    $buyer = User::factory()->create();
    $seller = User::factory()->create();
    $order = Order::factory()->for($buyer)->create();
    $listing = Listing::factory()->for($seller)->active()->create();
    OrderItem::factory()->for($order)->create(['listing_id' => $listing->id]);

    $checkin = app(RecordCheckinAction::class)->handle($seller->id, $order, ['listing_id' => $listing->id]);

    expect($checkin->status)->toBe(CheckinStatus::HandedOver);
});

it('refuses to check in a listing that is not part of the order', function () {
    $buyer = User::factory()->create();
    $order = Order::factory()->for($buyer)->create();
    $listing = Listing::factory()->active()->create();

    app(RecordCheckinAction::class)->handle($buyer->id, $order, ['listing_id' => $listing->id]);
})->throws(InvalidArgumentException::class);

it('depends on the bound repository implementation', function () {
    expect(app(CheckinRepositoryInterface::class))->toBeInstanceOf(
        EloquentCheckinRepository::class,
    );
});
