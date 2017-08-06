<?php

namespace Tests\Unit\Listeners;

use App\Events\UserCreated;
use App\Listeners\AttachDefaultRoleToUser;
use App\Models\Role;
use App\Models\User;
use Mockery;
use Tests\TestCase;

class AttachDefaultRoleToUserTest extends TestCase
{

    /** @test */
    function it_attaches_default_role_to_user()
    {
        $user = Mockery::mock(User::class);
        $role = Mockery::mock(Role::class);
        $defaultRole = Mockery::mock(Role::class);

        $role->shouldReceive('defaultRole')->once()->andReturn($defaultRole);
        $user->shouldReceive('attachRole')->with($defaultRole)->once();

        $listener = new AttachDefaultRoleToUser($role);
        $listener->handle(new UserCreated($user));
    }
}
