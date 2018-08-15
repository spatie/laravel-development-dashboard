<?php

namespace Spatie\DevelopmentDashboard\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\DevelopmentDashboard\Report;

class ReportController
{
    public function index(Request $request)
    {
        return Report::all($request->createdAfterTimestamp)
            ->take(100)
            ->map(function(Report $report) {
               return [
                   'createdAt' => $report->createdAt(),
                   'content' => $report->content()
               ];
            })
            ->toArray();
    }
}