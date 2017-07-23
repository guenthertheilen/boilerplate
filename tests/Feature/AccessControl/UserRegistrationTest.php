<?php

namespace Tests\Feature\AccessControl;

use App\Models\User;
use Auth;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class UserRegistrationTest extends TestCase
{
    use DatabaseMigrations;

    protected function setUp()
    {
        parent::setUp();
    }

    /** @test */
    function it_registers_new_user()
    {
        $this->post(route('register'), [
            'name' => 'John Doe',
            'email' => 'johndoe@example.com',
            'password' => 'secret',
            'password_confirmation' => 'secret'
        ]);

        $this->assertDatabaseHas('users', [
            'name' => 'John Doe',
            'email' => 'johndoe@example.com'
        ]);

        $this->assertTrue(Auth::attempt([
            'email' => 'johndoe@example.com',
            'password' => 'secret'
        ]));
    }

    /** @test */
    function it_does_not_register_user_without_name()
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
    function it_does_not_register_user_without_email()
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
    function it_does_not_register_user_with_invalid_email()
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
    function it_does_not_register_user_without_password()
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
    function it_does_not_register_user_with_too_short_password()
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
    function it_does_not_register_user_without_password_confirmation()
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
    function it_does_not_register_user_without_matching_password_confirmation()
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
    function it_does_not_register_user_without_unique_email()
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
            'name' => 'Jane',
            'email' => 'foo@bar.com',
        ]);
    }
}
