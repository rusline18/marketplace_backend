<?php

declare(strict_types=1);

namespace App\Domain\Listings\Models;

use Database\Factories\ListingImageFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['listing_id', 'path', 'position'])]
class ListingImage extends Model
{
    /** @use HasFactory<ListingImageFactory> */
    use HasFactory;

    /**
     * Get the listing this image belongs to.
     *
     * @return BelongsTo<Listing, ListingImage>
     */
    public function listing(): BelongsTo
    {
        return $this->belongsTo(Listing::class);
    }

    /**
     * Get the factory instance used to create this model
     * .
     * @return ListingImageFactory
     */
    protected static function newFactory(): ListingImageFactory
    {
        return ListingImageFactory::new();
    }
}
