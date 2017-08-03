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
    function it_updates_users_name()
    {
        $user = factory(User::class)->create(['name' => 'foo']);
        $role = factory(Role::class)->create();
        $user->attachRole($role);

        $this->patch(route('user.update', $user->id), ['name' => 'bar', 'email' => $user->email, 'roles' => [$role->id]])
            ->assertRedirect(route('user.index'));

        $this->assertDatabaseHas('users', ['name' => 'bar', 'email' => $user->email]);
        $this->assertDatabaseMissing('users', ['name' => 'foo']);

        $user->refresh();
        $this->assertTrue($user->hasRole($role));
    }

    /** @test */
    function it_updates_users_email()
    {
        $user = factory(User::class)->create(['email' => 'foo@example.com']);
        $role = factory(Role::class)->create();
        $user->attachRole($role);

        $this->patch(route('user.update', $user->id), ['name' => $user->name, 'email' => 'bar@example.com', 'roles' => [$role->id]])
            ->assertRedirect(route('user.index'));

        $this->assertDatabaseHas('users', ['name' => $user->name, 'email' => 'bar@example.com']);
        $this->assertDatabaseMissing('users', ['email' => 'foo@example.com']);

        $user->refresh();
        $this->assertTrue($user->hasRole($role));
    }

    /** @test */
    function it_updates_users_roles()
    {
        $user = factory(User::class)->create();
        $role1 = factory(Role::class)->create();
        $role2 = factory(Role::class)->create();
        $user->attachRole($role1);

        $this->patch(route('user.update', $user->id), ['name' => $user->name, 'email' => $user->email, 'roles' => [$role2->id]])
            ->assertRedirect(route('user.index'));

        $this->assertDatabaseHas('users', ['name' => $user->name, 'email' => $user->email]);
        $this->assertCount(1, User::all());

        $user->refresh();
        $this->assertFalse($user->hasRole($role1));
        $this->assertTrue($user->hasRole($role2));
    }

    /** @test */
    function it_does_not_update_user_without_name()
    {
        $user = factory(User::class)->create(['name' => 'foo']);
        $role = factory(Role::class)->create();
        $user->attachRole($role);

        $this->patch(route('user.update', $user->id), ['name' => '', 'email' => $user->email, 'roles' => [$role->id]]);

        $this->assertDatabaseMissing('users', ['name' => '']);
    }

    /** @test */
    function it_does_not_update_user_without_email()
    {
        $user = factory(User::class)->create(['email' => 'foo@bar.com']);
        $role = factory(Role::class)->create();
        $user->attachRole($role);

        $this->patch(route('user.update', $user->id), ['name' => $user->name, 'email' => '', 'roles' => [$role->id]]);

        $this->assertDatabaseMissing('users', ['email' => '']);
    }
}
