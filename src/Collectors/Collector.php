<?php

namespace Spatie\DevelopmentDashboard\Collectors;

use Exception;
use Symfony\Component\HttpFoundation\Response;

abstract class Collector
{
    /** @var \Symfony\Component\HttpFoundation\Response|null */
    protected $response;

    /** @var \Exception|null */
    protected $exception;

    public function name(): string
    {
        return $this->name ?? class_basename(static::class);
    }

    public function setResponse(Response $response)
    {
        $this->response = $response;

        $this->exception = $response->exception;

        return $this;
    }

    public function boot() {

    }

    public abstract function collectedData(): array;
}