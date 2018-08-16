<?php

namespace Spatie\DevelopmentDashboard\Tests\Feature;

use Spatie\DevelopmentDashboard\Facades\DevelopmentDashboard;
use Spatie\DevelopmentDashboard\Http\Middleware\Authorize;
use Spatie\DevelopmentDashboard\Tests\TestCase;
use Symfony\Component\HttpKernel\Exception\HttpException;

class AuthTest extends TestCase
{
    /** @test */
    public function it_will_follow_the_enabled_setting_if_no_closure_is_given()
    {
        config()->set('development-dashboard.enabled', true);

        $this->assertTrue(DevelopmentDashboard::check('user'));

        config()->set('development-dashboard.enabled', false);

        $this->assertFalse(DevelopmentDashboard::check('user'));
    }

    /** @test */
    public function it_can_check_authorisation_with_a_closure()
    {
        $this->assertTrue(DevelopmentDashboard::check('john'));

        DevelopmentDashboard::auth(function ($request) {
            return $request === 'john';
        });

        $this->assertTrue(DevelopmentDashboard::check('john'));
        $this->assertFalse(DevelopmentDashboard::check('jane'));
        $this->assertFalse(DevelopmentDashboard::check(null));
    }

    public function it_returns_a_correct_response()
    {
        DevelopmentDashboard::auth(function () {
            return true;
        });

        $middleware = new Authorize;

        $response = $middleware->handle(
            new class {
            },
            function () {
                return 'response';
            }
        );

        $this->assertSame('response', $response);
    }

    public function it_will_responsd_with_a_403_if_the_user_is_not()
    {
        DevelopmentDashboard::auth(function () {
            return false;
        });

        $middleware = new Authorize;

        $this->expectException(HttpException::class);

        $middleware->handle(
            new class {
            },
            function ($value) {
                return 'response';
            }
        );
    }


}
