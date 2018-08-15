<?php

namespace Spatie\DevelopmentDashboard\Tests\Collectors;

use Spatie\DevelopmentDashboard\Collectors\QueryCollector;
use Spatie\DevelopmentDashboard\Facades\DevelopmentDashboard;
use Spatie\DevelopmentDashboard\Tests\TestCase;
use Spatie\DevelopmentDashboard\Tests\TestModel;

class QueryCollectorTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();

        DevelopmentDashboard::setCollectors([
            QueryCollector::class,
        ]);
    }

    /** @test */
    public function it_can_log_queries()
    {
        $this
            ->performRequest(function() {
                TestModel::create(['name' => 'john']);
            })
            ->assertJsonStructure([
                'queries' => [
                    'sqlite' => [
                        0 => [
                            'sql',
                            'bindings',
                            'time',
                        ],
                    ],
                ],
            ]);
    }
}
