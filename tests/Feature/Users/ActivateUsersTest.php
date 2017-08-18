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
    public function itDoesNotSetUserPasswordWithInvalidEmail()
    {
        $user = factory(User::class)->create(['active' => 0, 'password' => '']);

        $this->post(route('password.store'), [
                'email' => '',
                'activation_token' => $user->activation_token,
                'password' => 'new-password',
                'password_confirmation' => 'new-password'
            ]);
        $this->assertTrue(false);
    }

    /** @test */
    public function itDeletesActivationTokenAfterUserIsActivated()
    {
        $user = factory(User::class)->create(['active' => 0, 'activation_token' => 'foo']);

        $this->get(route('user.activate', $user->activation_token));

        $this->assertEquals('', $user->fresh()->activation_token);
    }
}
