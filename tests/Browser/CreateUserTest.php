<?php

namespace Tests\Browser;

use App\Models\Role;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class CreateUserTest extends DuskTestCase
{
    use DatabaseMigrations;

    private $admin;

    public function setUp()
    {
        parent::setUp();
        $this->artisan('scaffold:build');

        $this->admin = $this->admin();
    }

    /** @test */
    function create_new_user()
    {
        // TODO: Fake mail sending possible?
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->admin)
                ->visit(route('user.index'))
                ->assertDontSee('Jimmy McGill')
                ->assertDontSee('jimmy@example.com')
                ->clickLink(__('Add User'))
                ->type('name', 'Jimmy McGill')
                ->type('email', 'jimmy@example.com')
                ->check('#role-admin')
                ->press(__('Create User'))
                ->assertRouteIs('user.index')
                ->assertSee('Jimmy McGill')
                ->assertSee('jimmy@example.com')
                ->assertSee(__('User was created.'));
        });

        $user = User::whereName('Jimmy McGill')->first();

        $this->assertTrue($user->hasRole(app(Role::class)->defaultRole()));
        $this->assertTrue($user->hasRole('admin'));
    }
}
