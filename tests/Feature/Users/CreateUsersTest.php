<?php

namespace Tests\Feature\Users;

use App\Events\UserCreated;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;

class CreateUsersTest extends TestCase
{
    use WithoutMiddleware;

    /** @test */
    function show_link_to_create_user()
    {
        $this->get(route('user.index'))
            ->assertSee(route('user.create'));
    }

    /** @test */
    function create_new_user()
    {
        $this->post(route('user.store'), [
            'name' => 'Foo Bar',
            'email' => 'foo@bar.com',
        ]);

        $this->assertDatabaseHas('users', [
            'name' => 'Foo Bar',
            'email' => 'foo@bar.com',
            'active' => 0,
        ]);
    }

    /** @test */
    function user_creation_requires_name()
    {
        $this->post(route('user.store'), [
            'name' => '',
            'email' => 'johndoe@example.com',
        ])->assertSessionHasErrors(['name']);

        $this->assertDatabaseMissing('users', [
            'email' => 'johndoe@example.com',
        ]);
    }

    /** @test */
    function dispatch_event_after_creating_user()
    {
        Event::fake();

        $this->post(route('user.store'), [
            'name' => 'Foo Bar',
            'email' => 'foo@bar.com',
        ]);

        Event::assertDispatched(UserCreated::class);
    }
}
