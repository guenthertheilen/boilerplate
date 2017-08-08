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
    function it_creates_activation_token()
    {
        $user = Mockery::spy(User::class);

        $listener = new CreateActivationToken();
        $listener->handle(new UserCreated($user));

        $user->shouldHaveReceived('createActivationToken')->once();
    }
}
