<?php

use App\Domain\Partners\Actions\RejectPartnerAction;
use App\Domain\Partners\Enums\PartnerStatus;
use App\Domain\Partners\Models\Partner;

it('rejects a pending partner', function () {
    $partner = Partner::factory()->create();

    $result = app(RejectPartnerAction::class)->handle($partner);

    expect($result->status)->toBe(PartnerStatus::Rejected);
});

it('refuses to reject a partner that is not pending', function () {
    $partner = Partner::factory()->approved()->create();

    app(RejectPartnerAction::class)->handle($partner);
})->throws(InvalidArgumentException::class);
