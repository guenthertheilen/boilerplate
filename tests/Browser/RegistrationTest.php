<?php

namespace Tests\Browser;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class RegistrationTest extends DuskTestCase
{
    use DatabaseMigrations;

    /** @test */
    public function itShowsRegistrationLinkForGuest()
    {
        $this->browse(function (Browser $browser) {
            $browser->logout()
                ->visit('/')
                ->clickLink(__('Register'))
                ->assertPathIs(route('register', [], false));
        });
    }

    /** @test */
    public function itRegistersNewUser()
    {
        // TODO: Fake mail sending possible?
        $user = [
            'name' => 'foo',
            'email' => 'foo@example.com',
            'active' => 0
        ];

        $this->browse(function (Browser $browser) use ($user) {
            $browser->logout()
                ->visit(route('register'))
                ->type('name', $user['name'])
                ->type('email', $user['email'])
                ->type('password', 'secret')
                ->type('password_confirmation', 'secret')
                ->press(__('Register'))
                ->assertPathIs('/');
        });

        $this->assertDatabaseHas('users', $user);
    }
}
