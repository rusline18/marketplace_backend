<?php

use App\Domain\Partners\Actions\ApprovePartnerAction;
use App\Domain\Partners\Enums\PartnerStatus;
use App\Domain\Partners\Models\Partner;

it('approves a pending partner', function () {
    $partner = Partner::factory()->create();

    $result = app(ApprovePartnerAction::class)->handle($partner);

    expect($result->status)->toBe(PartnerStatus::Approved);
});

it('refuses to approve a partner that is not pending', function () {
    $partner = Partner::factory()->approved()->create();

    app(ApprovePartnerAction::class)->handle($partner);
})->throws(InvalidArgumentException::class);
