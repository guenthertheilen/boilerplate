<?php

namespace Tests\Feature\Users;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Tests\TestCase;

class ShowUsersTest extends TestCase
{
    use DatabaseMigrations, WithoutMiddleware;

    /** @test */
    function it_shows_list_of_users()
    {
        factory(User::class)->create(['name' => 'Jane Doe', 'email' => 'jane@foo.de']);
        factory(User::class)->create(['name' => 'John Doe', 'email' => 'john@bar.org']);

        $this->get(route('user.index'))
            ->assertSeeText('Jane Doe')
            ->assertSeeText('jane@foo.de')
            ->assertSeeText('John Doe')
            ->assertSeeText('john@bar.org');
    }
}
