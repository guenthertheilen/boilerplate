<?php

namespace Tests\Unit\Events;

use App\Events\UserCreated;
use App\Listeners\AttachDefaultRoleToUser;
use App\Models\User;
use App\Providers\GeneratePasswordIfEmpty;
use Mockery;
use Tests\TestCase;

class UserCreatedTest extends TestCase
{
    private $listenerDefaultRole;
    private $listenerGeneratePassword;

    protected function setUp()
    {
        parent::setUp();

        $this->listenerDefaultRole = Mockery::spy(AttachDefaultRoleToUser::class);
        app()->instance(AttachDefaultRoleToUser::class, $this->listenerDefaultRole);

        $this->listenerGeneratePassword = Mockery::spy(GeneratePasswordIfEmpty::class);
        app()->instance(GeneratePasswordIfEmpty::class, $this->listenerGeneratePassword);
    }

    /** @test */
    function it_calls_listener_to_attach_default_role()
    {
        $user = factory(User::class)->make();
        event(new UserCreated($user));

        $this->listenerDefaultRole->shouldHaveReceived('handle')->with(Mockery::on(function ($event) use ($user) {
            return $event->user == $user;
        }))->once();
    }

    /** @test */
    function it_calls_listener_to_generate_password_if_empty()
    {
        $user = factory(User::class)->make();
        event(new UserCreated($user));

        $this->listenerGeneratePassword->shouldHaveReceived('handle')->with(Mockery::on(function ($event) use ($user) {
            return $event->user == $user;
        }))->once();
    }
}
