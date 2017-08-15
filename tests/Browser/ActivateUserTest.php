<?php

namespace Tests\Browser;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class ActivateUserTest extends DuskTestCase
{
    use DatabaseMigrations;

    /** @test */
    function it_activates_user_that_has_no_password_yet()
    {
        $user = factory(User::class)->create(['password' => '', 'active' => 0]);
        
        $this->browse(function (Browser $browser) use($user) {
            $browser->visit(route('user.activate', $user->activation_token))
                ->assertRouteIs('password.create', $user->activation_token)
                ->type('email', $user->email)
                ->type('password', 'my-new-password')
                ->type('password_confirmation', 'my-new-password')
                ->press(__('Save Password'))
                ->assertRouteIs('login');
        });

        $user->refresh();

        $this->assertTrue($user->isActive());
        $this->asswerTrue(Auth::validate(['email' => $user->email, 'password' => 'my-new-password']));
    }
}
