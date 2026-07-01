<?php

declare(strict_types=1);

namespace App\Domain\Checkins\Enums;

enum CheckinStatus: string
{
    case Received = 'received';
    case HandedOver = 'handed_over';
}
