<?php

namespace Tests\Unit\Listeners;

use App\Events\UserCreated;
use App\Models\User;
use App\Providers\GeneratePasswordIfEmpty;
use App\Services\Password;
use Mockery;
use Tests\TestCase;

class GeneratePasswordIfEmptyTest extends TestCase
{
    /** @test */
    function it_generates_password_if_empty()
    {
        $user = Mockery::mock(User::class);
        $password = Mockery::mock(Password::class);
        $user->shouldReceive('getAttribute')->with('password')->once()->andReturn('');
        $password->shouldReceive('generate')->once()->andReturn('some-password');
        $password->shouldReceive('encrypt')->with('some-password')->once()->andReturn('encrypted-password');

        $user->shouldReceive('update')->with(['password' => 'encrypted-password'])->once();

        $listener = new GeneratePasswordIfEmpty($password);
        $listener->handle(new UserCreated($user));
    }

    /** @test */
    function it_does_not_change_password_if_it_is_already_set()
    {
        $user = Mockery::mock(User::class);
        $password = Mockery::mock(Password::class);
        $user->shouldReceive('getAttribute')->with('password')->once()->andReturn('foobar');
        $user->shouldNotReceive('update');
        $password->shouldNotReceive('generate');

        $listener = new GeneratePasswordIfEmpty($password);
        $listener->handle(new UserCreated($user));
    }
}
