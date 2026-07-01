<?php

declare(strict_types=1);

namespace App\Http\Resources\Admin;

use App\Domain\Orders\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin OrderItem
 */
class OrderItemResource extends JsonResource
{
    /**
     * Transform the order item into an array for the admin API.
     *
     * @param  Request  $request  The incoming request.
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'order_id' => $this->order_id,
            'listing_id' => $this->listing_id,
            'quantity' => $this->quantity,
            'unit_price' => (float) $this->unit_price,
        ];
    }
}
