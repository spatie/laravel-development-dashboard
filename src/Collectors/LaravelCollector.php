<?php

namespace Spatie\DevelopmentDashboard\Collectors;

class LaravelCollector extends Collector
{
    protected $name = 'laravel';

    public function collectedData(): array
    {
        return [
            'version' => app()->version(),
            'environment' => app()->environment(),
            'locale' => app()->getLocale(),
        ];
    }
}