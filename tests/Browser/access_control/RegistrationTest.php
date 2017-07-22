<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class RegistrationTest extends DuskTestCase
{
    /** @test */
    function it_shows_registration_link()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/')
                ->clickLink(__('Register'))
                ->assertPathIs(route('register', [], false));
        });
    }
}
