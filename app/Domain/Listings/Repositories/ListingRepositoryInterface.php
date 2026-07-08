<?php

declare(strict_types=1);

namespace App\Domain\Listings\Repositories;

use App\Domain\Listings\Enums\ListingStatus;
use App\Domain\Listings\Models\Listing;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface ListingRepositoryInterface
{
    /**
     * Paginate active listings, optionally filtered by search term and category.
     *
     * @param  array{search?: string, category_id?: int}  $filters  Optional search and category filters.
     * @param  int  $perPage  Number of results per page.
     * @return LengthAwarePaginator<int, Listing>
     */
    public function paginateActive(array $filters, int $perPage = 15): LengthAwarePaginator;

    /**
     * Find an active listing by id.
     *
     * @param  int  $id  The listing id.
     */
    public function findActive(int $id): ?Listing;

    /**
     * Find a listing by id, scoped to a specific owner.
     *
     * @param  int  $id  The listing id.
     * @param  int  $partnerId  The id of the partner expected to own the listing.
     */
    public function findOwnedBy(int $id, int $partnerId): ?Listing;

    /**
     * Paginate listings, optionally filtered by status.
     *
     * @param  ListingStatus|null  $status  Status to filter by, or null for all statuses.
     * @param  int  $perPage  Number of results per page.
     * @return LengthAwarePaginator<int, Listing>
     */
    public function paginateByStatus(?ListingStatus $status, int $perPage = 15): LengthAwarePaginator;

    /**
     * Paginate listings owned by a specific partner, optionally filtered by status.
     *
     * @param  int  $partnerId  The id of the owning partner.
     * @param  ListingStatus|null  $status  Status to filter by, or null for all statuses.
     * @param  int  $perPage  Number of results per page.
     * @return LengthAwarePaginator<int, Listing>
     */
    public function paginateOwnedBy(int $partnerId, ?ListingStatus $status, int $perPage = 15): LengthAwarePaginator;

    /**
     * Persist the given listing.
     *
     * @param  Listing  $listing  The listing to save.
     * @return Listing The saved listing.
     */
    public function save(Listing $listing): Listing;
}
