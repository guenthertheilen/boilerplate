<?php

namespace Tests\Unit\Models;

use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class UserTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    function it_attaches_role_to_user()
    {
        $user = factory(User::class)->create();
        $role = factory(Role::class)->create();

        $user->attachRole($role);

        $this->assertContains($role->id, $user->roles->pluck('id'));
    }

    /** @test */
    function it_attaches_role_by_name_to_user()
    {
        $user = factory(User::class)->create();
        $role = factory(Role::class)->create(['name' => 'foobar']);

        $user->attachRole('foobar');

        $this->assertContains($role->id, $user->roles->pluck('id'));
    }

    /** @test */
    function it_does_not_attach_same_role_more_than_once()
    {
        $user = factory(User::class)->create();
        $role = factory(Role::class)->create();

        $user->attachRole($role);
        $user->attachRole($role);
        $user->attachRole($role);

        $this->assertCount(1, $user->roles->where('id', '=', $role->id));
    }

    /** @test */
    function it_detaches_role_from_user()
    {
        $user = factory(User::class)->create();
        $role = factory(Role::class)->create();
        $user->attachRole($role);

        $user->detachRole($role);

        $this->assertNotContains($role->id, $user->roles->pluck('id'));
    }

    /** @test */
    function it_makes_sure_user_keeps_at_least_one_role()
    {
        $user = factory(User::class)->create();
        $role = factory(Role::class)->create();

        $defaultRoles = $user->roles;

        $user->attachRole($role);

        foreach ($defaultRoles as $defaultRole) {
            $user->detachRole($defaultRole);
        }

        $user->detachRole($role);

        $this->assertTrue($user->hasRole($role));
    }

    /** @test */
    function it_checks_if_user_has_given_role()
    {
        $user = factory(User::class)->create();
        $role1 = factory(Role::class)->create();
        $role2 = factory(Role::class)->create();

        $user->attachRole($role1);

        $this->assertTrue($user->hasRole($role1));
        $this->assertFalse($user->hasRole($role2));
    }

    /** @test */
    function it_checks_if_user_has_given_role_by_name_of_role()
    {
        $user = factory(User::class)->create();
        $role1 = factory(Role::class)->create(['name' => 'foo']);
        factory(Role::class)->create(['name' => 'bar']);

        $user->attachRole($role1);

        $this->assertTrue($user->hasRole('foo'));
        $this->assertFalse($user->hasRole('bar'));
    }

    /** @test */
    function it_checks_if_user_is_admin()
    {
        $user = factory(User::class)->create();
        $adminRole = factory(Role::class)->create(['name' => 'admin']);

        $this->assertFalse($user->isAdmin());

        $user->attachRole($adminRole);

        $this->assertTrue($user->isAdmin());
    }

    /** @test */
    function it_checks_if_user_is_not_admin()
    {
        $user = factory(User::class)->create();
        $adminRole = factory(Role::class)->create(['name' => 'admin']);

        $this->assertTrue($user->isNotAdmin());

        $user->attachRole($adminRole);

        $this->assertFalse($user->isNotAdmin());
    }

    /** @test */
    function it_checks_if_user_has_permission_by_name()
    {
        $permission = factory(Permission::class)->create(['name' => 'foo']);

        $role = factory(Role::class)->create();
        $role->attachPermission($permission);

        $user = factory(User::class)->create();
        $user->attachRole($role);

        $this->assertTrue($user->hasPermission('foo'));
        $this->assertFalse($user->hasPermission('bar'));
    }

    /** @test */
    function it_checks_if_user_is_activated()
    {
        $inactiveUser = factory(User::class)->create(['active' => 0]);
        $activeUser = factory(User::class)->create(['active' => 1]);

        $this->assertFalse($inactiveUser->isActive());
        $this->assertTrue($activeUser->isActive());
    }

    /** @test */
    function it_gets_attached_roles_in_alphabetical_order_as_comma_seperated_string()
    {
        $user = factory(User::class)->create();

        $role1 = factory(Role::class)->create(['name' => 'xyz']);
        $user->attachRole($role1);

        $role2 = factory(Role::class)->create(['name' => 'abc']);
        $user->attachRole($role2);

        $this->assertEquals('abc, user, xyz', $user->rolesAsString());
    }

    /** @test */
    function it_creates_activation_token()
    {
        Event::fake();
        $user = factory(User::class)->create();

        $this->assertNull($user->getAttribute('activation_token'));

        $user->createActivationToken();

        $user->fresh();
        $this->assertNotNull($user->getAttribute('activation_token'));
    }

    /** @test */
    function it_checks_if_user_password_is_set()
    {
        $user1 = factory(User::class)->create(['password' => 'foo']);
        $user2 = factory(User::class)->create(['password' => '']);

        $this->assertFalse($user1->hasNoPassword());
        $this->assertTrue($user2->hasNoPassword());
    }

    /** @test */
    function it_activates_user()
    {
        $user = factory(User::class)->create(['active' => 0, 'activation_token' => 'foo']);

        $user->activate();

        $this->assertTrue($user->isActive());
        $this->assertEquals('', $user->activation_token);
    }
}
