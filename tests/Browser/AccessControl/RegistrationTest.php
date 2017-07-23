<?php

namespace Tests\Browser\AccessControl;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class RegistrationTest extends DuskTestCase
{
    use DatabaseMigrations;

    /** @test */
    function it_shows_registration_link_for_guest()
    {
        $this->browse(function (Browser $browser) {
            $browser->logout()
                ->visit('/')
                ->clickLink(__('Register'))
                ->assertPathIs(route('register', [], false));
        });
    }

    /** @test */
    function it_registers_new_user()
    {
        $user = [
            'name' => 'foo',
            'email' => 'foo@example.com'
        ];
        $this->browse(function (Browser $browser) use ($user) {
            $browser->logout()
                ->visit(route('register'))
                ->type('name', $user['name'])
                ->type('email', $user['email'])
                ->type('password', 'secret')
                ->type('password_confirmation', 'secret')
                ->press(__('Register'))
                ->assertPathIs('/')
                ->logout()
                ->visit('/')
                ->type('email', $user['email'])
                ->type('password', 'secret')
                ->press(__('Login'))
                ->assertPathIs('/');
        });

        $this->assertDatabaseHas('users', $user);
    }
}
