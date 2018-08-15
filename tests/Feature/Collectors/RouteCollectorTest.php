<?php

namespace Spatie\DevelopmentDashboard\Tests\Feature\Collectors;

use Illuminate\Support\Facades\Route;
use Spatie\DevelopmentDashboard\Collectors\RouteCollector;
use Spatie\DevelopmentDashboard\Facades\DevelopmentDashboard;
use Spatie\DevelopmentDashboard\Tests\Support\TestController;
use Spatie\DevelopmentDashboard\Tests\Support\TestControllerWithParameters;
use Spatie\DevelopmentDashboard\Tests\Support\TestModel;
use Spatie\DevelopmentDashboard\Tests\TestCase;

class RouteCollectorTestCollectorTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();

        DevelopmentDashboard::setCollectors([
            RouteCollector::class,
        ]);
    }

    /** @test */
    public function it_can_report_a_route_closure()
    {
        Route::get('/', function () {
            'ok';
        });

        $this->get('/')->assertSuccessful();

        $this->assertEquals([
            'route' => [
                'method' => 'GET',
                'action' => 'Closure',
                'name' => null,
                'parameters' => [],
            ],
        ], $this->getLastestReport()->content());
    }

    /** @test */
    public function it_can_report_a_route_that_uses_a_controller()
    {
        Route::get('controller', TestController::class . '@index');

        $this->get('controller');

        $this->assertEquals([
            'route' => [
                'method' => 'GET',
                'action' => 'Spatie\DevelopmentDashboard\Tests\Support\TestController@index',
                'name' => null,
                'parameters' => [],
            ],
        ], $this->getLastestReport()->content());
    }

    /** @test */
    public function it_can_report_a_post_call()
    {
        Route::post('controller', TestController::class . '@index');

        $this->post('controller');

        $this->assertEquals([
            'route' => [
                'method' => 'POST',
                'action' => 'Spatie\DevelopmentDashboard\Tests\Support\TestController@index',
                'name' => null,
                'parameters' => [],
            ],
        ], $this->getLastestReport()->content());
    }

    /** @test */
    public function it_can_report_a_named_route()
    {
        Route::get('controller', TestController::class . '@index')->name('routeName');

        $this->get('controller');

        $this->assertEquals([
            'route' => [
                'method' => 'GET',
                'action' => 'Spatie\DevelopmentDashboard\Tests\Support\TestController@index',
                'name' => 'routeName',
                'parameters' => [],
            ],
        ], $this->getLastestReport()->content());
    }

    /** @test */
    public function it_can_report_a_route_with_parameters()
    {
        $testModel = TestModel::create();

        Route::get('controller/{testModel}', TestControllerWithParameters::class . '@show');

        $this->get("controller/{$testModel->id}");

        $this->assertEquals([
            'route' => [
                'method' => 'GET',
                'action' => 'Spatie\DevelopmentDashboard\Tests\Support\TestControllerWithParameters@show',
                'name' => null,
                'parameters' => [
                    'testModel',
                ],
            ],
        ], $this->getLastestReport()->content());

    }

    /** @test */
    public function it_can_report_a_missed_route()
    {
        $this->withExceptionHandling();

        $this->get('does-not-exist');

        $this->assertEquals([
            'route' => [],
        ], $this->getLastestReport()->content());
    }
}
