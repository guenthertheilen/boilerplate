<?php

namespace Tests\Feature\Permissions;

use App\Models\Permission;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Tests\TestCase;

class CreatePermissionsTest extends TestCase
{
    use DatabaseMigrations, WithoutMiddleware;

    /** @test */
    function show_link_to_create_permission()
    {
        $this->get(route('permission.index'))
            ->assertSee(route('permission.create'));
    }

    /** @test */
    function create_new_permission()
    {
        $this->post(route('permission.store'), ['name' => 'foo']);

        $this->assertDatabaseHas('permissions', ['name' => 'foo']);
    }

    /** @test */
    function new_permission_requires_name()
    {
        $this->post(route('permission.store'), ['name' => '']);

        $this->assertDatabaseMissing('permissions', ['name' => '']);
    }

    /** @test */
    function new_permission_requires_unique_name()
    {
        $permission = factory(Permission::class)->create(['name' => 'foo']);

        $this->post(route('permission.store'), ['name' => 'foo']);

        $this->assertCount(1, Permission::all());
        $this->assertEquals($permission->id, Permission::whereName('foo')->pluck('id')->first());
    }
}
