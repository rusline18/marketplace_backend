<?php

declare(strict_types=1);

namespace App\Http\Resources\Api\V1;

use App\Domain\Orders\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin Order
 */
class OrderResource extends JsonResource
{
    /**
     * Transform the order into an array for the public API.
     *
     * @param  Request  $request  The incoming request.
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'status' => $this->status->value,
            'total' => (float) $this->total,
            'items' => OrderItemResource::collection($this->items),
            'created_at' => $this->created_at,
        ];
    }
}
