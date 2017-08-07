<?php

namespace Tests\Unit\Events;

use App\Events\UserCreated;
use App\Listeners\AttachDefaultRoleToUser;
use App\Models\User;
use Mockery;
use Tests\TestCase;

class UserCreatedTest extends TestCase
{
    private $listenerDefaultRole;
    private $user;

    protected function setUp()
    {
        parent::setUp();

        $this->listenerDefaultRole = Mockery::spy(AttachDefaultRoleToUser::class);
        app()->instance(AttachDefaultRoleToUser::class, $this->listenerDefaultRole);

        $this->user = factory(User::class)->make();
        event(new UserCreated($this->user));
    }

    /** @test */
    function it_calls_listener_to_attach_default_role()
    {
        $this->listenerDefaultRole->shouldHaveReceived('handle')->once();
    }
}
