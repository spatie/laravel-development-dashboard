<?php

namespace Spatie\DevelopmentDashboard\Tests;

use Illuminate\Contracts\Http\Kernel;
use Illuminate\Foundation\Testing\TestResponse;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Route;
use Orchestra\Testbench\TestCase as Orchestra;
use Spatie\DevelopmentDashboard\DevelopmentDashboardServiceProvider;
use Spatie\DevelopmentDashboard\Http\Middleware\DevelopmentDashboard;
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

        $app['config']->set('development-dashboard.storage_directory', $this->getTempDirectory());
        $app['config']->set('development-dashboard.enabled', true);

        $app[Kernel::class]->pushMiddleware(DevelopmentDashboard::class);
    }

    protected function initializeDirectory($directory)
    {
        if (File::isDirectory($directory)) {
            File::deleteDirectory($directory);
        }
        File::makeDirectory($directory);
    }

    public function getTempDirectory()
    {
        return __DIR__ . '/temp';
    }

    public function assertJsonStructure(array $expectedStructure, string $jsonFilePath = null)
    {
        $jsonFilePath = $jsonFilePath ?? $this->getLatestTempFilePath();

        $actualArray = '{}';
        if ($jsonFilePath) {
            $json = file_get_contents($jsonFilePath);

            $actualArray = json_decode($json, true);
        }
        (new TestResponse(''))->assertJsonStructure($expectedStructure, $actualArray);
    }

    protected function getLatestTempFilePath(): ?string
    {
        $latestFile = collect(File::allFiles($this->getTempDirectory()))
            ->sortByDesc(function (SplFileInfo $file) {

                return $file->getMTime();
            })
            ->first();

        return optional($latestFile)->getPathname();
    }

    protected function performRequest(Callable $callable = null)
    {
        $callable = $callable ?? function () {
                return '';
            };

        Route::get('/', $callable);

        $this->get('/')->assertSuccessful();

        return $this;
    }
}