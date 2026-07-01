<?php

declare(strict_types=1);

namespace App\Http\Resources\Api\V1;

use App\Domain\Checkins\Models\Checkin;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin Checkin
 */
class CheckinResource extends JsonResource
{
    /**
     * Transform the checkin into an array for the public API.
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
            'status' => $this->status->value,
            'created_at' => $this->created_at,
        ];
    }
}
