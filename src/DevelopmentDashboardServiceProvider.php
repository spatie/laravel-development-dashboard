<?php

namespace Spatie\DevelopmentDashboard;

use Illuminate\Foundation\Http\Kernel;
use Illuminate\Support\ServiceProvider;
use Spatie\DevelopmentDashboard\Http\Middleware\DevelopmentDashboard;

class DevelopmentDashboardServiceProvider extends ServiceProvider
{
    protected $defer = false;

    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/development-dashboard.php' => config_path('development-dashboard.php'),
            ], 'config');
        }

        $this->app->singleton(CollectorManager::class, function() {
           $collectorManager = new CollectorManager();

           $collectorManager->addCollector(config('development-dashboard.collectors'));

           return $collectorManager;
        });

        $this->app[Kernel::class]->pushMiddleware(DevelopmentDashboard::class);
    }

    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/development-dashboard.php', 'development-dashboard');
    }
}
