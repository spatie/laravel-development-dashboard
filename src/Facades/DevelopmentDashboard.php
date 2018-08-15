<?php

namespace Spatie\DevelopmentDashboard\Facades;

use Illuminate\Support\Facades\Facade;

class DevelopmentDashboard extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'development-dashboard';
    }
}