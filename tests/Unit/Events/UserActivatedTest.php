<?php

namespace Tests\Unit\Events;

use App\Events\UserActivated;
use App\Listeners\AskUserForPasswordIfNotSet;
use App\Listeners\DeleteActivationToken;
use App\Models\User;
use Mockery;
use Tests\TestCase;

class UserActivatedTest extends TestCase
{
    private $askForPassword;
    private $deleteToken;
    private $user;

    protected function setUp()
    {
        parent::setUp();

        $this->askForPassword = Mockery::spy(AskUserForPasswordIfNotSet::class);
        app()->instance(AskUserForPasswordIfNotSet::class, $this->askForPassword);

        $this->deleteToken = Mockery::spy(DeleteActivationToken::class);
        app()->instance(DeleteActivationToken::class, $this->deleteToken);

        $this->user = factory(User::class)->make();
        event(new UserActivated($this->user));
    }

    /** @test */
    function it_calls_listener_to_ask_user_for_password()
    {
        $this->askForPassword->shouldHaveReceived('handle')->once();
    }

    /** @test */
    function it_calls_listener_to_delete_activation_token()
    {
        $this->deleteToken->shouldHaveReceived('handle')->once();
    }
}
