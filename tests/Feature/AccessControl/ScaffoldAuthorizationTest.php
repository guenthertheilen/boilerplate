<?php

namespace Tests\Feature\AccessControl;

use Auth;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class ScaffoldAuthorizationTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    function it_generates_admin_user()
    {
        $this->artisan("scaffold:build");

        $this->assertDatabaseHas('users',[
            'name' => env('ADMIN_NAME'),
            'email' => env('ADMIN_EMAIL')
        ]);

        $this->assertTrue(Auth::validate([
            'email' => env('ADMIN_EMAIL'),
            'password' => env('ADMIN_PASSWORD')
        ]));
    }


}
