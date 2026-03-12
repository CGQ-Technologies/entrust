<?php

use PHPUnit\Framework\TestCase;
use Zizaco\Entrust\Entrust;

class EntrustTest extends TestCase
{
    protected function tearDown(): void
    {
        Mockery::close();

        parent::tearDown();
    }

    public function testHasRole()
    {
        $app = new stdClass();
        $entrust = Mockery::mock('Zizaco\Entrust\Entrust[user]', [$app]);
        $user = Mockery::mock('_mockedUser');

        $entrust->shouldReceive('user')
            ->andReturn($user)
            ->twice()
            ->ordered();

        $entrust->shouldReceive('user')
            ->andReturn(false)
            ->once()
            ->ordered();

        $user->shouldReceive('hasRole')
            ->with('UserRole', false)
            ->andReturn(true)
            ->once();

        $user->shouldReceive('hasRole')
            ->with('NonUserRole', false)
            ->andReturn(false)
            ->once();

        $this->assertTrue($entrust->hasRole('UserRole'));
        $this->assertFalse($entrust->hasRole('NonUserRole'));
        $this->assertFalse($entrust->hasRole('AnyRole'));
    }

    public function testCan()
    {
        $app = new stdClass();
        $entrust = Mockery::mock('Zizaco\Entrust\Entrust[user]', [$app]);
        $user = Mockery::mock('_mockedUser');

        $entrust->shouldReceive('user')
            ->andReturn($user)
            ->twice()
            ->ordered();

        $entrust->shouldReceive('user')
            ->andReturn(false)
            ->once()
            ->ordered();

        $user->shouldReceive('can')
            ->with('user_can', false)
            ->andReturn(true)
            ->once();

        $user->shouldReceive('can')
            ->with('user_cannot', false)
            ->andReturn(false)
            ->once();

        $this->assertTrue($entrust->can('user_can'));
        $this->assertFalse($entrust->can('user_cannot'));
        $this->assertFalse($entrust->can('any_permission'));
    }

    public function testUser()
    {
        $app = new stdClass();
        $app->auth = Mockery::mock('Auth');
        $entrust = new Entrust($app);
        $user = Mockery::mock('_mockedUser');

        $app->auth->shouldReceive('user')
            ->andReturn($user)
            ->once();

        $this->assertSame($user, $entrust->user());
    }
}
