<?php

declare(strict_types=1);

namespace App\Domain\Partners\Enums;

enum PartnerStatus: string
{
    case Pending = 'pending';
    case Approved = 'approved';
    case Rejected = 'rejected';
}
