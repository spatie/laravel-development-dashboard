<?php

namespace Spatie\DevelopmentDashboard\Collectors;

use Illuminate\Foundation\Application;

class LaravelCollector extends Collector
{
    protected $name = 'laravel';

    /** @var \Illuminate\Foundation\Application */
    protected $app;

    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    public function collectedData(): array
    {
        return [
            'version' => $this->app->version(),
            'environment' => $this->app->environment(),
            'locale' => $this->app->getLocale(),
        ];
    }
}