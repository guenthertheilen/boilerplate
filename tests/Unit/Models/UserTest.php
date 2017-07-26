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
    function it_attaches_role_to_user()
    {
        $user = factory(User::class)->create();
        $role = factory(Role::class)->create();

        $user->attachRole($role);

        $this->assertContains($role->id, $user->roles->pluck('id'));
    }

    /** @test */
    function it_does_not_attach_same_role_more_than_once()
    {
        $user = factory(User::class)->create();
        $role = factory(Role::class)->create();

        $user->attachRole($role);
        $user->attachRole($role);
        $user->attachRole($role);

        $this->assertCount(1, $user->roles->where('id', '=', $role->id));
    }

    /** @test */
    function it_detaches_role_from_user()
    {
        $user = factory(User::class)->create();
        $role = factory(Role::class)->create();
        $user->attachRole($role);

        $user->detachRole($role);

        $this->assertNotContains($role->id, $user->roles->pluck('id'));
    }

    /** @test */
    function it_checks_if_user_has_given_role()
    {
        $user = factory(User::class)->create();
        $role1 = factory(Role::class)->create();
        $role2 = factory(Role::class)->create();

        $user->attachRole($role1);

        $this->assertTrue($user->hasRole($role1));
        $this->assertFalse($user->hasRole($role2));
    }

    /** @test */
    function it_checks_if_user_has_given_role_by_name_of_role()
    {
        $user = factory(User::class)->create();
        $role1 = factory(Role::class)->create(['name' => 'foo']);
        factory(Role::class)->create(['name' => 'bar']);

        $user->attachRole($role1);

        $this->assertTrue($user->hasRole('foo'));
        $this->assertFalse($user->hasRole('bar'));
    }
}
