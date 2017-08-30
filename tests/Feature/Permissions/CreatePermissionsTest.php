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
    function it_shows_link_to_create_user()
    {
        $this->get(route('permission.index'))
            ->assertSee(route('permission.create'));
    }

    /** @test */
    function it_adds_new_permission()
    {
        $this->post(route('permission.store'), ['name' => 'foo']);

        $this->assertDatabaseHas('permissions', ['name' => 'foo']);
    }

    /** @test */
    function it_does_not_add_permission_without_name()
    {
        $this->post(route('permission.store'), ['name' => '']);

        $this->assertDatabaseMissing('permissions', ['name' => '']);
    }

    /** @test */
    function it_does_not_add_permission_without_unique_name()
    {
        $permission = factory(Permission::class)->create(['name' => 'foo']);

        $this->post(route('permission.store'), ['name' => 'foo']);

        $this->assertCount(1, Permission::all());
        $this->assertEquals($permission->id, Permission::whereName('foo')->pluck('id')->first());
    }
}
