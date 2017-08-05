<?php

namespace Tests\Feature\AccessControl;

use App\Models\Role;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class RolesTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    function it_shows_list_of_roles()
    {
        factory(Role::class)->create(['name' => 'role_1']);
        factory(Role::class)->create(['name' => 'role_2']);

        $this->withoutMiddleware()
            ->get(route('role.index'))
            ->assertSeeText('role_1')
            ->assertSeeText('role_2');
    }

    /** @test */
    function it_adds_new_role()
    {
        $this->withoutMiddleware()
            ->post(route('role.store'), ['name' => 'foobar']);

        $this->assertDatabaseHas('roles', ['name' => 'foobar']);
    }

    /** @test */
    function it_shows_links_to_edit_role()
    {
        $role = factory(Role::class)->create();
        $this->withoutMiddleware()
            ->get(route('role.index'))
            ->assertSee(route('role.edit', $role->id));
    }
}
