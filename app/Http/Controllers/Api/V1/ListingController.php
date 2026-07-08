<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1;

use App\Domain\Listings\Enums\ListingStatus;
use App\Domain\Listings\Models\Listing;
use App\Domain\Listings\Repositories\ListingRepositoryInterface;
use App\Http\Controllers\Controller;
use App\Http\Resources\Api\V1\ListingResource;
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
     * Show a single active listing.
     *
     * @param  Listing  $listing  The listing to show.
     */
    public function show(Listing $listing): ListingResource
    {
        abort_unless($listing->status === ListingStatus::Active, 404);

        return new ListingResource($listing);
    }
}
