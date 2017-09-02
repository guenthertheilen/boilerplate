<?php

namespace Tests\Browser;

use App\Models\Permission;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class PermissionUpdateTest extends DuskTestCase
{
    use DatabaseMigrations;

    private $permission;

    public function setUp()
    {
        parent::setUp();
        $this->artisan('scaffold:build');
    }

    /** @test */
    function update_permission()
    {
        $this->permission = factory(Permission::class)->create(['name' => 'foo']);

        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->admin())
                ->visit(route('permission.index'))
                ->assertSee('foo')
                ->click('#permission-edit-' . $this->permission->id)
                ->type('name', 'bar')
                ->press(__('Update'))
                ->assertRouteIs('permission.index')
                ->assertSee('bar')
                ->assertDontSee('foo')
                ->assertSee(__('Permission was updated.'));
        });
    }
}
