<?php

declare(strict_types=1);

namespace App\Domain\Orders\Enums;

enum OrderStatus: string
{
    case Pending = 'pending';
    case Confirmed = 'confirmed';
    case Cancelled = 'cancelled';
}
