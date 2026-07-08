<?php

namespace Database\Factories;

use App\Domain\Partners\Enums\PartnerStatus;
use App\Domain\Partners\Models\Partner;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends Factory<Partner>
 */
class PartnerFactory extends Factory
{
    protected $model = Partner::class;

    protected static ?string $password;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => static::$password ??= Hash::make('password'),
            'status' => PartnerStatus::Pending,
            'remember_token' => Str::random(10),
        ];
    }

    /**
     * @return $this
     */
    public function approved(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => PartnerStatus::Approved,
        ]);
    }

    /**
     * @return $this
     */
    public function rejected(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => PartnerStatus::Rejected,
        ]);
    }
}
