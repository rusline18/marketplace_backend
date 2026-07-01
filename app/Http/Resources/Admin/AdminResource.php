<?php

declare(strict_types=1);

namespace App\Http\Resources\Admin;

use App\Domain\Users\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin Admin
 */
class AdminResource extends JsonResource
{
    /**
     * Transform the admin into an array for the admin API.
     *
     * @param  Request  $request  The incoming request.
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
        ];
    }
}
