<?php

namespace Tests\Browser;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class UserUpdateTest extends DuskTestCase
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
            'email' => 'jimmy@example.com',
        ]);

        $this->admin = $this->admin();
    }

    /** @test */
    function change_username_and_email_and_roles()
    {
        $this->assertTrue($this->user->hasRole('user'));
        $this->assertFalse($this->user->hasRole('admin'));

        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->admin)
                ->visit(route('user.index'))
                ->assertSee('Jimmy McGill')
                ->assertSee('jimmy@example.com')
                ->click('#user-edit-' . $this->user->id)
                ->type('name', 'Kim Wexler')
                ->type('email', 'kim@example.com')
                ->uncheck('#role-user')
                ->check('#role-admin')
                ->press(__('Update'))
                ->assertRouteIs('user.index')
                ->assertSee('Kim Wexler')
                ->assertSee('kim@example.com')
                ->assertDontSee('Jimmy McGill')
                ->assertDontSee('jimmy@example.com')
                ->assertSee(__('User was updated.'));
        });

        $this->user->refresh();
        $this->assertFalse($this->user->hasRole('user'));
        $this->assertTrue($this->user->hasRole('admin'));
    }
}
