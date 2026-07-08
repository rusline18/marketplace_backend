<?php

declare(strict_types=1);

namespace App\Domain\Listings\Repositories;

use App\Domain\Listings\Enums\ListingStatus;
use App\Domain\Listings\Models\Listing;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;

class EloquentListingRepository implements ListingRepositoryInterface
{
    /**
     * Paginate active listings, optionally filtered by search term and category.
     *
     * @param  array{search?: string, category_id?: int}  $filters  Optional search and category filters.
     * @param  int  $perPage  Number of results per page.
     * @return LengthAwarePaginator<int, Listing>
     */
    public function paginateActive(array $filters, int $perPage = 15): LengthAwarePaginator
    {
        return Listing::query()
            ->where('status', ListingStatus::Active)
            ->when($filters['search'] ?? null, fn ($query, $search) => $query->where('title', 'like', "%{$search}%"))
            ->when($filters['category_id'] ?? null, fn ($query, $categoryId) => $query->where('category_id', $categoryId))
            ->latest('published_at')
            ->paginate($perPage);
    }

    /**
     * Find an active listing by id.
     *
     * @param  int  $id  The listing id.
     */
    public function findActive(int $id): ?Listing
    {
        return Listing::query()
            ->where('status', ListingStatus::Active)
            ->find($id);
    }

    /**
     * Find a listing by id, scoped to a specific owner.
     *
     * @param  int  $id  The listing id.
     * @param  int  $partnerId  The id of the partner expected to own the listing.
     */
    public function findOwnedBy(int $id, int $partnerId): ?Listing
    {
        return Listing::query()
            ->where('partner_id', $partnerId)
            ->find($id);
    }

    /**
     * Paginate listings, optionally filtered by status.
     *
     * @param  ListingStatus|null  $status  Status to filter by, or null for all statuses.
     * @param  int  $perPage  Number of results per page.
     * @return LengthAwarePaginator<int, Listing>
     */
    public function paginateByStatus(?ListingStatus $status, int $perPage = 15): LengthAwarePaginator
    {
        return Listing::query()
            ->when($status, fn (Builder $query) => $query->where('status', $status))
            ->latest()
            ->paginate($perPage);
    }

    /**
     * Paginate listings owned by a specific partner, optionally filtered by status.
     *
     * @param  int  $partnerId  The id of the owning partner.
     * @param  ListingStatus|null  $status  Status to filter by, or null for all statuses.
     * @param  int  $perPage  Number of results per page.
     * @return LengthAwarePaginator<int, Listing>
     */
    public function paginateOwnedBy(int $partnerId, ?ListingStatus $status, int $perPage = 15): LengthAwarePaginator
    {
        return Listing::query()
            ->where('partner_id', $partnerId)
            ->when($status, fn (Builder $query) => $query->where('status', $status))
            ->latest()
            ->paginate($perPage);
    }

    /**
     * Persist the given listing.
     *
     * @param  Listing  $listing  The listing to save.
     * @return Listing The saved listing.
     */
    public function save(Listing $listing): Listing
    {
        $listing->save();

        return $listing;
    }
}
