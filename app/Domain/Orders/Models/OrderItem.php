<?php

declare(strict_types=1);

namespace App\Domain\Orders\Models;

use App\Domain\Listings\Models\Listing;
use Database\Factories\OrderItemFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['order_id', 'listing_id', 'quantity', 'unit_price'])]
class OrderItem extends Model
{
    /** @use HasFactory<OrderItemFactory> */
    use HasFactory;

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'unit_price' => 'decimal:2',
        ];
    }

    /**
     * Get the order this item belongs to.
     *
     * @return BelongsTo<Order, OrderItem>
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Get the listing being purchased.
     *
     * @return BelongsTo<Listing, OrderItem>
     */
    public function listing(): BelongsTo
    {
        return $this->belongsTo(Listing::class);
    }

    /**
     * Get the factory instance used to create this model.
     */
    protected static function newFactory(): OrderItemFactory
    {
        return OrderItemFactory::new();
    }
}
