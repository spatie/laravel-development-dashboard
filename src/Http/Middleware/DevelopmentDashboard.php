<?php

namespace Spatie\DevelopmentDashboard\Http\Middleware;

use Closure;
use Exception;
use Spatie\DevelopmentDashboard\DevelopmentDashboard as Dashboard;

class DevelopmentDashboard
{
    /** @var \Spatie\DevelopmentDashboard\DevelopmentDashboard */
    protected $developmentDashboard;

    public function __construct(Dashboard $developmentDashboard)
    {
        $this->developmentDashboard = $developmentDashboard;
    }

    public function handle($request, Closure $next)
    {
        if (!config('development-dashboard.enabled')) {
            return $next($request);
        }

        $this->developmentDashboard->startCollectingData();

        try {
            return $next($request);
        }

        catch(Exception $exception) {
            throw $exception;
        }
        finally
        {
            $this->developmentDashboard->stopCollectingData();
        }
    }
}