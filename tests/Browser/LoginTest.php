<?php

namespace Tests\Browser;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class LoginTest extends DuskTestCase
{
    use DatabaseMigrations;

    function setUp()
    {
        parent::setUp();

        factory(User::class)->create([
            'email' => 'abc@example.com',
            'password' => bcrypt('123456')
        ]);
    }

    /** @test */
    function it_shows_login_page_if_guest_tries_to_access_homepage()
    {
        $this->browse(function (Browser $browser) {
            $browser->logout()
                ->visit('/')
                ->assertRouteIs('login');
        });
    }

    /** @test */
    function it_logs_in_visitor_with_valid_credentials()
    {
        $this->browse(function (Browser $browser) {
            $browser->logout()
                ->visit('/')
                ->type('email', 'abc@example.com')
                ->type('password', '123456')
                ->press(__('Login'))
                ->assertRouteIs('home');
        });
    }
}
