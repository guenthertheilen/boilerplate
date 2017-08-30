<?php

namespace Tests\Unit\Models;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class PermissionTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    function get_attached_roles_in_alphabetical_order_as_comma_seperated_string()
    {
        $permission = factory(Permission::class)->create();

        $role1 = factory(Role::class)->create(['name' => 'xyz']);
        $role1->attachPermission($permission);

        $role2 = factory(Role::class)->create(['name' => 'abc']);
        $role2->attachPermission($permission);

        $this->assertEquals('abc, xyz', $permission->rolesAsString());
    }
}
