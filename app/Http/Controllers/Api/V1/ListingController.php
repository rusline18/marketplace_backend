<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Domain\Listings\Actions\ArchiveListingAction;
use App\Domain\Listings\Actions\CreateListingAction;
use App\Domain\Listings\Actions\PublishListingAction;
use App\Domain\Listings\Actions\UpdateListingAction;
use App\Domain\Listings\Enums\ListingStatus;
use App\Domain\Listings\Models\Listing;
use App\Domain\Listings\Repositories\ListingRepositoryInterface;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\V1\StoreListingRequest;
use App\Http\Requests\Api\V1\UpdateListingRequest;
use App\Http\Resources\Api\V1\ListingResource;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ListingController extends Controller
{
    public function __construct(
        private readonly ListingRepositoryInterface $listings,
    ) {}

    /**
     * List active listings, optionally filtered by search term and category.
     *
     * @param  Request  $request  May contain `search` and `category_id` query parameters.
     * @return AnonymousResourceCollection
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $listings = $this->listings->paginateActive([
            'search' => $request->string('search')->toString() ?: null,
            'category_id' => $request->integer('category_id') ?: null,
        ]);

        return ListingResource::collection($listings);
    }

    /**
     * Show a single listing if it is active or owned by the authenticated user.
     *
     * @param  Listing  $listing  The listing to show.
     * @return ListingResource
     *
     * @throws HttpResponseException If the listing is not visible to the requester.
     */
    public function show(Listing $listing): ListingResource
    {
        abort_unless(
            $listing->status === ListingStatus::Active
                || (auth('sanctum')->check() && auth('sanctum')->id() === $listing->user_id),
            404,
        );

        return new ListingResource($listing);
    }

    /**
     * Create a new draft listing owned by the authenticated user.
     *
     * @param  StoreListingRequest  $request  The validated listing attributes.
     * @param  CreateListingAction  $action  The action that creates the listing.
     * @return JsonResponse
     */
    public function store(StoreListingRequest $request, CreateListingAction $action): JsonResponse
    {
        $listing = $action->handle($request->user()->id, $request->validated());

        return (new ListingResource($listing))->response()->setStatusCode(201);
    }

    /**
     * Update an existing listing owned by the authenticated user.
     *
     * @param  UpdateListingRequest  $request  The validated listing attributes.
     * @param  Listing  $listing  The listing to update.
     * @param  UpdateListingAction  $action  The action that performs the update.
     * @return ListingResource
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
     * @param  Listing  $listing  The draft listing to publish.
     * @param  PublishListingAction  $action  The action that performs the submission.
     * @return ListingResource
     */
    public function publish(Request $request, Listing $listing, PublishListingAction $action): ListingResource
    {
        $this->authorize('update', $listing);

        return new ListingResource($action->handle($listing));
    }

    /**
     * Archive an active listing owned by the authenticated user.
     *
     * @param  Request  $request  The incoming request.
     * @param  Listing  $listing  The active listing to archive.
     * @param  ArchiveListingAction  $action  The action that performs the archival.
     * @return ListingResource
     */
    public function archive(Request $request, Listing $listing, ArchiveListingAction $action): ListingResource
    {
        $this->authorize('archive', $listing);

        return new ListingResource($action->handle($listing));
    }
}
