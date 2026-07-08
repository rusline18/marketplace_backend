<?php

declare(strict_types=1);

namespace App\Domain\Partners\Models;

use App\Domain\Listings\Models\Listing;
use App\Domain\Partners\Enums\PartnerStatus;
use Database\Factories\PartnerFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;

#[Fillable(['name', 'email', 'password', 'status'])]
#[Hidden(['password', 'remember_token'])]
class Partner extends Authenticatable
{
    /** @use HasFactory<PartnerFactory> */
    use HasApiTokens, HasFactory;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'status' => PartnerStatus::class,
        ];
    }

    /**
     * Get the partner's listings.
     *
     * @return HasMany<Listing>
     */
    public function listings(): HasMany
    {
        return $this->hasMany(Listing::class);
    }

    /**
     * Get the factory instance used to create this model.
     */
    protected static function newFactory(): PartnerFactory
    {
        return PartnerFactory::new();
    }
}
