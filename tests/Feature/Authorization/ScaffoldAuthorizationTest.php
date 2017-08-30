<?php

namespace Tests\Feature\Authorization;

use App\Models\User;
use App\Services\Authorizer;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class ScaffoldAuthorizationTest extends TestCase
{
    use DatabaseMigrations;

    function setUp()
    {
        parent::setUp();

        $this->artisan("scaffold:build");
    }

    /** @test */
    function generate_admin_user()
    {
        $this->assertDatabaseHas('users', [
            'name' => config('scaffold.admin_name'),
            'email' => config('scaffold.admin_email'),
            'active' => 0
        ]);

        $admin = User::whereName(config('scaffold.admin_name'))->first();
        $this->assertTrue($admin->hasRole('admin'));
    }

    /** @test */
    function scaffold_admin_permissions()
    {
        $adminPermissions = [
            'permission.create',
            'permission.destroy',
            'permission.edit',
            'permission.index',
            'permission.show',
            'permission.store',
            'permission.update',
            'role.create',
            'role.destroy',
            'role.edit',
            'role.index',
            'role.show',
            'role.store',
            'role.update',
            'user.create',
            'user.destroy',
            'user.edit',
            'user.index',
            'user.show',
            'user.store',
            'user.update',
        ];

        $this->actingAs(User::whereName(config('scaffold.admin_name'))->first());

        foreach ($adminPermissions as $adminPermission) {
            $this->assertTrue(app(Authorizer::class)->allows($adminPermission));
        }
    }

    /** @test */
    function scaffold_user_permissions()
    {
        $user = factory(User::class)->create();
        $user->attachRole('user');

        $this->actingAs($user)
            ->get(route('home'))
            ->assertStatus(200);
    }
}
