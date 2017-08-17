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
    private $listenerDefaultRole;
    private $listenerActivationToken;
    private $user;

    protected function setUp()
    {
        parent::setUp();

        $this->listenerDefaultRole = Mockery::spy(AttachDefaultRoleToUser::class);
        app()->instance(AttachDefaultRoleToUser::class, $this->listenerDefaultRole);

        $this->listenerActivationToken = Mockery::spy(CreateActivationToken::class);
        app()->instance(CreateActivationToken::class, $this->listenerActivationToken);

        $this->listenerSendActivationMail = Mockery::spy(SendActivationMail::class);
        app()->instance(SendActivationMail::class, $this->listenerSendActivationMail);

        $this->user = factory(User::class)->make();
        event(new UserCreated($this->user));
    }

    /** @test */
    function it_calls_listener_to_attach_default_role()
    {
        $this->listenerDefaultRole->shouldHaveReceived('handle')->once();
    }

    /** @test */
    function it_calls_listener_to_create_activation_token()
    {
        $this->listenerActivationToken->shouldHaveReceived('handle')->once();
    }

    /** @test */
    function it_calls_listener_to_send_activation_mail()
    {
        $this->listenerSendActivationMail->shouldHaveReceived('handle')->once();
    }
}
