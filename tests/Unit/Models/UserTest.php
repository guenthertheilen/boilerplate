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
    public function itAttachesRoleToUser()
    {
        $user = factory(User::class)->create();
        $role = factory(Role::class)->create();

        $user->attachRole($role);

        $this->assertContains($role->id, $user->roles->pluck('id'));
    }

    /** @test */
    public function itAttachesRoleByNameToUser()
    {
        $user = factory(User::class)->create();
        $role = factory(Role::class)->create(['name' => 'foobar']);

        $user->attachRole('foobar');

        $this->assertContains($role->id, $user->roles->pluck('id'));
    }

    /** @test */
    public function itDoesNotAttachSameRoleMoreThanOnce()
    {
        $user = factory(User::class)->create();
        $role = factory(Role::class)->create();

        $user->attachRole($role);
        $user->attachRole($role);
        $user->attachRole($role);

        $this->assertCount(1, $user->roles->where('id', '=', $role->id));
    }

    /** @test */
    public function itDetachesRoleFromUser()
    {
        $user = factory(User::class)->create();
        $role = factory(Role::class)->create();
        $user->attachRole($role);

        $user->detachRole($role);

        $this->assertNotContains($role->id, $user->roles->pluck('id'));
    }

    /** @test */
    public function itMakesSureUserKeepsAtLeastOneRole()
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
    public function itChecksIfUserHasGivenRole()
    {
        $user = factory(User::class)->create();
        $role1 = factory(Role::class)->create();
        $role2 = factory(Role::class)->create();

        $user->attachRole($role1);

        $this->assertTrue($user->hasRole($role1));
        $this->assertFalse($user->hasRole($role2));
    }

    /** @test */
    public function itChecksIfUserHasGivenRoleByNameOfRole()
    {
        $user = factory(User::class)->create();
        $role1 = factory(Role::class)->create(['name' => 'foo']);
        factory(Role::class)->create(['name' => 'bar']);

        $user->attachRole($role1);

        $this->assertTrue($user->hasRole('foo'));
        $this->assertFalse($user->hasRole('bar'));
    }

    /** @test */
    public function itChecksIfUserIsAdmin()
    {
        $user = factory(User::class)->create();
        $adminRole = factory(Role::class)->create(['name' => 'admin']);

        $this->assertFalse($user->isAdmin());

        $user->attachRole($adminRole);

        $this->assertTrue($user->isAdmin());
    }

    /** @test */
    public function itChecksIfUserIsNotAdmin()
    {
        $user = factory(User::class)->create();
        $adminRole = factory(Role::class)->create(['name' => 'admin']);

        $this->assertTrue($user->isNotAdmin());

        $user->attachRole($adminRole);

        $this->assertFalse($user->isNotAdmin());
    }

    /** @test */
    public function itChecksIfUserHasPermissionByName()
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
    public function itGetsAttachedRolesInAlphabeticalOrderAsCommaSeperatedString()
    {
        $user = factory(User::class)->create();

        $role1 = factory(Role::class)->create(['name' => 'xyz']);
        $user->attachRole($role1);

        $role2 = factory(Role::class)->create(['name' => 'abc']);
        $user->attachRole($role2);

        $this->assertEquals('abc, user, xyz', $user->rolesAsString());
    }

    /** @test */
    public function itCreatesActivationToken()
    {
        Event::fake();
        $user = factory(User::class)->create();

        $this->assertNull($user->getAttribute('activation_token'));

        $user->createActivationToken();

        $user->fresh();
        $this->assertNotNull($user->getAttribute('activation_token'));
    }
}
