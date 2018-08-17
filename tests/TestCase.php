<?php

namespace Spatie\DevelopmentDashboard\Tests;

use Closure;
use Illuminate\Contracts\Http\Kernel;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Foundation\Testing\TestResponse;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;
use Orchestra\Testbench\TestCase as Orchestra;
use Spatie\DevelopmentDashboard\DevelopmentDashboardServiceProvider;
use Spatie\DevelopmentDashboard\Http\Middleware\CollectData;
use Spatie\DevelopmentDashboard\Report;
use Symfony\Component\Finder\SplFileInfo;

abstract class TestCase extends Orchestra
{
    public function setUp()
    {
        parent::setUp();

        $this->withoutExceptionHandling();
    }

    protected function getPackageProviders($app)
    {
        return [
            DevelopmentDashboardServiceProvider::class
        ];
    }

    /**
     * @param \Illuminate\Foundation\Application $app
     */
    protected function getEnvironmentSetUp($app)
    {
        $this->initializeDirectory($this->getTempDirectory());

        $app['config']->set('development-dashboard.storage.path', $this->getTempDirectory());
        $app['config']->set('development-dashboard.enabled', true);
        $app['config']->set('app.debug', true);

        $app[Kernel::class]->pushMiddleware(CollectData::class);

        $this->setUpDatabase($app);
    }

    protected function initializeDirectory($directory)
    {
        if (File::isDirectory($directory)) {
            File::deleteDirectory($directory);
        }
        File::makeDirectory($directory);
    }

    protected function setUpDatabase($app)
    {
        $app['config']->set('database.default', 'sqlite');
        $app['config']->set('database.connections.sqlite', [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
        ]);

        Schema::create('test_models', function(Blueprint $table) {
            $table->increments('id');
            $table->string('name')->nullable();
            $table->timestamps();
        });
    }

    protected function getTempDirectory()
    {
        return __DIR__ . '/temp';
    }

    protected function assertJsonStructure(array $expectedStructure, string $jsonFilePath = null)
    {
        $reportContent = [];

        if ($report = $this->getLastestReport()) {
            $reportContent = $report->content();
        };

        (new TestResponse(''))->assertJsonStructure($expectedStructure, $reportContent);
    }

    protected function performRequest(Closure $callable = null, $responseCode = 200)
    {
        $callable = $callable ?? function () {
                return '';
            };

        Route::get('/', $callable);

        $this->get('/')->assertStatus($responseCode);

        return $this;
    }

    protected function getLastestReport(): ?Report
    {
        return Report::all()->first();
    }
}