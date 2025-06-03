<?php

namespace App\Providers;

use App\Http\Controllers\InstructionController\InstructionController;
use App\Services\ConversationService;
use App\Services\SpaceService;
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
        $this->app->singleton(InstructionController::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
