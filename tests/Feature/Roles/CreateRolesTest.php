<?php

namespace Tests\Feature\Roles;

use App\Models\Role;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Tests\TestCase;

class CreateRolesTest extends TestCase
{
    use DatabaseMigrations, WithoutMiddleware;

    /** @test */
    function show_link_to_create_role()
    {
        $this->get(route('role.index'))
            ->assertSee(route('role.create'));
    }

    /** @test */
    function create_new_role()
    {
        $this->post(route('role.store'), ['name' => 'foobar']);

        $this->assertDatabaseHas('roles', ['name' => 'foobar']);
    }

    /** @test */
    function new_role_requires_name()
    {
        $this->post(route('role.store'), ['name' => '']);

        $this->assertDatabaseMissing('roles', ['name' => '']);
    }

    /** @test */
    function new_role_requires_unique_name()
    {
        $role = factory(Role::class)->create(['name' => 'foo']);

        $this->post(route('role.store'), ['name' => 'foo']);

        $this->assertCount(1, Role::all());
        $this->assertEquals($role->id, Role::where('name', 'foo')->pluck('id')->first());
    }
}
