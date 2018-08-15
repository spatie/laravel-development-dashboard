<?php

namespace Spatie\DevelopmentDashboard;

use Illuminate\Foundation\Http\Kernel;
use Illuminate\Support\ServiceProvider;
use Spatie\DevelopmentDashboard\Http\Middleware\DevelopmentDashboard as DevelopmentDashboardMiddleware;

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

        $this->app->singleton(DevelopmentDashboard::class, function() {
           $developmentDashboard = new DevelopmentDashboard();

           $developmentDashboard->addCollector(config('development-dashboard.collectors'));

           return $developmentDashboard;
        });

        $this->app->alias(DevelopmentDashboard::class, 'development-dashboard');

        $this->app[Kernel::class]->pushMiddleware(DevelopmentDashboardMiddleware::class);
    }

    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/development-dashboard.php', 'development-dashboard');
    }
}
