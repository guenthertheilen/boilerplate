<?php

namespace Tests\Unit\Models;

use App\Models\Permission;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Collection;
use Tests\TestCase;

class PermissionTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    function it_has_permissions()
    {
        $permission = factory(Permission::class)->create();

        $this->assertInstanceOf(Collection::class, $permission->roles);
    }
}
