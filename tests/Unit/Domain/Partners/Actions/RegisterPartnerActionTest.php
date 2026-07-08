<?php

use App\Domain\Partners\Actions\RegisterPartnerAction;
use App\Domain\Partners\Enums\PartnerStatus;
use App\Domain\Partners\Models\Partner;
use Illuminate\Support\Facades\Hash;

it('creates a pending partner with a hashed password', function () {
    $partner = app(RegisterPartnerAction::class)->handle([
        'name' => 'Jane Partner',
        'email' => 'jane@example.com',
        'password' => 'password123',
    ]);

    expect($partner)->toBeInstanceOf(Partner::class)
        ->and($partner->email)->toBe('jane@example.com')
        ->and($partner->status)->toBe(PartnerStatus::Pending)
        ->and(Hash::check('password123', $partner->password))->toBeTrue();
});
