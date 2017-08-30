<?php

namespace Tests\Feature\Permissions;

use App\Models\Permission;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Tests\TestCase;

class UpdatePermissionsTest extends TestCase
{
    use DatabaseMigrations, WithoutMiddleware;

    /** @test */
    function show_links_to_edit_permission()
    {
        $permission = factory(Permission::class)->create();

        $this->get(route('permission.index'))
            ->assertSee(route('permission.edit', $permission->id));
    }
}
