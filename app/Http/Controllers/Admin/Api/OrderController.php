<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\Api;

use App\Domain\Orders\Actions\CancelOrderAction;
use App\Domain\Orders\Actions\ConfirmOrderAction;
use App\Domain\Orders\Enums\OrderStatus;
use App\Domain\Orders\Models\Order;
use App\Domain\Orders\Repositories\OrderRepositoryInterface;
use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\OrderResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class OrderController extends Controller
{
    public function __construct(
        private readonly OrderRepositoryInterface $orders,
    ) {}

    /**
     * List orders, optionally filtered by status.
     *
     * @param  Request  $request  May contain a `status` query parameter.
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $status = $request->filled('status') ? OrderStatus::from($request->string('status')->toString()) : null;

        return OrderResource::collection($this->orders->paginateByStatus($status));
    }

    /**
     * Show a single order's details.
     *
     * @param  Order  $order  The order to show.
     */
    public function show(Order $order): OrderResource
    {
        return new OrderResource($order);
    }

    /**
     * Confirm a pending order.
     *
     * @param  Order  $order  The order to confirm.
     * @param  ConfirmOrderAction  $action  The action that performs the confirmation.
     */
    public function confirm(Order $order, ConfirmOrderAction $action): OrderResource
    {
        return new OrderResource($action->handle($order));
    }

    /**
     * Cancel an order.
     *
     * @param  Order  $order  The order to cancel.
     * @param  CancelOrderAction  $action  The action that performs the cancellation.
     */
    public function cancel(Order $order, CancelOrderAction $action): OrderResource
    {
        return new OrderResource($action->handle($order));
    }
}
