<?php

namespace Tests\Feature\AccessControl;

use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Tests\TestCase;

class UsersTest extends TestCase
{
    use DatabaseMigrations, WithoutMiddleware;

    /** @test */
    function it_shows_list_of_users()
    {
        factory(User::class)->create(['name' => 'Jane Doe', 'email' => 'jane@foo.de']);
        factory(User::class)->create(['name' => 'John Doe', 'email' => 'john@bar.org']);

        $this->get(route('user.index'))
            ->assertSeeText('Jane Doe')
            ->assertSeeText('jane@foo.de')
            ->assertSeeText('John Doe')
            ->assertSeeText('john@bar.org');
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

    /** @test */
    function it_shows_links_to_edit_user()
    {
        $user = factory(User::class)->create();

        $this->get(route('user.index'))
            ->assertSee(route('user.edit', $user->id));
    }

    /** @test */
    function it_updates_user()
    {
        $user = factory(User::class)->create(['name' => 'foo', 'email' => 'foo@example.com']);
        $role1 = factory(Role::class)->create();
        $role2 = factory(Role::class)->create();
        $user->attachRole($role1);

        $this->patch(route('user.update', $user->id), ['name' => 'bar', 'email' => 'bar@example.com', 'roles' => [$role2->id]]);

        $this->assertDatabaseHas('users', ['name' => 'bar', 'email' => 'bar@example.com']);
    }
}
