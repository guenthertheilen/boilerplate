<?php

namespace Tests\Unit\Listeners;

use App\Events\UserCreated;
use App\Models\User;
use App\Providers\GeneratePasswordIfEmpty;
use Mockery;
use Tests\TestCase;

class GeneratePasswordIfEmptyTest extends TestCase
{
    /** @test */
    function it_generates_password_if_empty()
    {
        $user = Mockery::mock(User::class);
        $user->shouldReceive('getAttribute')->with('password')->once()->andReturn('');
        $user->shouldReceive('update')->with(['password' => 'foo'])->once();

        $listener = new GeneratePasswordIfEmpty();
        $listener->handle(new UserCreated($user));
    }

    /** @test */
    function it_does_not_change_password_if_not_empty()
    {
        $user = Mockery::mock(User::class);
        $user->shouldReceive('getAttribute')->with('password')->once()->andReturn('foobar');
        $user->shouldNotReceive('update');

        $listener = new GeneratePasswordIfEmpty();
        $listener->handle(new UserCreated($user));
    }
}
