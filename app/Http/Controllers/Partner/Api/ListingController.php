<?php

declare(strict_types=1);

namespace App\Http\Controllers\Partner\Api;

use App\Domain\Listings\Actions\ArchiveListingAction;
use App\Domain\Listings\Actions\CreateListingAction;
use App\Domain\Listings\Actions\PublishListingAction;
use App\Domain\Listings\Actions\UpdateListingAction;
use App\Domain\Listings\Enums\ListingStatus;
use App\Domain\Listings\Models\Listing;
use App\Domain\Listings\Repositories\ListingRepositoryInterface;
use App\Http\Controllers\Controller;
use App\Http\Requests\Partner\Api\StoreListingRequest;
use App\Http\Requests\Partner\Api\UpdateListingRequest;
use App\Http\Resources\Partner\ListingResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ListingController extends Controller
{
    public function __construct(
        private readonly ListingRepositoryInterface $listings,
    ) {}

    /**
     * List the authenticated partner's own listings, optionally filtered by status.
     *
     * @param  Request  $request  May contain a `status` query parameter.
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $status = $request->filled('status') ? ListingStatus::from($request->string('status')->toString()) : null;

        $listings = $this->listings->paginateOwnedBy($request->user()->id, $status);

        return ListingResource::collection($listings);
    }

    /**
     * Create a new draft listing owned by the authenticated partner.
     *
     * @param  StoreListingRequest  $request  The validated listing attributes.
     * @param  CreateListingAction  $action  The action that creates the listing.
     */
    public function store(StoreListingRequest $request, CreateListingAction $action): JsonResponse
    {
        $listing = $action->handle($request->user()->id, $request->validated());

        return (new ListingResource($listing))->response()->setStatusCode(201);
    }

    /**
     * Update an existing listing owned by the authenticated partner.
     *
     * @param  UpdateListingRequest  $request  The validated listing attributes.
     * @param  Listing  $listing  The listing to update.
     * @param  UpdateListingAction  $action  The action that performs the update.
     */
    public function update(UpdateListingRequest $request, Listing $listing, UpdateListingAction $action): ListingResource
    {
        $this->authorize('update', $listing);

        return new ListingResource($action->handle($listing, $request->validated()));
    }

    /**
     * Submit a draft listing for moderation.
     *
     * @param  Request  $request  The incoming request.
     * @param  Listing  $listing  The draft listing to submit.
     * @param  PublishListingAction  $action  The action that performs the submission.
     */
    public function publish(Request $request, Listing $listing, PublishListingAction $action): ListingResource
    {
        $this->authorize('update', $listing);

        return new ListingResource($action->handle($listing));
    }

    /**
     * Archive an active listing owned by the authenticated partner.
     *
     * @param  Request  $request  The incoming request.
     * @param  Listing  $listing  The active listing to archive.
     * @param  ArchiveListingAction  $action  The action that performs the archival.
     */
    public function archive(Request $request, Listing $listing, ArchiveListingAction $action): ListingResource
    {
        $this->authorize('archive', $listing);

        return new ListingResource($action->handle($listing));
    }
}
