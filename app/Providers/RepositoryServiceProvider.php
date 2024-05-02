<?php

namespace App\Providers;

use App\Interfaces\BookingRepositoryInterface;
use App\Interfaces\ConditionRepositoryInterface;
use App\Interfaces\DashboardRepositoryInterface;
use App\Interfaces\InvitationTokenRepositoryInterface;
use App\Interfaces\RoleRepositoryInterface;
use App\Interfaces\TravelCategoryRepositoryInterface;
use App\Interfaces\TravelDestinationRepositoryInterface;
use App\Interfaces\TravelPackageImageRepositoryInterface;
use App\Interfaces\TravelPackageRepositoryInterface;
use App\Interfaces\UserRepositoryInterface;
use App\Repositories\BookingRepository;
use App\Repositories\ConditionRepository;
use App\Repositories\DashboardRepository;
use App\Repositories\InvitationTokenRepository;
use App\Repositories\RoleRepository;
use App\Repositories\TravelCategoryRepository;
use App\Repositories\TravelDestinationRepository;
use App\Repositories\TravelPackageImageRepository;
use App\Repositories\TravelPackageRepository;
use App\Repositories\UserRepository;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(UserRepositoryInterface::class, UserRepository::class);
        $this->app->bind(RoleRepositoryInterface::class, RoleRepository::class);
        $this->app->bind(InvitationTokenRepositoryInterface::class, InvitationTokenRepository::class);
        $this->app->bind(BookingRepositoryInterface::class, BookingRepository::class);
        $this->app->bind(TravelCategoryRepositoryInterface::class, TravelCategoryRepository::class);
        $this->app->bind(TravelDestinationRepositoryInterface::class, TravelDestinationRepository::class);
        $this->app->bind(TravelPackageRepositoryInterface::class, TravelPackageRepository::class);
        $this->app->bind(TravelPackageImageRepositoryInterface::class, TravelPackageImageRepository::class);
        $this->app->bind(ConditionRepositoryInterface::class, ConditionRepository::class);
        $this->app->bind(DashboardRepositoryInterface::class, DashboardRepository::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
