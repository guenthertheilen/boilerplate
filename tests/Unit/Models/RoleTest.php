<?php

namespace Tests\Unit\Models;

use App\Models\Role;
use Tests\TestCase;

class RoleTest extends TestCase
{
    /** @test */
    function a_role_has_users()
    {
        $role = new Role();

        $role->users();
    }
}
