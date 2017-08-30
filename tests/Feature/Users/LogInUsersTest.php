<?php

namespace Tests\Feature\Users;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class LogInUsersTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    function log_in_active_user_with_valid_crendentials()
    {
        $user = factory(User::class)->create(['password' => bcrypt('secret'), 'active' => 1]);

        $this->assertFalse(Auth::check());

        $this->post(route('login'), [
            'email' => $user->email,
            'password' => 'secret'
        ]);

        $this->assertTrue(Auth::check());
    }

    /** @test */
    function do_not_log_in_inactive_user_with_valid_crendentials()
    {
        $user = factory(User::class)->create(['password' => bcrypt('secret'), 'active' => 0]);

        $this->assertFalse(Auth::check());

        $this->post(route('login'), [
            'email' => $user->email,
            'password' => 'secret'
        ]);

        $this->assertFalse(Auth::check());
    }

    /** @test */
    function do_not_log_in_user_with_invalid_email()
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
    function do_not_log_in_user_with_invalid_password()
    {
        $user = factory(User::class)->create(['password' => bcrypt('secret'), 'active' => 1]);

        $this->assertFalse(Auth::check());

        $this->post(route('login'), [
            'email' => $user->email,
            'password' => 'INVALID'
        ]);

        $this->assertFalse(Auth::check());
    }
}
