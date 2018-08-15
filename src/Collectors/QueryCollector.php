<?php

namespace Spatie\DevelopmentDashboard\Collectors;

use Illuminate\Database\Events\QueryExecuted;
use Illuminate\Support\Facades\DB;

class QueryCollector extends Collector
{
    protected $name = 'queries';

    protected $queries = [];

    public function boot()
    {
        DB::listen(function(QueryExecuted $query) {
            $this->queries[$query->connectionName][] = [
                'sql' => $query->sql,
                'bindings' => $query->bindings,
                'time' => $query->time,
            ];
        });
    }

    public function collectedData(): array
    {
        return $this->queries;
    }
}