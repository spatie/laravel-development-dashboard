<?php

namespace Spatie\DevelopmentDashboard\Tests;

class ExampleTest extends TestCase
{
    /** @test */
    public function it_tests()
    {
        $this
            ->performRequest()
            ->assertJsonStructure(['laravel' => [
                'version', 'environment', 'locale',
            ]]);
    }
}
