<?php

namespace Tests\Browser;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class CreateRoleTest extends DuskTestCase
{
    use DatabaseMigrations;

    private $admin;

    public function setUp()
    {
        parent::setUp();
        $this->artisan('scaffold:build');

        $this->admin = $this->admin();
    }

    /** @test */
    function create_new_role()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->admin)
                ->visit(route('role.index'))
                ->assertDontSee('some-new-role')
                ->clickLink(__('Add Role'))
                ->type('name', 'some-new-role')
                ->press(__('Create Role'))
                ->assertRouteIs('role.index')
                ->assertSee('some-new-role')
                ->assertSee(__('Role was created.'));
        });
    }
}
