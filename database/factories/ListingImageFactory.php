<?php

namespace Database\Factories;

use App\Domain\Listings\Models\ListingImage;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ListingImage>
 */
class ListingImageFactory extends Factory
{
    protected $model = ListingImage::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'listing_id' => ListingFactory::new(),
            'path' => 'listings/'.fake()->uuid().'.jpg',
            'position' => 0,
        ];
    }
}
