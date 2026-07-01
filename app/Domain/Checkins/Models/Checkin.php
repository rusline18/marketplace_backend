<?php

declare(strict_types=1);

namespace App\Domain\Checkins\Models;

use App\Domain\Checkins\Enums\CheckinStatus;
use App\Domain\Listings\Models\Listing;
use App\Domain\Orders\Models\Order;
use App\Models\User;
use Database\Factories\CheckinFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['order_id', 'listing_id', 'user_id', 'status'])]
class Checkin extends Model
{
    /** @use HasFactory<CheckinFactory> */
    use HasFactory;

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'status' => CheckinStatus::class,
        ];
    }

    /**
     * Get the order this checkin belongs to.
     *
     * @return BelongsTo<Order, Checkin>
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Get the listing this checkin confirms.
     *
     * @return BelongsTo<Listing, Checkin>
     */
    public function listing(): BelongsTo
    {
        return $this->belongsTo(Listing::class);
    }

    /**
     * Get the user who recorded the checkin.
     *
     * @return BelongsTo<User, Checkin>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the factory instance used to create this model.
     */
    protected static function newFactory(): CheckinFactory
    {
        return CheckinFactory::new();
    }
}
