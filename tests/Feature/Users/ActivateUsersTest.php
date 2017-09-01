<?php

namespace Tests\Feature\Users;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class ActivateUsersTest extends TestCase
{
    /** @test */
    function activate_user()
    {
        $user = factory(User::class)->create(['active' => 0]);

        $this->assertFalse($user->isActive());
        $this->assertNotNull($user->activation_token);

        $this->get(route('user.activate', $user->activation_token))
            ->assertRedirect(route('login'));

        $this->assertTrue($user->fresh()->isActive());
    }

    /** @test */
    function permit_user_activation_when_logged_in()
    {
        $user1 = factory(User::class)->create();
        $user2 = factory(User::class)->create(['active' => 0]);

        $this->actingAs($user1)
            ->get(route('user.activate', $user2->activation_token))
            ->assertRedirect(route('home'));

        $this->assertFalse($user2->fresh()->isActive());
    }

    /** @test */
    function asks_user_to_set_password_if_none_is_set()
    {
        $user = factory(User::class)->create(['active' => 0, 'password' => '']);

        $this->get(route('user.activate', $user->activation_token))
            ->assertRedirect(route('password.create', $user->activation_token));
    }

    /** @test */
    function set_user_password()
    {
        $user = factory(User::class)->create(['active' => 0, 'password' => '']);

        $this->post(route('password.store'), [
            'email' => $user->email,
            'activation_token' => $user->activation_token,
            'password' => 'new-password',
            'password_confirmation' => 'new-password',
        ])->assertRedirect(route('user.activate', $user->activation_token));

        $this->assertTrue(Auth::validate(['email' => $user->email, 'password' => 'new-password']));
    }

    /** @test */
    function password_creation_requires_email()
    {
        $user = factory(User::class)->create(['active' => 0, 'password' => '']);

        $this->post(route('password.store'), [
            'email' => '',
            'activation_token' => $user->activation_token,
            'password' => 'new-password',
            'password_confirmation' => 'new-password',
        ])->assertSessionHasErrors(['email']);
    }

    /** @test */
    function password_creation_requires_valid_email()
    {
        $user = factory(User::class)->create(['active' => 0, 'password' => '']);

        $this->post(route('password.store'), [
            'email' => 'not-an-email',
            'activation_token' => $user->activation_token,
            'password' => 'new-password',
            'password_confirmation' => 'new-password',
        ])->assertSessionHasErrors(['email']);
    }

    /** @test */
    function password_creation_requires_token()
    {
        $user = factory(User::class)->create(['active' => 0, 'password' => '']);

        $this->post(route('password.store'), [
            'email' => $user->email,
            'activation_token' => '',
            'password' => 'new-password',
            'password_confirmation' => 'new-password',
        ])->assertSessionHasErrors(['activation_token']);
    }

    /** @test */
    function password_creation_requires_existing_token()
    {
        $user = factory(User::class)->create(['active' => 0, 'password' => '']);

        $this->post(route('password.store'), [
            'email' => $user->email,
            'activation_token' => 'invalid-token',
            'password' => 'new-password',
            'password_confirmation' => 'new-password',
        ])->assertSessionHasErrors(['email']);
    }

    /** @test */
    function password_creation_requires_existing_email()
    {
        $user = factory(User::class)->create(['active' => 0, 'password' => '', 'email' => 'foo@example.com']);

        $this->post(route('password.store'), [
            'email' => 'bar@example.com',
            'activation_token' => $user->activation_token,
            'password' => 'new-password',
            'password_confirmation' => 'new-password',
        ])->assertSessionHasErrors(['email']);
    }

    /** @test */
    function password_creation_requires_matching_token_and_email()
    {
        $user1 = factory(User::class)->create(['active' => 0, 'password' => '']);
        $user2 = factory(User::class)->create(['active' => 0, 'password' => '']);

        $this->post(route('password.store'), [
            'email' => $user1->email,
            'activation_token' => $user2->activation_token,
            'password' => 'new-password',
            'password_confirmation' => 'new-password',
        ])->assertSessionHasErrors(['email']);
    }

    /** @test */
    function password_creation_requires_password_confirmation()
    {
        $user = factory(User::class)->create(['active' => 0, 'password' => '']);

        $this->post(route('password.store'), [
            'email' => $user->email,
            'activation_token' => $user->activation_token,
            'password' => 'new-password',
            'password_confirmation' => 'new-password-foo',
        ])->assertSessionHasErrors(['password']);
    }

    /** @test */
    function password_creation_requires_password()
    {
        $user = factory(User::class)->create(['active' => 0, 'password' => '']);

        $this->post(route('password.store'), [
            'email' => $user->email,
            'activation_token' => $user->activation_token,
            'password' => '',
            'password_confirmation' => '',
        ])->assertSessionHasErrors(['password']);
    }

    /** @test */
    function password_creation_requires_password_of_sufficient_length()
    {
        $user = factory(User::class)->create(['active' => 0, 'password' => '']);

        $this->post(route('password.store'), [
            'email' => $user->email,
            'activation_token' => $user->activation_token,
            'password' => 'short',
            'password_confirmation' => 'short',
        ])->assertSessionHasErrors(['password']);
    }

    /** @test */
    function delete_activation_token_after_user_is_activated()
    {
        $user = factory(User::class)->create(['active' => 0, 'activation_token' => 'foo']);

        $this->get(route('user.activate', $user->activation_token));

        $this->assertSame(null, $user->fresh()->activation_token);
    }
}
