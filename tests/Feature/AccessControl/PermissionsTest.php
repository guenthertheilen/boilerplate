<?php

namespace Tests\Feature\AccessControl;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class PermissionsTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    function it_adds_new_permission()
    {
        $this->withoutMiddleware()
            ->post(route('permission.store'), ['name' => 'foo']);

        $this->assertDatabaseHas('permissions', ['name' => 'foo']);
    }
}
