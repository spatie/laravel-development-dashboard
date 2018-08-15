<?php

namespace Spatie\DevelopmentDashboard\Tests\Collectors;

use Spatie\DevelopmentDashboard\Collectors\LaravelCollector;
use Spatie\DevelopmentDashboard\Facades\DevelopmentDashboard;
use Spatie\DevelopmentDashboard\Tests\TestCase;

class LaravelCollectorTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();

        DevelopmentDashboard::setCollectors([
            LaravelCollector::class,
        ]);
    }

    /** @test */
    public function it_can_get_info_on_the_current_laravel_app()
    {
        $this
            ->performRequest()
            ->assertJsonStructure([
                'laravel' => [
                    'version',
                    'environment',
                    'locale',
                ],
            ]);
    }
}
