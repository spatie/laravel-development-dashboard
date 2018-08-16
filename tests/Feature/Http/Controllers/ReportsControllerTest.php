<?php

namespace Spatie\DevelopmentDashboard\Tests\Feature\Http\Controllers;

use Carbon\Carbon;
use Spatie\DevelopmentDashboard\Tests\Support\ReportFactory;
use Spatie\DevelopmentDashboard\Tests\TestCase;

class ReportsControllerTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();

        Carbon::setTestNow(Carbon::create(2018)->startOfYear());

        foreach (range(1, 10) as $i) {
            ReportFactory::create(['report' => $i], now()->addDays($i));
        }
    }

    /** @test */
    public function it_can_get_the_content_of_all_reports()
    {
        $response = $this
            ->get('/vendor/spatie/development-dashboard/reports')
            ->assertSuccessful()
            ->assertJsonCount(10)
            ->decodeResponseJson();

        $this->assertEquals(range(10, 1), $this->getReportNumbers($response));
    }

    /** @test */
    public function it_can_get_all_reports_starting_from_a_given_timestamp()
    {
        $response = $this
            ->get('/vendor/spatie/development-dashboard/reports?created_after_timestamp=' . now()->addDays(6)->timestamp)
            ->assertSuccessful()
            ->assertJsonCount(5)
            ->decodeResponseJson();

        $this->assertEquals(range(10, 6), $this->getReportNumbers($response));
    }

    protected function getReportNumbers(array $response): array
    {
        return collect($response)
            ->map(function (array $reportProperties) {
                return $reportProperties['content']['report'];
            })
            ->values()
            ->toArray();
    }
}
