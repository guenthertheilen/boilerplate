<?php

namespace App\Providers;

use App\Events\UserCreated;
use App\Services\Password;

class GeneratePasswordIfEmpty
{
    /**
     * @var Password
     */
    private $password;

    /**
     * Create the event listener.
     *
     * @param Password $password
     */
    public function __construct(Password $password)
    {
        $this->password = $password;
    }

    /**
     * Handle the event.
     *
     * @param  UserCreated $event
     * @return void
     */
    public function handle(UserCreated $event)
    {
        if (empty($event->user->getAttribute('password'))) {
            $password = $this->password->generate();
            $event->user->update(['password' => $this->password->encrypt($password)]);
        }
    }
}
