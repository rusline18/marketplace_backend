<?php

declare(strict_types=1);

namespace App\Domain\Listings\Models;

use App\Domain\Listings\Enums\ListingStatus;
use App\Models\User;
use Database\Factories\ListingFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['user_id', 'category_id', 'title', 'slug', 'description', 'price', 'status', 'published_at'])]
class Listing extends Model
{
    /** @use HasFactory<ListingFactory> */
    use HasFactory;

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'status' => ListingStatus::class,
            'published_at' => 'datetime',
        ];
    }

    /**
     * Get the owning user.
     *
     * @return BelongsTo<User, Listing>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the category this listing belongs to.
     *
     * @return BelongsTo<Category, Listing>
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Get the listing's images, ordered by position.
     *
     * @return HasMany<ListingImage>
     */
    public function images(): HasMany
    {
        return $this->hasMany(ListingImage::class)->orderBy('position');
    }

    /**
     * Get the factory instance used to create this model.
     * @return ListingFactory
     */
    protected static function newFactory(): ListingFactory
    {
        return ListingFactory::new();
    }
}
