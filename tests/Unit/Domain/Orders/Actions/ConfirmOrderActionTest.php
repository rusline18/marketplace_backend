<?php

use App\Domain\Orders\Actions\ConfirmOrderAction;
use App\Domain\Orders\Enums\OrderStatus;
use App\Domain\Orders\Models\Order;

it('confirms a pending order', function () {
    $order = Order::factory()->create();

    $result = app(ConfirmOrderAction::class)->handle($order);

    expect($result->status)->toBe(OrderStatus::Confirmed)
        ->and($order->statusHistories()->latest()->first()->to_status)->toBe(OrderStatus::Confirmed);
});

it('refuses to confirm an order that is not pending', function () {
    $order = Order::factory()->confirmed()->create();

    app(ConfirmOrderAction::class)->handle($order);
})->throws(InvalidArgumentException::class);
