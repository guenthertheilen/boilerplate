<?php

namespace Tests\Feature\Users;

use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Tests\TestCase;

class UpdateUsersTest extends TestCase
{
    use DatabaseMigrations, WithoutMiddleware;

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

        $payload = ['name' => 'bar', 'email' => $user->email, 'roles' => [$role->id]];
        $this->patch(route('user.update', $user->id), $payload)
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

        $payload = ['name' => $user->name, 'email' => 'bar@example.com', 'roles' => [$role->id]];
        $this->patch(route('user.update', $user->id), $payload)
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

        $payload = ['name' => $user->name, 'email' => $user->email, 'roles' => [$role2->id]];
        $this->patch(route('user.update', $user->id), $payload)
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

        $payload = ['name' => '', 'email' => $user->email, 'roles' => [$role->id]];
        $this->patch(route('user.update', $user->id), $payload);

        $this->assertDatabaseMissing('users', ['name' => '']);
    }

    /** @test */
    function it_does_not_update_user_without_email()
    {
        $user = factory(User::class)->create(['email' => 'foo@bar.com']);
        $role = factory(Role::class)->create();
        $user->attachRole($role);

        $payload = ['name' => $user->name, 'email' => '', 'roles' => [$role->id]];
        $this->patch(route('user.update', $user->id), $payload);

        $this->assertDatabaseMissing('users', ['email' => '']);
    }

    /** @test */
    function it_does_not_update_user_without_valid_email()
    {
        $user = factory(User::class)->create(['email' => 'foo@bar.com']);
        $role = factory(Role::class)->create();
        $user->attachRole($role);

        $payload = ['name' => $user->name, 'email' => 'not-an-email', 'roles' => [$role->id]];
        $this->patch(route('user.update', $user->id), $payload);

        $this->assertDatabaseMissing('users', ['email' => 'not-an-email']);
    }

    /** @test */
    function it_does_not_update_user_without_unique_email()
    {
        factory(User::class)->create(['email' => 'foo@foo.com']);

        $user = factory(User::class)->create(['email' => 'bar@bar.com']);
        $role = factory(Role::class)->create();
        $user->attachRole($role);

        $payload = ['name' => $user->name, 'email' => 'foo@foo.com', 'roles' => [$role->id]];
        $this->patch(route('user.update', $user->id), $payload);

        $this->assertDatabaseMissing('users', ['id' => $user->id, 'email' => 'foo@foo.com']);
    }

    /** @test */
    function it_does_not_update_user_without_roles()
    {
        $user = factory(User::class)->create();

        $payload = ['name' => $user->name, 'email' => $user->email, 'roles' => []];
        $this->patch(route('user.update', $user->id), $payload);

        $user->refresh();
        $this->assertCount(1, $user->roles);
    }

    /** @test */
    function it_can_remove_admin_role_from_other_users()
    {
        $user1 = factory(User::class)->create();
        $user2 = factory(User::class)->create();
        $adminRole = factory(Role::class)->create(['name' => 'admin']);
        $user2->attachRole($adminRole);

        $payload = ['name' => $user2->name, 'email' => $user2->email, 'roles' => [1]];
        $this->actingAs($user1)
            ->patch(route('user.update', $user2->id), $payload);

        $user2->refresh();
        $this->assertFalse($user1->hasRole('admin'));
    }

    /** @test */
    function it_prevents_user_from_removing_own_admin_role()
    {
        $user = factory(User::class)->create();
        $adminRole = factory(Role::class)->create(['name' => 'admin']);
        $user->attachRole($adminRole);

        $payload = ['name' => $user->name, 'email' => $user->email, 'roles' => [1]];
        $this->actingAs($user)
            ->patch(route('user.update', $user->id), $payload);

        $user->refresh();
        $this->assertTrue($user->hasRole('admin'));
    }
}
