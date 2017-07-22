<?php

namespace Tests\Browser\AccessControl;

use App\User;
use Auth;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class LoginTest extends DuskTestCase
{
    use DatabaseMigrations;

    public function setUp()
    {
        parent::setUp();
        Auth::logout();
        factory(User::class)->create([
            'email' => 'abc@example.com',
            'password' => bcrypt('123456')
        ]);
    }

    /** @test */
    function it_shows_login_page_if_guest_tries_to_access_homepage()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/')
                ->assertPathIs(route('login', [], false));
        });
    }

//    /** @test */
//    function it_logs_in_visitor_with_valid_credentials()
//    {
//        $this->browse(function (Browser $browser) {
//            $browser->visit('/')
//                ->type('email', 'abc@example.com')
//                ->type('password', '123456')
//                ->press(__('Login'))
//                ->assertPathIs('/');
//        });
//    }
//
//    /** @test */
//    function it_does_not_log_in_visitor_with_invalid_credentials()
//    {
//        $this->browse(function (Browser $browser) {
//            $browser->visit('/')
//                ->type('email', 'INVALID@example.com')
//                ->type('password', '123456')
//                ->press(__('Login'))
//                ->type('email', 'abc@example.com')
//                ->type('password', 'INVALID')
//                ->press(__('Login'))
//                ->assertPathIs(route('login', [], false));
//        });
//    }
}
