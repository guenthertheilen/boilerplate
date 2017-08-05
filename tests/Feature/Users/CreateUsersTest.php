<?php

namespace Tests\Feature\Users;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Tests\TestCase;

class CreateUsersTest extends TestCase
{
    use DatabaseMigrations, WithoutMiddleware;

    /** @test */
    function it_shows_link_to_create_user()
    {
        $this->get(route('user.index'))
            ->assertSee(route('user.create'));
    }

    /** @test */
    function it_adds_new_user()
    {
        $this->post(route('user.store'), [
            'name' => 'Foo Bar',
            'email' => 'foo@bar.com',
            'password' => 'bizbaz'
        ]);

        $this->assertDatabaseHas('users', ['name' => 'Foo Bar', 'email' => 'foo@bar.com']);
    }
}