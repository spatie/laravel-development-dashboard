<?php

namespace Spatie\DevelopmentDashboard;

use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Spatie\DevelopmentDashboard\Collectors\Collector;

class CollectorManager
{
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

    public function start()
    {
        $this->collectors->each->boot();
    }

    public function end()
    {
        $this->collectors
            ->mapWithKeys(function (Collector $collector) {
                return [$collector->name() => $collector->collectedData()];
            })
            ->pipe(function(Collection $collectedData) {
                $this->writeToFile($collectedData->toArray());
            });

    }

    protected function writeToFile(array $data)
    {
        $fileName = date('Ymd-his') . '-' . Str::uuid() . '.json';

        $fullPath = config('development-dashboard.storage_directory') . '/' . $fileName;

        file_put_contents($fullPath, json_encode($data));
    }
}