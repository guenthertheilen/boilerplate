<?php

namespace Tests\Feature\Users;

use App\Models\User;
use Auth;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class LogInUsersTest extends TestCase
{
    use DatabaseMigrations;

    protected function setUp()
    {
        parent::setUp();


    }

    /** @test */
    function it_logs_in_active_user_with_valid_crendentials()
    {
        factory(User::class)->create([
            'email' => 'foo@example.com',
            'password' => bcrypt('secret'),
            'active' => 1
        ]);

        $this->assertFalse(Auth::check());

        $this->post(route('login'), [
            'email' => 'foo@example.com',
            'password' => 'secret'
        ]);

        $this->assertTrue(Auth::check());
    }

    /** @test */
    function it_does_not_log_in_inactive_user_with_valid_crendentials()
    {
        factory(User::class)->create([
            'email' => 'foo@example.com',
            'password' => bcrypt('secret'),
            'active' => 0
        ]);

        $this->assertFalse(Auth::check());

        $this->post(route('login'), [
            'email' => 'foo@example.com',
            'password' => 'secret'
        ]);

        $this->assertFalse(Auth::check());
    }

    /** @test */
    function it_does_not_log_in_user_with_invalid_email()
    {
        factory(User::class)->create([
            'email' => 'foo@example.com',
            'password' => bcrypt('secret'),
            'active' => 1
        ]);

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
        factory(User::class)->create([
            'email' => 'foo@example.com',
            'password' => bcrypt('secret'),
            'active' => 1
        ]);

        $this->assertFalse(Auth::check());

        $this->post(route('login'), [
            'email' => 'foo@example.com',
            'password' => 'INVALID'
        ]);

        $this->assertFalse(Auth::check());
    }
}
