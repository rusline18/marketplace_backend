<?php

use App\Domain\Orders\Actions\CancelOrderAction;
use App\Domain\Orders\Enums\OrderStatus;
use App\Domain\Orders\Models\Order;
use App\Domain\Orders\Policies\OrderPolicy;
use App\Models\User;

it('cancels a pending order', function () {
    $order = Order::factory()->create();

    $result = app(CancelOrderAction::class)->handle($order);

    expect($result->status)->toBe(OrderStatus::Cancelled);
});

it('cancels a confirmed order', function () {
    $order = Order::factory()->confirmed()->create();

    $result = app(CancelOrderAction::class)->handle($order);

    expect($result->status)->toBe(OrderStatus::Cancelled);
});

it('refuses to cancel an order that is already cancelled', function () {
    $order = Order::factory()->cancelled()->create();

    app(CancelOrderAction::class)->handle($order);
})->throws(InvalidArgumentException::class);

it('denies a buyer cancelling their own confirmed order via the policy', function () {
    $buyer = User::factory()->create();
    $order = Order::factory()->for($buyer)->confirmed()->create();

    expect((new OrderPolicy)->cancel($buyer, $order))->toBeFalse();
});
