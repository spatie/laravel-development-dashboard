<?php

namespace Spatie\DevelopmentDashboard\Tests\Feature\Collectors;

use Spatie\DevelopmentDashboard\Collectors\RequestCollector;
use Spatie\DevelopmentDashboard\Facades\DevelopmentDashboard;
use Spatie\DevelopmentDashboard\Tests\TestCase;

class RequestCollectorTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();

        DevelopmentDashboard::setCollectors([
            RequestCollector::class,
        ]);
    }

    /** @test */
    public function it_can_report_a_the_properties_of_a_request()
    {
        $this
            ->performRequest()
            ->assertJsonStructure([
                'request' => [
                    'format',
                ],
            ]);
    }
}
