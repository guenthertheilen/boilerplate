<?php

namespace Tests\Feature\Users;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ActivateUsersTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    function it_activates_user()
    {
        $user = factory(User::class)->create(['active' => 0]);

        $this->assertFalse($user->isActive());

        $this->get(route('user.activate', $user->activation_token))
            ->assertRedirect(route('login'));

        $this->assertTrue($user->fresh()->isActive());
    }

    /** @test */
    function it_clears_activation_token_after_activation()
    {
        $user = factory(User::class)->create(['active' => 0]);

        $this->get(route('user.activate', $user->activation_token));

        $user->refresh();

        $this->assertEmpty($user->getAttribute('activation_token'));
    }
}
