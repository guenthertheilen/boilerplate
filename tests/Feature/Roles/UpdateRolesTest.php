<?php

namespace Tests\Feature\Roles;

use App\Models\Role;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Tests\TestCase;

class UpdateRolesTest extends TestCase
{
    use DatabaseMigrations, WithoutMiddleware;

    /** @test */
    function show_links_to_edit_role()
    {
        $role = factory(Role::class)->create();

        $this->get(route('role.index'))
            ->assertSee(route('role.edit', $role->id));
    }
}
