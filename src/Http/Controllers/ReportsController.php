<?php

namespace Spatie\DevelopmentDashboard\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\DevelopmentDashboard\Report;

class ReportsController
{
    public function index(Request $request)
    {
        return Report::all($request->created_after_timestamp)
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