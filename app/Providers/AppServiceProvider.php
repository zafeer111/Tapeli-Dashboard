<?php

namespace App\Providers;

use App\Repositories\BundleRepository;
use App\Repositories\BundleRepositoryInterface;
use App\Repositories\ItemRepository;
use App\Repositories\ItemRepositoryInterface;
use App\Repositories\SportRepository;
use App\Repositories\SportRepositoryInterface;
use App\Repositories\TournamentRepository;
use App\Repositories\TournamentRepositoryInterface;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Event;
use SocialiteProviders\Manager\SocialiteWasCalled;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register()
    {
        $this->app->bind(TournamentRepositoryInterface::class, TournamentRepository::class);
        $this->app->bind(SportRepositoryInterface::class, SportRepository::class);
        $this->app->bind(ItemRepositoryInterface::class, ItemRepository::class);
        $this->app->bind(BundleRepositoryInterface::class, BundleRepository::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Event::listen(function (SocialiteWasCalled $event) {
            $event->extendSocialite('apple', \SocialiteProviders\Apple\Provider::class);
        });
    }
}
