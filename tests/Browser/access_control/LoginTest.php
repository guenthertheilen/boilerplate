<?php

namespace Tests\Browser\access_control;

use Auth;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class LoginTest extends DuskTestCase
{
    /** @test */
    function it_shows_login_page_if_guest_tries_to_access_homepage()
    {
        Auth::logout();

        $this->browse(function (Browser $browser) {
            $browser->visit('/')
                ->assertPathIs(route('login', [], false));
        });
    }
}
