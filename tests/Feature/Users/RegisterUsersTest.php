<?php

namespace Tests\Feature\Users;

use App\Events\UserCreated;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class RegisterUsersTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    function register_new_user()
    {
        $this->post(route('register'), [
            'name' => 'John Doe',
            'email' => 'johndoe@example.com',
            'password' => 'secret',
            'password_confirmation' => 'secret'
        ]);

        $this->assertDatabaseHas('users', [
            'name' => 'John Doe',
            'email' => 'johndoe@example.com',
            'active' => 0
        ]);

        $this->assertTrue(Auth::attempt([
            'email' => 'johndoe@example.com',
            'password' => 'secret'
        ]));
    }

    /** @test */
    function dispatch_event_after_registering_user()
    {
        Event::fake();

        $this->post(route('register'), [
            'name' => 'John Doe',
            'email' => 'johndoe@example.com',
            'password' => 'secret',
            'password_confirmation' => 'secret'
        ]);

        Event::assertDispatched(UserCreated::class);
    }

    /** @test */
    function user_registration_requires_name()
    {
        $this->post(route('register'), [
            'email' => 'johndoe@example.com',
            'password' => 'secret',
            'password_confirmation' => 'secret'
        ]);

        $this->assertDatabaseMissing('users', [
            'email' => 'johndoe@example.com'
        ]);
    }

    /** @test */
    function user_registration_requires_email()
    {
        $this->post(route('register'), [
            'name' => 'John Doe',
            'password' => 'secret',
            'password_confirmation' => 'secret'
        ]);

        $this->assertDatabaseMissing('users', [
            'name' => 'John Doe'
        ]);
    }

    /** @test */
    function user_registration_requires_valid_email()
    {
        $this->post(route('register'), [
            'name' => 'John Doe',
            'email' => 'not-an-email',
            'password' => 'secret',
            'password_confirmation' => 'secret'
        ]);

        $this->assertDatabaseMissing('users', [
            'name' => 'John Doe'
        ]);
    }


    /** @test */
    function user_registration_requires_password()
    {
        $this->post(route('register'), [
            'name' => 'John Doe',
            'email' => 'johndoe@example.com',
            'password_confirmation' => 'secret'
        ]);

        $this->assertDatabaseMissing('users', [
            'name' => 'John Doe'
        ]);
    }

    /** @test */
    function user_registration_requires_password_of_sufficient_length()
    {
        $this->post(route('register'), [
            'name' => 'John Doe',
            'email' => 'johndoe@example.com',
            'password' => 'short',
            'password_confirmation' => 'short'
        ]);

        $this->assertDatabaseMissing('users', [
            'name' => 'John Doe'
        ]);
    }

    /** @test */
    function user_registration_requires_password_confirmation()
    {
        $this->post(route('register'), [
            'name' => 'John Doe',
            'email' => 'johndoe@example.com',
            'password' => 'secret'
        ]);

        $this->assertDatabaseMissing('users', [
            'name' => 'John Doe'
        ]);
    }

    /** @test */
    function user_registration_requires_matching_password_confirmation()
    {
        $this->post(route('register'), [
            'name' => 'John Doe',
            'email' => 'johndoe@example.com',
            'password' => 'secret',
            'password_confirmation' => 'something_else'
        ]);

        $this->assertDatabaseMissing('users', [
            'name' => 'John Doe'
        ]);
    }

    /** @test */
    function user_registration_requires_unique_email()
    {
        $oldUser = factory(User::class)->create([
            'name' => 'John',
            'email' => 'foo@bar.com'
        ]);

        $this->post(route('register'), [
            'name' => 'Jane',
            'email' => 'foo@bar.com',
            'password' => 'secret',
            'password_confirmation' => 'secret'
        ]);

        $this->assertDatabaseHas('users', [
            'id' => $oldUser->id,
            'name' => 'John',
            'email' => 'foo@bar.com',
        ]);

        $this->assertDatabaseMissing('users', [
            'name' => 'Jane'
        ]);
    }

    /** @test */
    function attach_default_role_to_user_after_registration()
    {
        $this->post(route('register'), [
            'name' => 'John Doe',
            'email' => 'johndoe@example.com',
            'password' => 'secret',
            'password_confirmation' => 'secret'
        ]);

        $user = User::whereName('John Doe')->first();

        $this->assertTrue($user->hasRole('user'));
    }
}
