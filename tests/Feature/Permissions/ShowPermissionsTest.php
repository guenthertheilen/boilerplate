<?php

namespace Tests\Feature\Permissions;

use App\Models\Permission;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Tests\TestCase;

class ShowPermissionsTest extends TestCase
{
    use DatabaseMigrations, WithoutMiddleware;

    /** @test */
    function it_shows_list_of_permissions()
    {
        factory(Permission::class)->create(['name' => 'permission_1']);
        factory(Permission::class)->create(['name' => 'permission_2']);

        $this->get(route('permission.index'))
            ->assertSeeText('permission_1')
            ->assertSeeText('permission_2');
    }
}
