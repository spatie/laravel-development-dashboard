<?php

namespace Spatie\DevelopmentDashboard\Tests\Unit;

use Spatie\DevelopmentDashboard\Report;
use Spatie\DevelopmentDashboard\Tests\TestCase;

class ReportTest extends TestCase
{
    /** @test */
    public function it_can_create_a_report()
    {
        $content = ['key' => 'value'];

        $report = Report::createFromData($content);

        $this->assertTrue(is_int($report->createdAt()));
        $this->assertEquals($content, $report->content());
    }

    /** @test */
    public function it_can_get_all_reports()
    {
        foreach(range(1,3) as $i) {
            Report::createFromData([$i]);
            sleep(1);
        }

        $this->assertEquals(
            [[3], [2], [1]],
            Report::all()->map->content()->toArray()
        );
    }
}