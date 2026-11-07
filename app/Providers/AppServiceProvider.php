<?php

namespace App\Providers;

use App\Facades\Module;
use App\Services\ModuleManager;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(ModuleManager::class, function () {
            return new ModuleManager;
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Enforce strict prevention of N+1 queries in development/local
        Model::preventLazyLoading(! app()->isProduction());

        // Custom Blade directive for conditional module rendering
        Blade::if('module', function (string $key) {
            return Module::enabled($key);
        });
    }
}
