<?php

return [
    'enabled' => env('DEVELOPMENT_DASHBOARD_ENABLED', env('APP_DEBUG', false)),

    'storage' => [
        'path' => storage_path('development-dashboard'),

        'maximum_number_of_kept_reports' => 50,
    ],

    'collectors' => [
        \Spatie\DevelopmentDashboard\Collectors\LaravelCollector::class,
        \Spatie\DevelopmentDashboard\Collectors\QueryCollector::class,
        \Spatie\DevelopmentDashboard\Collectors\RouteCollector::class,
        \Spatie\DevelopmentDashboard\Collectors\RequestCollector::class,
        \Spatie\DevelopmentDashboard\Collectors\ResponseCollector::class,
        \Spatie\DevelopmentDashboard\Collectors\ExceptionCollector::class,
    ],
];
