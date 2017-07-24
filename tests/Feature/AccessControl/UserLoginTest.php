<?php

namespace Tests\Feature\AccessControl;

use App\Models\User;
use Auth;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class UserLoginTest extends TestCase
{
    protected function setUp()
    {
        parent::setUp();

        factory(User::class)->create([
            'email' => 'foo@example.com',
            'password' => bcrypt('secret')
        ]);
    }

    use DatabaseMigrations;

    /** @test */
    function it_logs_in_user_with_valid_crendentials()
    {
        $this->assertFalse(Auth::check());

        $this->post(route('login'), [
            'email' => 'foo@example.com',
            'password' => 'secret'
        ]);

        $this->assertTrue(Auth::check());
    }

    /** @test */
    function it_does_not_log_in_user_with_invalid_email()
    {
        $this->assertFalse(Auth::check());

        $this->post(route('login'), [
            'email' => 'INVALID@example.com',
            'password' => 'secret'
        ]);

        $this->assertFalse(Auth::check());
    }

    /** @test */
    function it_does_not_log_in_user_with_invalid_password()
    {
        $this->assertFalse(Auth::check());

        $this->post(route('login'), [
            'email' => 'foo@example.com',
            'password' => 'INVALID'
        ]);

        $this->assertFalse(Auth::check());
    }
}
