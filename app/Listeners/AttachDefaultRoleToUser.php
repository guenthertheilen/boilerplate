<?php

namespace App\Listeners;

use App\Events\UserCreated;
use App\Models\Role;
use App\Models\User;

class AttachDefaultRoleToUser
{
    /**
     * @var User
     */
    private $user;

    /**
     * @var Role
     */
    private $role;

    /**
     * Create the event listener.
     *
     * @param Role $role
     */
    public function __construct(Role $role)
    {
        $this->role = $role;
    }

    /**
     * Handle the event.
     *
     * @param  UserCreated $event
     * @return void
     */
    public function handle(UserCreated $event)
    {
        $event->user->attachRole($this->role->defaultRole());
    }
}
