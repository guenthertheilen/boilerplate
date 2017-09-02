<?php

namespace Tests\Browser;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class UserDeleteTest extends DuskTestCase
{
    use DatabaseMigrations;

    private $user;

    public function setUp()
    {
        parent::setUp();
        $this->artisan('scaffold:build');
    }

    /** @test */
    function delete_user()
    {
        $this->user = factory(User::class)->create();

        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->admin())
                ->visit(route('user.index'))
                ->assertSee($this->user->name)
                ->assertSee($this->user->email)
                ->click('#user-edit-' . $this->user->id)
                ->check('#confirm-delete')
                ->press(__('Delete User'))
                ->assertRouteIs('user.index')
                ->assertDontSee($this->user->name)
                ->assertDontSee($this->user->email)
                ->assertSee(__('User was deleted.'));
        });
    }
}
