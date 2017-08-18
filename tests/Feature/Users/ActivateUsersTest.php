<?php

namespace Tests\Feature\Users;

use App\Models\User;
use Auth;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class ActivateUsersTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function itActivatesUser()
    {
        $user = factory(User::class)->create(['active' => 0, 'activation_token' => 'foo']);

        $this->assertFalse($user->isActive());

        $this->get(route('user.activate', $user->activation_token))
            ->assertRedirect(route('login'));

        $this->assertTrue($user->fresh()->isActive());
    }

    /** @test */
    public function itAsksUserToSetPasswordIfNoPasswordIsSetYet()
    {
        $user = factory(User::class)->create(['active' => 0, 'activation_token' => 'foo', 'password' => '']);

        $this->get(route('user.activate', $user->activation_token))
            ->assertRedirect(route('password.create', $user->activation_token));
    }

    /** @test */
    public function itSetsUserPassword()
    {
        $user = factory(User::class)->create(['active' => 0, 'password' => '']);

        $this->post(route('password.store'), [
                'email' => $user->email,
                'activation_token' => $user->activation_token,
                'password' => 'new-password',
                'password_confirmation' => 'new-password'
            ])->assertRedirect(route('user.activate', $user->activation_token));

        $this->assertTrue(Auth::validate(['email' => $user->email, 'password' => 'new-password']));
    }
    
    /** @test */
    public function itDoesNotSetUserPasswordWithoutEmail()
    {
        $user = factory(User::class)->create(['active' => 0, 'password' => '']);

        $this->post(route('password.store'), [
                'email' => '',
                'activation_token' => $user->activation_token,
                'password' => 'new-password',
                'password_confirmation' => 'new-password'
            ])->assertSessionHasErrors(['email']);
    }

    /** @test */
    public function itDoesNotSetUserPasswordWithInvalidEmail()
    {
        $user = factory(User::class)->create(['active' => 0, 'password' => '']);

        $this->post(route('password.store'), [
                'email' => 'not-an-email',
                'activation_token' => $user->activation_token,
                'password' => 'new-password',
                'password_confirmation' => 'new-password'
            ])->assertSessionHasErrors(['email']);
    }

    /** @test */
    public function itDoesNotSetUserPasswordIfTokenIsEmpty()
    {
        $user = factory(User::class)->create(['active' => 0, 'password' => '']);

        $this->post(route('password.store'), [
                'email' => $user->email,
                'activation_token' => '',
                'password' => 'new-password',
                'password_confirmation' => 'new-password'
            ])->assertSessionHasErrors(['activation_token']);
    }

    /** @test */
    public function itDoesNotSetUserPasswordIfTokenDoesNotExist()
    {
        $user = factory(User::class)->create(['active' => 0, 'password' => '', 'activation_token' => 'foo']);

        $this->post(route('password.store'), [
                'email' => $user->email,
                'activation_token' => 'bar',
                'password' => 'new-password',
                'password_confirmation' => 'new-password'
            ])->assertSessionHasErrors(['email']);
    }

    /** @test */
    public function itDoesNotSetUserPasswordIfEmailDoesNotExist()
    {
        $user = factory(User::class)->create(['active' => 0, 'password' => '', 'email' => 'foo@example.com']);

        $this->post(route('password.store'), [
                'email' => 'bar@example.com',
                'activation_token' => $user->activation_token,
                'password' => 'new-password',
                'password_confirmation' => 'new-password'
            ])->assertSessionHasErrors(['email']);
    }

    /** @test */
    public function itDoesNotSetUserPasswordIfEmailAndTokenDoNotMatch()
    {
        factory(User::class)->create([
            'active' => 0,
            'password' => '',
            'email' => 'foo@example.com',
            'activation_token' => 'foo'
        ]);
        factory(User::class)->create([
            'active' => 0,
            'password' => '',
            'email' => 'bar@example.com',
            'activation_token' => 'bar'
        ]);

        $this->post(route('password.store'), [
                'email' => 'foo@example.com',
                'activation_token' => 'bar',
                'password' => 'new-password',
                'password_confirmation' => 'new-password'
            ])->assertSessionHasErrors(['email']);
    }

    /** @test */
    public function itDoesNotSetUserPasswordIfPasswordIsNotConfirmed()
    {
        $user = factory(User::class)->create(['active' => 0, 'password' => '']);

        $this->post(route('password.store'), [
                'email' => $user->email,
                'activation_token' => $user->activation_token,
                'password' => 'new-password',
                'password_confirmation' => 'new-password-foo'
            ])->assertSessionHasErrors(['password']);
    }

    /** @test */
    public function itDoesNotSetUserPasswordIfPasswordIsEmpty()
    {
        $user = factory(User::class)->create(['active' => 0, 'password' => '']);

        $this->post(route('password.store'), [
                'email' => $user->email,
                'activation_token' => $user->activation_token,
                'password' => '',
                'password_confirmation' => ''
            ])->assertSessionHasErrors(['password']);
    }

    /** @test */
    public function itDoesNotSetUserPasswordIfPasswordIsTooShort()
    {
        $user = factory(User::class)->create(['active' => 0, 'password' => '']);

        $this->post(route('password.store'), [
                'email' => $user->email,
                'activation_token' => $user->activation_token,
                'password' => 'short',
                'password_confirmation' => 'short'
            ])->assertSessionHasErrors(['password']);
    }

    /** @test */
    public function itDeletesActivationTokenAfterUserIsActivated()
    {
        $user = factory(User::class)->create(['active' => 0, 'activation_token' => 'foo']);

        $this->get(route('user.activate', $user->activation_token));

        $this->assertEquals('', $user->fresh()->activation_token);
    }
}
