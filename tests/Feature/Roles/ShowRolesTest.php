<?php

namespace Tests\Feature\Roles;

use App\Models\Role;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Tests\TestCase;

class ShowRolesTest extends TestCase
{
    use DatabaseMigrations, WithoutMiddleware;

    /** @test */
    function show_list_of_roles()
    {
        factory(Role::class)->create(['name' => 'role_1']);
        factory(Role::class)->create(['name' => 'role_2']);

        $this->get(route('role.index'))
            ->assertSeeText('role_1')
            ->assertSeeText('role_2');
    }
}
