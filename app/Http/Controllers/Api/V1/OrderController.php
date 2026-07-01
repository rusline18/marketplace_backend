<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Domain\Orders\Actions\CancelOrderAction;
use App\Domain\Orders\Actions\CreateOrderAction;
use App\Domain\Orders\Models\Order;
use App\Domain\Orders\Repositories\OrderRepositoryInterface;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\StoreOrderRequest;
use App\Http\Resources\Api\V1\OrderResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class OrderController extends Controller
{
    public function __construct(
        private readonly OrderRepositoryInterface $orders,
    ) {}

    /**
     * List the authenticated buyer's order history.
     *
     * @param  Request  $request  The incoming request.
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        return OrderResource::collection($this->orders->paginateForUser($request->user()->id));
    }

    /**
     * Show a single order owned by the authenticated buyer.
     *
     * @param  Order  $order  The order to show.
     */
    public function show(Order $order): OrderResource
    {
        $this->authorize('view', $order);

        return new OrderResource($order);
    }

    /**
     * Place a new order for the authenticated buyer.
     *
     * @param  StoreOrderRequest  $request  The validated order attributes.
     * @param  CreateOrderAction  $action  The action that creates the order.
     */
    public function store(StoreOrderRequest $request, CreateOrderAction $action): JsonResponse
    {
        $order = $action->handle($request->user()->id, $request->validated());

        return (new OrderResource($order))->response()->setStatusCode(201);
    }

    /**
     * Cancel a pending order owned by the authenticated buyer.
     *
     * @param  Order  $order  The order to cancel.
     * @param  CancelOrderAction  $action  The action that performs the cancellation.
     */
    public function cancel(Order $order, CancelOrderAction $action): OrderResource
    {
        $this->authorize('cancel', $order);

        return new OrderResource($action->handle($order));
    }
}
