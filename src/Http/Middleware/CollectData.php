<?php

namespace Spatie\DevelopmentDashboard\Http\Middleware;

use Closure;
use Exception;
use Illuminate\Contracts\Debug\ExceptionHandler;
use Spatie\DevelopmentDashboard\DevelopmentDashboard;
use Symfony\Component\HttpFoundation\Request;

class CollectData
{
    /** @var \Spatie\DevelopmentDashboard\DevelopmentDashboard */
    protected $developmentDashboard;

    public function __construct(DevelopmentDashboard $developmentDashboard)
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
            $response = $next($request);
        } catch (Exception $exception) {
            $response = $this->handleException($request, $exception);
        }

        $this->developmentDashboard->stopCollectingData($response);

        return $response;
    }

    protected function handleException($passable, Exception $e)
    {
        if (!app()->bound(ExceptionHandler::class) ||
            !$passable instanceof Request) {

            throw $e;
        }
        $handler = app()->make(ExceptionHandler::class);

        $handler->report($e);

        $response = $handler->render($passable, $e);

        if (method_exists($response, 'withException')) {
            $response->withException($e);
        }

        return $response;
    }

}