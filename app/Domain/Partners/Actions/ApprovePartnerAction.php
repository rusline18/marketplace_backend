<?php

declare(strict_types=1);

namespace App\Domain\Partners\Actions;

use App\Domain\Partners\Enums\PartnerStatus;
use App\Domain\Partners\Models\Partner;
use InvalidArgumentException;

class ApprovePartnerAction
{
    /**
     * Admin approves a partner account pending moderation.
     *
     * @param  Partner  $partner  The partner currently pending review.
     * @return Partner The partner with status set to approved.
     *
     * @throws InvalidArgumentException If the partner is not pending review.
     */
    public function handle(Partner $partner): Partner
    {
        if ($partner->status !== PartnerStatus::Pending) {
            throw new InvalidArgumentException('Only pending partners can be approved.');
        }

        $partner->status = PartnerStatus::Approved;
        $partner->save();

        return $partner;
    }
}
