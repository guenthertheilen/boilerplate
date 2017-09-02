<?php

namespace Tests\Browser;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class PermissionCreateTest extends DuskTestCase
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
    function create_new_permission()
    {
        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->admin)
                ->visit(route('permission.index'))
                ->assertDontSee('some-new-permission')
                ->clickLink(__('Add Permission'))
                ->type('name', 'some-new-permission')
                ->press(__('Create Permission'))
                ->assertRouteIs('permission.index')
                ->assertSee('some-new-permission')
                ->assertSee(__('Permission was created.'));
        });
    }
}
