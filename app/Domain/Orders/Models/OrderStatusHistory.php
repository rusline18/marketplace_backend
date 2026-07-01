<?php

declare(strict_types=1);

namespace App\Domain\Orders\Models;

use App\Domain\Orders\Enums\OrderStatus;
use Database\Factories\OrderStatusHistoryFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['order_id', 'from_status', 'to_status'])]
class OrderStatusHistory extends Model
{
    /** @use HasFactory<OrderStatusHistoryFactory> */
    use HasFactory;

    protected $table = 'order_status_history';

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'from_status' => OrderStatus::class,
            'to_status' => OrderStatus::class,
        ];
    }

    /**
     * Get the order this history entry belongs to.
     *
     * @return BelongsTo<Order, OrderStatusHistory>
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Get the factory instance used to create this model.
     */
    protected static function newFactory(): OrderStatusHistoryFactory
    {
        return OrderStatusHistoryFactory::new();
    }
}
