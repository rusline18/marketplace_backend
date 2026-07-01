<?php

namespace App\Providers;

use App\Domain\Checkins\Models\Checkin;
use App\Domain\Checkins\Policies\CheckinPolicy;
use App\Domain\Checkins\Repositories\CheckinRepositoryInterface;
use App\Domain\Checkins\Repositories\EloquentCheckinRepository;
use App\Domain\Listings\Models\Listing;
use App\Domain\Listings\Policies\ListingPolicy;
use App\Domain\Listings\Repositories\EloquentListingRepository;
use App\Domain\Listings\Repositories\ListingRepositoryInterface;
use App\Domain\Orders\Models\Order;
use App\Domain\Orders\Policies\OrderPolicy;
use App\Domain\Orders\Repositories\EloquentOrderRepository;
use App\Domain\Orders\Repositories\OrderRepositoryInterface;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(ListingRepositoryInterface::class, EloquentListingRepository::class);
        $this->app->bind(OrderRepositoryInterface::class, EloquentOrderRepository::class);
        $this->app->bind(CheckinRepositoryInterface::class, EloquentCheckinRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Gate::policy(Listing::class, ListingPolicy::class);
        Gate::policy(Order::class, OrderPolicy::class);
        Gate::policy(Checkin::class, CheckinPolicy::class);
    }
}
