<?php

namespace Tests\Feature\Users;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ActivateUsersTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    function it_activates_user()
    {
        $user = factory(User::class)->create(['active' => 0, 'activation_token' => 'foo']);

        $this->assertFalse($user->isActive());

        $this->get(route('user.activate', $user->activation_token))
            ->assertRedirect(route('login'));

        $this->assertTrue($user->fresh()->isActive());
    }

    /** @test */
    function it_asks_user_to_set_password_if_no_password_is_set_yet()
    {
        $user = factory(User::class)->create(['active' => 0, 'activation_token' => 'foo', 'password' => '']);

        $this->get(route('user.activate', $user->activation_token))
            ->assertRedirect(route('password.create', $user->activation_token));
    }

    /** @test */
    function it_sets_user_password()
    {
        $user = factory(User::class)->create(['active' => 0, 'password' => '']);

        $this->post(route('password.store'), [
                'email' => $user->email,
                'activation_token' => $user->activation_token,
                'password' => 'new-password',
                'password_confirmation' => 'new-password'
            ])->assertRedirect(route('user.activate', $user->activation_token));
    }
    
    /** @test */
    function it_does_not_set_user_password_with_invalid_email()
    {
        $user = factory(User::class)->create(['active' => 0, 'password' => '']);

        $this->post(route('password.store'), [
                'email' => '',
                'activation_token' => $user->activation_token,
                'password' => 'new-password',
                'password_confirmation' => 'new-password'
            ])->assertRedirect(route('password.create', $user->activation_token));
    }

    /** @test */
    function it_deletes_activation_token_after_user_is_activated()
    {
        $user = factory(User::class)->create(['active' => 0, 'activation_token' => 'foo']);

        $this->get(route('user.activate', $user->activation_token));

        $this->assertEquals('', $user->fresh()->activation_token);
    }
}
