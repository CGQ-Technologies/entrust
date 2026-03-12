<?php

require_once __DIR__.'/MiddlewareTestCase.php';

use Zizaco\Entrust\Middleware\EntrustRole;
use Mockery as m;

class EntrustRoleTest extends MiddlewareTestCase
{
    public function testHandle_IsGuestWithMismatchingRole_ShouldAbort403()
    {
        /*
        |------------------------------------------------------------
        | Set
        |------------------------------------------------------------
        */
        $guard = Mockery::mock('Illuminate\Contracts\Auth\Guard[guest]');
        $request = $this->mockRequest();

        $middleware = new EntrustRole($guard);

        /*
        |------------------------------------------------------------
        | Expectation
        |------------------------------------------------------------
        */
        $guard->shouldReceive('guest')->andReturn(true);
        $request->user()->shouldReceive('hasRole')->andReturn(false);

        $middleware->handle($request, function () {}, 'owner');

        /*
        |------------------------------------------------------------
        | Assertion
        |------------------------------------------------------------
        */
        $this->assertAbortCode(403);
    }

    public function testHandle_IsGuestWithMatchingRole_ShouldAbort403()
    {
        /*
        |------------------------------------------------------------
        | Set
        |------------------------------------------------------------
        */
        $guard = Mockery::mock('Illuminate\Contracts\Auth\Guard');
        $request = $this->mockRequest();

        $middleware = new EntrustRole($guard);

        /*
        |------------------------------------------------------------
        | Expectation
        |------------------------------------------------------------
        */
        $guard->shouldReceive('guest')->andReturn(true);
        $request->user()->shouldReceive('hasRole')->andReturn(true);

        $middleware->handle($request, function () {}, 'owner');

        /*
        |------------------------------------------------------------
        | Assertion
        |------------------------------------------------------------
        */
        $this->assertAbortCode(403);
    }

    public function testHandle_IsLoggedInWithMismatchRole_ShouldAbort403()
    {
        /*
        |------------------------------------------------------------
        | Set
        |------------------------------------------------------------
        */
        $guard = Mockery::mock('Illuminate\Contracts\Auth\Guard');
        $request = $this->mockRequest();

        $middleware = new EntrustRole($guard);

        /*
        |------------------------------------------------------------
        | Expectation
        |------------------------------------------------------------
        */
        $guard->shouldReceive('guest')->andReturn(false);
        $request->user()->shouldReceive('hasRole')->andReturn(false);

        $middleware->handle($request, function () {}, 'owner');

        /*
        |------------------------------------------------------------
        | Assertion
        |------------------------------------------------------------
        */
        $this->assertAbortCode(403);
    }

    public function testHandle_IsLoggedInWithMatchingRole_ShouldNotAbort()
    {
        /*
        |------------------------------------------------------------
        | Set
        |------------------------------------------------------------
        */
        $guard = Mockery::mock('Illuminate\Contracts\Auth\Guard');
        $request = $this->mockRequest();

        $middleware = new EntrustRole($guard);

        /*
        |------------------------------------------------------------
        | Expectation
        |------------------------------------------------------------
        */
        $guard->shouldReceive('guest')->andReturn(false);
        $request->user()->shouldReceive('hasRole')->andReturn(true);

        $middleware->handle($request, function () {}, 'owner');

        /*
        |------------------------------------------------------------
        | Assertion
        |------------------------------------------------------------
        */
        $this->assertDidNotAbort();
    }
}
