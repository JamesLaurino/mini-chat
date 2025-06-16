<?php

namespace App\Providers;

use App\Interfaces\ConversationRepositoryInterface;
use App\Interfaces\MetricRepositoryInterface;
use App\Interfaces\PreferenceRepositoryInterface;
use App\Interfaces\SpaceRepositoryInterface;
use App\Repositories\ConversationRepository;
use App\Repositories\MetricRepository;
use App\Repositories\PreferenceRepository;
use App\Repositories\SpaceRepository;
use App\Services\ConversationService;
use App\Services\MetricService;
use App\Services\PreferenceService;
use App\Services\SpaceService;
use App\Services\WebService;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(WebService::class);

        $this->app->singleton(SpaceRepositoryInterface::class, SpaceRepository::class);
        $this->app->singleton(SpaceService::class, function ($app) {
            return new SpaceService($app->make(SpaceRepositoryInterface::class));
        });

        $this->app->singleton(MetricRepositoryInterface::class, MetricRepository::class);
        $this->app->singleton(MetricService::class, function ($app) {
            return new MetricService($app->make(MetricRepositoryInterface::class));
        });

        $this->app->singleton(PreferenceRepositoryInterface::class, PreferenceRepository::class);
        $this->app->singleton(PreferenceService::class, function ($app) {
            return new PreferenceService($app->make(PreferenceRepositoryInterface::class));
        });

        $this->app->singleton(ConversationRepositoryInterface::class, ConversationRepository::class);
        $this->app->singleton(ConversationService::class, function ($app) {
            return new ConversationService($app->make(ConversationRepositoryInterface::class));
        });

    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
