<?php

namespace Spatie\DevelopmentDashboard\Http\Middleware;

use Closure;
use Exception;
use Spatie\DevelopmentDashboard\CollectorManager;

class DevelopmentDashboard
{
    /** @var \Spatie\DevelopmentDashboard\CollectorManager */
    protected $collectorManager;

    public function __construct(CollectorManager $collectorManager)
    {
        $this->collectorManager = $collectorManager;
    }

    public function handle($request, Closure $next)
    {
        if (!config('development-dashboard.enabled')) {
            return $next($request);
        }

        $this->collectorManager->start();

        try {
            return $next($request);
        }

        catch(Exception $exception) {
            throw $exception;
        }
        finally
        {
            $this->collectorManager->end();
        }
    }
}