<?php

namespace Tests\Feature\Users;

use App\Events\UserCreated;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class CreateUsersTest extends TestCase
{
    use DatabaseMigrations, WithoutMiddleware;

    /** @test */
    function it_shows_link_to_create_user()
    {
        $this->get(route('user.index'))
            ->assertSee(route('user.create'));
    }

    /** @test */
    function it_adds_new_user()
    {
        $this->post(route('user.store'), [
            'name' => 'Foo Bar',
            'email' => 'foo@bar.com'
        ]);

        $this->assertDatabaseHas('users', [
            'name' => 'Foo Bar',
            'email' => 'foo@bar.com',
            'active' => 0
        ]);
    }

    /** @test */
    function it_dispatches_event_after_creating_user()
    {
        Event::fake();

        $this->post(route('user.store'), [
            'name' => 'Foo Bar',
            'email' => 'foo@bar.com'
        ]);

        Event::assertDispatched(UserCreated::class);
    }
}
