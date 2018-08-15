<?php

return [
    'enabled' => env('DEVELOPMENT_DASHBOARD_ENABLED', env('APP_DEBUG', false)),

    'storage_path' => storage_path('development-dashboard'),

    'collectors' => [
        \Spatie\DevelopmentDashboard\Collectors\LaravelCollector::class,
    ]
];