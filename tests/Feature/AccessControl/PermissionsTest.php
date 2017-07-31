<?php

namespace Tests\Feature\AccessControl;

use App\Models\Permission;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class PermissionsTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    function it_shows_list_of_permissions()
    {
        factory(Permission::class)->create(['name' => 'permission_1']);
        factory(Permission::class)->create(['name' => 'permission_2']);

        $this->withoutMiddleware()
            ->get('permission')
            ->assertSeeText('permission_1')
            ->assertSeeText('permission_2');
    }

    /** @test */
    function it_adds_new_permission()
    {
        $this->withoutMiddleware()
            ->post(route('permission.store'), ['name' => 'foo']);

        $this->assertDatabaseHas('permissions', ['name' => 'foo']);
    }

    /** @test */
    function it_shows_links_to_edit_permissions()
    {
        $permission = factory(Permission::class)->create();
        $this->withoutMiddleware()
            ->get(route('permission.index'))
            ->assertSee(route('permission.edit', $permission->id));
    }
}
