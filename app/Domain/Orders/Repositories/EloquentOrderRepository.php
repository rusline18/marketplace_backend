<?php

declare(strict_types=1);

namespace App\Domain\Orders\Repositories;

use App\Domain\Orders\Enums\OrderStatus;
use App\Domain\Orders\Models\Order;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;

class EloquentOrderRepository implements OrderRepositoryInterface
{
    /**
     * Paginate orders belonging to a specific buyer.
     *
     * @param  int  $userId  The id of the buyer.
     * @param  int  $perPage  Number of results per page.
     * @return LengthAwarePaginator<int, Order>
     */
    public function paginateForUser(int $userId, int $perPage = 15): LengthAwarePaginator
    {
        return Order::query()
            ->with('items')
            ->where('user_id', $userId)
            ->latest()
            ->paginate($perPage);
    }

    /**
     * Paginate orders, optionally filtered by status.
     *
     * @param  OrderStatus|null  $status  Status to filter by, or null for all statuses.
     * @param  int  $perPage  Number of results per page.
     * @return LengthAwarePaginator<int, Order>
     */
    public function paginateByStatus(?OrderStatus $status, int $perPage = 15): LengthAwarePaginator
    {
        return Order::query()
            ->with('items')
            ->when($status, fn (Builder $query) => $query->where('status', $status))
            ->latest()
            ->paginate($perPage);
    }

    /**
     * Persist the given order.
     *
     * @param  Order  $order  The order to save.
     * @return Order The saved order.
     */
    public function save(Order $order): Order
    {
        $order->save();

        return $order;
    }
}
