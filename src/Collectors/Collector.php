<?php

namespace Spatie\DevelopmentDashboard\Collectors;

abstract class Collector
{
    public function name(): string
    {
        return $this->name ?? class_basename(static::class);
    }

    public function boot() {

    }

    public abstract function collectedData(): array;
}