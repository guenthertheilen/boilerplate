<?php

namespace Tests\Unit\Events;

use App\Events\UserCreated;
use App\Listeners\AttachDefaultRoleToUser;
use App\Listeners\CreateActivationToken;
use App\Listeners\SendActivationMail;
use App\Models\User;
use Mockery;
use Tests\TestCase;

class UserCreatedTest extends TestCase
{

    /** @test */
    function call_listeners()
    {
        $listenerDefaultRole = Mockery::spy(AttachDefaultRoleToUser::class);
        app()->instance(AttachDefaultRoleToUser::class, $listenerDefaultRole);
        $listenerDefaultRole->shouldReceive('handle')->once();

        $listenerActivationToken = Mockery::spy(CreateActivationToken::class);
        app()->instance(CreateActivationToken::class, $listenerActivationToken);
        $listenerActivationToken->shouldReceive('handle')->once();

        $listenerSendActivationMail = Mockery::spy(SendActivationMail::class);
        app()->instance(SendActivationMail::class, $listenerSendActivationMail);
        $listenerSendActivationMail->shouldReceive('handle')->once();

        event(new UserCreated(factory(User::class)->make()));
    }
}
