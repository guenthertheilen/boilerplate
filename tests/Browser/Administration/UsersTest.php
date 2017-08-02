<?php

namespace Tests\Browser\Administration;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class UsersTest extends DuskTestCase
{
    use DatabaseMigrations;

    private $user;
    private $admin;

    public function setUp()
    {
        parent::setUp();
        $this->artisan('scaffold:build');

        $this->user = factory(User::class)->create([
            'name' => 'Jimmy McGill',
            'email' => 'jimmy@example.com'
        ]);
        $this->admin = app(User::class)->where('name', '=', config('scaffold.admin_name'))->first();
    }


    /** @test */
    public function it_changes_username()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->admin)
                ->visit(route('user.edit', $this->user->id))
                ->type('name', 'Kim Wexler')
                ->press(__('Update'))
                ->assertRouteIs('user.index')
                ->assertSee('Kim Wexler')
                ->assertDontSee('Jimmy McGill');
        });
    }

    /** @test */
    public function it_changes_email()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->admin)
                ->visit(route('user.edit', $this->user->id))
                ->type('email', 'kim@example.com')
                ->press(__('Update'))
                ->assertRouteIs('user.index')
                ->assertSee('kim@example.com')
                ->assertDontSee('jimmy@example.com');
        });
    }
}
