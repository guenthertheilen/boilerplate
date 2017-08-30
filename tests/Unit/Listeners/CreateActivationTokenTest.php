<?php

namespace Tests\Unit\Listeners;

use App\Events\UserCreated;
use App\Listeners\CreateActivationToken;
use App\Models\User;
use Mockery;
use Tests\TestCase;

class CreateActivationTokenTest extends TestCase
{
    /** @test */
    function create_activation_token()
    {
        $user = Mockery::mock(User::class);
        $user->shouldReceive('createActivationToken')->once();

        $listener = new CreateActivationToken();
        $listener->handle(new UserCreated($user));
    }
}
