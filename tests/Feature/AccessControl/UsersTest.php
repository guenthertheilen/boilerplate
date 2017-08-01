<?php

namespace Tests\Feature\AccessControl;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class UsersTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    function it_shows_list_of_users()
    {
        factory(User::class)->create(['name' => 'Jane Doe']);
        factory(User::class)->create(['name' => 'John Doe']);

        $this->withoutMiddleware()
            ->get(route('user.index'))
            ->assertSeeText('Jane Doe')
            ->assertSeeText('John Doe');
    }

    /** @test */
    function it_adds_new_user()
    {
        $this->withoutMiddleware()
            ->post(route('user.store'), [
                'name' => 'Foo Bar',
                'email' => 'foo@bar.com',
                'password' => 'bizbaz'
            ]);

        $this->assertDatabaseHas('users', ['name' => 'Foo Bar']);
    }

    /** @test */
    function it_shows_links_to_edit_user()
    {
        $user = factory(User::class)->create();
        $this->withoutMiddleware()
            ->get(route('user.index'))
            ->assertSee(route('user.edit', $user->id));
    }
}
