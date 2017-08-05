<?php

namespace Tests\Feature\AccessControl;

use App\Models\User;
use App\Services\Authorizer;
use Auth;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class ScaffoldAuthorizationTest extends TestCase
{
    use DatabaseMigrations;

    protected function setUp()
    {
        parent::setUp();

        $this->artisan("scaffold:build");
    }

    /** @test */
    function it_generates_admin_user()
    {
        $this->assertDatabaseHas('users', [
            'name' => config('scaffold.admin_name'),
            'email' => config('scaffold.admin_email')
        ]);

        $this->assertTrue(Auth::validate([
            'email' => config('scaffold.admin_email'),
            'password' => config('scaffold.admin_password')
        ]));

        $admin = app(User::class)->where('name', '=', config('scaffold.admin_name'))->first();
        $this->assertTrue($admin->hasRole('admin'));
    }

    /** @test */
    function it_scaffolds_permission_for_admin()
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

        $this->actingAs(app(User::class)->where('name', '=', config('scaffold.admin_name'))->first());

        foreach ($adminPermissions as $adminPermission) {
            $this->assertTrue(app(Authorizer::class)->allows($adminPermission));
        }
    }

    /** @test */
    function it_scaffolds_permission_for_user()
    {
        $user = factory(User::class)->create();
        $user->attachRole('user');

        $this->actingAs($user)
            ->get(route('home'))
            ->assertStatus(200);
    }
}
