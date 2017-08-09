<?php

namespace Tests\Feature\Users;

use App\Events\UserActivated;
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
        Event::fake();

        $user = factory(User::class)->create(['active' => 0, 'activation_token' => 'foo']);

        $this->assertFalse($user->isActive());

        $this->get(route('user.activate', $user->activation_token))
            ->assertRedirect(route('login'));

        $this->assertTrue($user->fresh()->isActive());
    }

    /** @test */
    function it_dispatches_event_after_activation()
    {
        Event::fake();

        $user = factory(User::class)->create(['active' => 0, 'activation_token' => 'foobar']);

        $this->get(route('user.activate', $user->activation_token));

        Event::assertDispatched(UserActivated::class);
    }
}
