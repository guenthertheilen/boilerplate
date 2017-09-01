<?php

namespace Tests\Browser;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Auth;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class ActivateUserTest extends DuskTestCase
{
    // TODO: Figure out a way to replace DatabaseMigration with RefreshDatabase.
    // This only seems to work with in-memory databases, which do not seem to work with Dusk.
    use DatabaseMigrations;

    /** @test */
    function activate_user_that_has_no_password_yet()
    {
        $user = factory(User::class)->create(['password' => '', 'active' => 0]);

        $this->browse(function (Browser $browser) use ($user) {
            $browser->visit(route('user.activate', $user->activation_token))
                ->assertRouteIs('password.create', $user->activation_token)
                ->type('email', $user->email)
                ->type('password', 'my-new-password')
                ->type('password_confirmation', 'my-new-password')
                ->press(__('Save Password'))
                ->assertRouteIs('login')
                ->assertSee(__('Your account was activated. Please log in now.'));
        });

        $user->refresh();

        $this->assertTrue($user->isActive());
        $this->assertTrue(Auth::validate(['email' => $user->email, 'password' => 'my-new-password']));
    }

    /** @test */
    function activate_user_that_has_password()
    {
        $user = factory(User::class)->create(['password' => 'something', 'active' => 0]);

        $this->browse(function (Browser $browser) use ($user) {
            $browser->visit(route('user.activate', $user->activation_token))
                ->assertRouteIs('login')
                ->assertSee(__('Your account was activated. Please log in now.'));
        });

        $user->refresh();

        $this->assertTrue($user->isActive());
    }
}
