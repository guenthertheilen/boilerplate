<?php

namespace Tests\Unit\Models;

use App\Models\Permission;
use App\Models\Role;
use Tests\TestCase;

class RoleTest extends TestCase
{
    /** @test */
    function attach_permission_to_role()
    {
        $role = factory(Role::class)->create();
        $permission = factory(Permission::class)->create();

        $role->attachPermission($permission);

        $this->assertContains($permission->id, $role->permissions->pluck('id'));
    }

    /** @test */
    function attach_permission_by_name_to_role()
    {
        $role = factory(Role::class)->create();
        $permission = factory(Permission::class)->create(['name' => '/foobar']);

        $role->attachPermission('/foobar');

        $this->assertContains($permission->id, $role->permissions->pluck('id'));
    }

    /** @test */
    function check_if_role_has_given_permission()
    {
        $role = factory(Role::class)->create();
        $permission1 = factory(Permission::class)->create();
        $permission2 = factory(Permission::class)->create();

        $role->attachPermission($permission1);

        $this->assertTrue($role->hasPermission($permission1));
        $this->assertFalse($role->hasPermission($permission2));
    }

    /** @test */
    function check_if_role_has_given_permission_by_name_of_permission()
    {
        $role = factory(Role::class)->create();
        $permission1 = factory(Permission::class)->create(['name' => '/foo']);
        factory(Permission::class)->create(['name' => '/bar']);

        $role->attachPermission($permission1);

        $this->assertTrue($role->hasPermission('/foo'));
        $this->assertFalse($role->hasPermission('/bar'));
    }

    /** @test */
    function get_default_role()
    {
        $default = app(Role::class)->defaultRole()->toArray();

        $this->assertEquals('user', $default['name']);
    }

    /** @test */
    function create_default_role_if_it_is_missing()
    {
        $this->assertDatabaseMissing('roles', ['name' => 'user']);

        $default = app(Role::class)->defaultRole()->toArray();

        $this->assertDatabaseHas('roles', ['id' => $default['id'], 'name' => 'user']);
    }

    /** @test */
    function create_default_role_only_once()
    {
        $this->assertDatabaseMissing('roles', ['name' => 'user']);

        app(Role::class)->defaultRole();
        app(Role::class)->defaultRole();

        $this->assertCount(1, Role::where('name', 'user')->get());
    }

    /** @test */
    function check_if_role_is_default_role()
    {
        $role1 = factory(Role::class)->create(['name' => 'foo']);
        $this->assertFalse($role1->isDefaultRole());

        $role2 = factory(Role::class)->create(['name' => 'user']);
        $this->assertTrue($role2->isDefaultRole());
    }
}
