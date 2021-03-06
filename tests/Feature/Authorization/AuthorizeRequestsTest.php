<?php

namespace Tests\Feature\Authorization;

use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use App\Services\Authorizer;
use Tests\TestCase;

class AuthorizeRequestsTest extends TestCase
{
    private $permission;
    private $adminRole;
    private $user;
    private $admin;

    /**
     * The test route is defined in routes/web.php.
     * It seems there is no way to define a named route on the fly.
     */
    function setUp()
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
    function authorize_request()
    {
        $this->actingAs($this->admin)
            ->get(route("A1IboUB4N6w27hLNMeKnecsl7obntg"))
            ->assertSee("gtnbo7lscenKeMNLh72w6N4BUobI1A")
            ->assertStatus(200);

        $this->assertTrue(app(Authorizer::class)->allows("A1IboUB4N6w27hLNMeKnecsl7obntg"));
        $this->assertFalse(app(Authorizer::class)->denies("A1IboUB4N6w27hLNMeKnecsl7obntg"));
    }

    /** @test */
    function deny_request()
    {
        $this->actingAs($this->user)
            ->get(route("A1IboUB4N6w27hLNMeKnecsl7obntg"))
            ->assertDontSee("gtnbo7lscenKeMNLh72w6N4BUobI1A")
            ->assertStatus(403);

        $this->assertFalse(app(Authorizer::class)->allows("A1IboUB4N6w27hLNMeKnecsl7obntg"));
        $this->assertTrue(app(Authorizer::class)->denies("A1IboUB4N6w27hLNMeKnecsl7obntg"));
    }
}
