<?php

namespace Database\Factories;

use App\Domain\Listings\Enums\ListingStatus;
use App\Domain\Listings\Models\Listing;
use App\Domain\Partners\Models\Partner;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Listing>
 */
class ListingFactory extends Factory
{
    protected $model = Listing::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = fake()->unique()->sentence(3);

        return [
            'partner_id' => Partner::factory(),
            'category_id' => CategoryFactory::new(),
            'title' => $title,
            'slug' => Str::slug($title).'-'.fake()->unique()->numberBetween(1, 1000000),
            'description' => fake()->paragraph(),
            'price' => fake()->randomFloat(2, 1, 1000),
            'status' => ListingStatus::Draft,
            'published_at' => null,
        ];
    }

    /**
     * @return $this
     */
    public function active(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => ListingStatus::Active,
            'published_at' => now(),
        ]);
    }

    /**
     * @return $this
     */
    public function pendingReview(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => ListingStatus::PendingReview,
        ]);
    }
}
