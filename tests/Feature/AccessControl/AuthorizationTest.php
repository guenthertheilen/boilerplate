<?php

namespace Tests\Feature\AccessControl;

use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class AuthorizationTest extends TestCase
{
    use DatabaseMigrations;

    private $permission;
    private $adminRole;
    private $user;
    private $admin;

    /**
     * The test route is defined in routes/web.php.
     * It seems there is no way to define a named route on the fly.
     */
    protected function setUp()
    {
        parent::setUp();

        $this->permission = factory(Permission::class)->create(['name' => 'A1IboUB4N6w27hLNMeKnecsl7obntg']);

        $this->adminRole = factory(Role::class)->create();
        $this->adminRole->attachPermission($this->permission);

        $this->user = factory(User::class)->create();

        $this->admin = factory(User::class)->create();
        $this->admin->attachRole($this->adminRole);
    }

    /** @test */
    function it_authorizes_request()
    {
        $this->actingAs($this->admin)
            ->get(route("A1IboUB4N6w27hLNMeKnecsl7obntg"))
            ->assertSee("gtnbo7lscenKeMNLh72w6N4BUobI1A")
            ->assertStatus(200);
    }

    /** @test */
    function it_denies_request()
    {
        $this->actingAs($this->user)
            ->get(route("A1IboUB4N6w27hLNMeKnecsl7obntg"))
            ->assertDontSee("gtnbo7lscenKeMNLh72w6N4BUobI1A")
            ->assertStatus(403);
    }
}
