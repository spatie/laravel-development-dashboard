<?php

namespace Spatie\DevelopmentDashboard\Tests\Feature\Collectors;

use Exception;
use Spatie\DevelopmentDashboard\Collectors\ExceptionCollector;
use Spatie\DevelopmentDashboard\Collectors\ResponseCollector;
use Spatie\DevelopmentDashboard\Facades\DevelopmentDashboard;
use Spatie\DevelopmentDashboard\Tests\TestCase;

class ExceptionCollectorTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();

        $this->withExceptionHandling();

        DevelopmentDashboard::setCollectors([
            ResponseCollector::class,
            ExceptionCollector::class,
        ]);
    }

    /** @test */
    public function it_can_report_an_exception()
    {
        $this
            ->performRequest(function() {
                throw new Exception('This is a test exception');
            }, 500)
            ->assertJsonStructure([
                'exception' => [
                    'code',
                    'message',
                ],
            ]);
    }
}