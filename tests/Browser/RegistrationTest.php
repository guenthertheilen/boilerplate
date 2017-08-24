<?php

namespace Tests\Browser;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class RegistrationTest extends DuskTestCase
{
    use DatabaseMigrations;

    /** @test */
    public function it_shows_registration_link_for_guest()
    {
        $this->browse(function (Browser $browser) {
            $browser->logout()
                ->visit('/')
                ->clickLink(__('Register'))
                ->assertPathIs(route('register', [], false));
        });
    }

    /** @test */
    public function it_registers_new_user()
    {
        // TODO: Fake mail sending possible?
        $user = [
            'name' => 'foo',
            'email' => 'foo@example.com',
            'active' => 0,
        ];

        $this->browse(function (Browser $browser) use ($user) {
            $browser->logout()
                ->visit(route('register'))
                ->type('name', $user['name'])
                ->type('email', $user['email'])
                ->type('password', 'secret')
                ->type('password_confirmation', 'secret')
                ->press(__('Register'))
                ->assertPathIs('/registration_successful')
                ->visit(route('home'))
                ->assertPathIs('/login'); // Make sure user is not logged in after registration
        });

        $this->assertDatabaseHas('users', $user);
    }
}
