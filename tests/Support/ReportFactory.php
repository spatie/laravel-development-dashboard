<?php

namespace Spatie\DevelopmentDashboard\Tests\Support;

use Carbon\Carbon;
use Spatie\DevelopmentDashboard\Report;

class ReportFactory
{
    public static function create(array $data = [], Carbon $date = null)
    {
        $report = Report::createFromData($data);

        $date = $date ?? now();

        touch($report->path(), $date->timestamp);

        return $report;
    }
}