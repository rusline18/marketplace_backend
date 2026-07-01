<?php

declare(strict_types=1);

namespace App\Http\Resources\Api\V1;

use App\Domain\Orders\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin OrderItem
 */
class OrderItemResource extends JsonResource
{
    /**
     * Transform the order item into an array for the public API.
     *
     * @param  Request  $request  The incoming request.
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'listing_id' => $this->listing_id,
            'quantity' => $this->quantity,
            'unit_price' => (float) $this->unit_price,
        ];
    }
}
