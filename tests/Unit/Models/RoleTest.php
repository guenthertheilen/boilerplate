<?php

namespace Tests\Unit\Models;

use App\Models\Role;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class RoleTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    function it_has_users()
    {
        $role = factory(Role::class)->create();

        $role->users;
    }

    /** @test */
    function it_gets_default_role()
    {
        $role = new Role();
        $default = $role->defaultRole()->toArray();

        $this->assertEquals('user', $default['name']);
    }

    /** @test */
    function it_creates_default_role_if_it_is_missing()
    {
        $this->assertDatabaseMissing('roles', ['name' => 'user']);

        $role = new Role();
        $default = $role->defaultRole()->toArray();

        $this->assertDatabaseHas('roles', ['id' => $default['id'], 'name' => 'user']);
    }

    /** @test */
    function it_creates_default_role_only_once()
    {
        $this->assertDatabaseMissing('roles', ['name' => 'user']);

        $role = new Role();
        $role->defaultRole()->toArray();
        $role->defaultRole()->toArray();

        $this->assertCount(1, $role->where(['name' => 'user'])->get());
    }
}
