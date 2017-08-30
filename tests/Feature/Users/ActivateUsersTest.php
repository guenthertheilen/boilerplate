<?php

namespace Tests\Feature\Users;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class ActivateUsersTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function it_activates_user()
    {
        $user = factory(User::class)->create(['active' => 0]);

        $this->assertFalse($user->isActive());
        $this->assertNotNull($user->activation_token);

        $this->get(route('user.activate', $user->activation_token))
            ->assertRedirect(route('login'));

        $this->assertTrue($user->fresh()->isActive());
    }

    /** @test */
    public function it_does_not_activate_user_if_currently_logged_in_as_other_user()
    {
        $user1 = factory(User::class)->create();
        $user2 = factory(User::class)->create(['active' => 0]);

        $this->actingAs($user1)
            ->get(route('user.activate', $user2->activation_token))
            ->assertRedirect(route('home'));

        $this->assertFalse($user2->fresh()->isActive());
    }

    /** @test */
    public function it_asks_user_to_set_password_if_no_password_is_set_yet()
    {
        $user = factory(User::class)->create(['active' => 0, 'password' => '']);

        $this->get(route('user.activate', $user->activation_token))
            ->assertRedirect(route('password.create', $user->activation_token));
    }

    /** @test */
    public function it_sets_user_password()
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
    public function it_does_not_set_user_password_without_email()
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
    public function it_does_not_set_user_password_with_invalid_email()
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
    public function it_does_not_set_user_password_if_token_is_empty()
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
    public function it_does_not_set_user_password_if_token_does_not_exist()
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
    public function it_does_not_set_user_password_if_email_does_not_exist()
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
    public function it_does_not_set_user_password_if_email_and_token_do_not_match()
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
    public function it_does_not_set_user_password_if_password_is_not_confirmed()
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
    public function it_does_not_set_user_password_if_password_is_empty()
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
    public function it_does_not_set_user_password_if_password_is_too_short()
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
    public function it_deletes_activation_token_after_user_is_activated()
    {
        $user = factory(User::class)->create(['active' => 0, 'activation_token' => 'foo']);

        $this->get(route('user.activate', $user->activation_token));

        $this->assertSame(null, $user->fresh()->activation_token);
    }
}
