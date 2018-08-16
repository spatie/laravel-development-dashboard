<?php

namespace Spatie\DevelopmentDashboard;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Spatie\DevelopmentDashboard\Collectors\Collector;

class DevelopmentDashboard
{
    /** @var \Closure */
    public static $authUsing;

    /** @var \Illuminate\Support\Collection */
    protected $collectors;

    public function __construct()
    {
        $this->collectors = collect();
    }

    /**
     * @param Spatie\DevelopmentDashboard\Collector\|string|array $collector
     */
    public function addCollector($collector)
    {
        if (is_array($collector)) {

            foreach($collector as $singleCollector) {
                $this->addCollector($singleCollector);
            }

            return;
        }

        if (is_string($collector)) {
            $collector = app($collector);
        }

        $this->collectors->push($collector);
    }

    public function setCollectors(array $collectors)
    {
        $this->collectors = collect();

        $this->addCollector($collectors);
    }

    public function startCollectingData()
    {
        $this->collectors->each->boot();
    }

    public function stopCollectingData()
    {
        $this->collectors
            ->mapWithKeys(function (Collector $collector) {
                return [$collector->name() => $collector->collectedData()];
            })
            ->pipe(function(Collection $collectedData) {
                Report::createFromData($collectedData->toArray());
            });
    }

    public static function check($request)
    {
        return (static::$authUsing ?? function () {
            return config('development-dashboard.enabled');
        })($request);
    }

    public static function auth(Closure $callback)
    {
        static::$authUsing = $callback;

        return new static;
    }
}