<?php

namespace Tests\Feature\Roles;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Tests\TestCase;

class CreateRolesTest extends TestCase
{
    use DatabaseMigrations, WithoutMiddleware;

    /** @test */
    function it_adds_new_role()
    {
        $this->post(route('role.store'), ['name' => 'foobar']);

        $this->assertDatabaseHas('roles', ['name' => 'foobar']);
    }
}
