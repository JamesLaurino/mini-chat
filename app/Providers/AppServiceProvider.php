<?php

namespace App\Providers;

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
        $this->app->singleton(SpaceService::class);
        $this->app->singleton(ConversationService::class);
        $this->app->singleton(PreferenceService::class);
        $this->app->singleton(WebService::class);
        $this->app->singleton(MetricService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
