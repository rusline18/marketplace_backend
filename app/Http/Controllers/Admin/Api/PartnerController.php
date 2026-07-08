<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\Api;

use App\Domain\Partners\Actions\ApprovePartnerAction;
use App\Domain\Partners\Actions\RejectPartnerAction;
use App\Domain\Partners\Enums\PartnerStatus;
use App\Domain\Partners\Models\Partner;
use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\PartnerResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class PartnerController extends Controller
{
    /**
     * List partners for moderation, optionally filtered by status.
     *
     * @param  Request  $request  May contain a `status` query parameter.
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $status = $request->filled('status') ? PartnerStatus::from($request->string('status')->toString()) : null;

        $partners = Partner::query()
            ->when($status, fn ($query) => $query->where('status', $status))
            ->latest()
            ->paginate();

        return PartnerResource::collection($partners);
    }

    /**
     * Approve a partner account pending moderation.
     *
     * @param  Partner  $partner  The partner to approve.
     * @param  ApprovePartnerAction  $action  The action that performs the approval.
     */
    public function approve(Partner $partner, ApprovePartnerAction $action): PartnerResource
    {
        return new PartnerResource($action->handle($partner));
    }

    /**
     * Reject a partner account pending moderation.
     *
     * @param  Partner  $partner  The partner to reject.
     * @param  RejectPartnerAction  $action  The action that performs the rejection.
     */
    public function reject(Partner $partner, RejectPartnerAction $action): PartnerResource
    {
        return new PartnerResource($action->handle($partner));
    }
}
