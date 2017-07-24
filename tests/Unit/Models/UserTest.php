<?php

namespace Tests\Unit\Models;

use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class UserTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    function a_user_has_roles()
    {
        $user = new User();

        $user->roles();
    }

    /** @test */
    function it_attaches_role_to_user()
    {
        $user = factory(User::class)->create();
        $role = factory(Role::class)->create();

        $this->assertEmpty($user->roles);

        $user->attachRole($role);

        $user = $user->fresh();
        $this->assertCount(1, $user->roles);
        $this->assertEquals($role->id, $user->roles->first()->id);
    }

    /** @test */
    function it_detaches_role_from_user()
    {
        $user = factory(User::class)->create();
        $role = factory(Role::class)->create();
        $user->attachRole($role);

        $user->detachRole($role);

        $user = $user->fresh();
        $this->assertEmpty($user->roles);
    }
}
