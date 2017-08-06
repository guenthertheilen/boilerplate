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
    private $user;

    protected function setUp()
    {
        parent::setUp();

        $this->listenerDefaultRole = Mockery::spy(AttachDefaultRoleToUser::class);
        app()->instance(AttachDefaultRoleToUser::class, $this->listenerDefaultRole);

        $this->listenerGeneratePassword = Mockery::spy(GeneratePasswordIfEmpty::class);
        app()->instance(GeneratePasswordIfEmpty::class, $this->listenerGeneratePassword);

        $this->user = factory(User::class)->make();
        event(new UserCreated($this->user));
    }

    /** @test */
    function it_calls_listener_to_attach_default_role()
    {
        $this->listenerDefaultRole->shouldHaveReceived('handle')->with(Mockery::on(function ($event) {
            return $event->user == $this->user;
        }))->once();
    }

    /** @test */
    function it_calls_listener_to_generate_password_if_empty()
    {
        $this->listenerGeneratePassword->shouldHaveReceived('handle')->with(Mockery::on(function ($event) {
            return $event->user == $this->user;
        }))->once();
    }
}
