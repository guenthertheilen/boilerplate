<?php

namespace Tests\Unit\Models;

use App\Models\User;
use Tests\TestCase;

class UserTest extends TestCase
{
    /** @test */
    function a_user_has_roles()
    {
        $user = new User();

        $user->roles();
    }

}
