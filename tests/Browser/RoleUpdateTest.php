<?php

namespace Tests\Browser;

use App\Models\Role;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class RoleUpdateTest extends DuskTestCase
{
    use DatabaseMigrations;

    private $role;

    public function setUp()
    {
        parent::setUp();
        $this->artisan('scaffold:build');
    }

    /** @test */
    function update_role()
    {
        $this->role = factory(Role::class)->create(['name' => 'foo']);

        $this->browse(function (Browser $browser) {
            $browser->loginAs($this->admin())
                ->visit(route('role.index'))
                ->assertSee('foo')
                ->click('#role-edit-' . $this->role->id)
                ->type('name', 'bar')
                ->press(__('Update'))
                ->assertRouteIs('role.index')
                ->assertSee('bar')
                ->assertDontSee('foo')
                ->assertSee(__('Role was updated.'));
        });
    }
}
