<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\Api;

use App\Domain\Listings\Actions\ApproveListingAction;
use App\Domain\Listings\Actions\RejectListingAction;
use App\Domain\Listings\Enums\ListingStatus;
use App\Domain\Listings\Models\Listing;
use App\Domain\Listings\Repositories\ListingRepositoryInterface;
use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\ListingResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ListingController extends Controller
{
    public function __construct(
        private readonly ListingRepositoryInterface $listings,
    ) {}

    /**
     * List listings for moderation, optionally filtered by status.
     *
     * @param  Request  $request  May contain a `status` query parameter.
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $status = $request->filled('status') ? ListingStatus::from($request->string('status')->toString()) : null;

        return ListingResource::collection($this->listings->paginateByStatus($status));
    }

    /**
     * Approve a listing pending moderation.
     *
     * @param  Listing  $listing  The listing to approve.
     * @param  ApproveListingAction  $action  The action that performs the approval.
     */
    public function approve(Listing $listing, ApproveListingAction $action): ListingResource
    {
        return new ListingResource($action->handle($listing));
    }

    /**
     * Reject a listing pending moderation.
     *
     * @param  Listing  $listing  The listing to reject.
     * @param  RejectListingAction  $action  The action that performs the rejection.
     */
    public function reject(Listing $listing, RejectListingAction $action): ListingResource
    {
        return new ListingResource($action->handle($listing));
    }
}
