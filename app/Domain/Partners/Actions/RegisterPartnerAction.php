<?php

declare(strict_types=1);

namespace App\Domain\Partners\Actions;

use App\Domain\Partners\Enums\PartnerStatus;
use App\Domain\Partners\Models\Partner;

class RegisterPartnerAction
{
    /**
     * Register a new partner account, pending admin approval.
     *
     * @param  array{name: string, email: string, password: string}  $data  Validated registration attributes.
     * @return Partner The newly created partner.
     */
    public function handle(array $data): Partner
    {
        return Partner::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => $data['password'],
            'status' => PartnerStatus::Pending,
        ]);
    }
}
