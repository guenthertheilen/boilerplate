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
            'name' => env('ADMIN_NAME'),
            'email' => env('ADMIN_EMAIL')
        ]);

        $this->assertTrue(Auth::validate([
            'email' => env('ADMIN_EMAIL'),
            'password' => env('ADMIN_PASSWORD')
        ]));

        $admin = User::where('name', '=', env('ADMIN_NAME'))->first();
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
        ];

        $this->actingAs(User::where('name', '=', env('ADMIN_NAME'))->first());

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
