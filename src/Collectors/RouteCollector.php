<?php

namespace Spatie\DevelopmentDashboard\Collectors;

use Illuminate\Routing\Router;

class RouteCollector extends Collector
{
    protected $name = 'route';

    /** @var \Illuminate\Routing\Router */
    protected $router;

    public function __construct(Router $router)
    {
        $this->router = $router;
    }

    public function collectedData(): array
    {
        $route = $this->router->current();

        if (! $route) {
            return [];
        }

        return [
            'method' => $route->methods()[0],
            'action' => $route->getActionName(),
            'name' => $route->getName(),
            'parameters' => $route->parameterNames(),
        ];
    }
}