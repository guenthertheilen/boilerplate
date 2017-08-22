<?php

namespace App\Listeners;

use App\Events\UserCreated;
use App\Models\Role;

class AttachDefaultRoleToUser
{
    /**
     * @var Role
     */
    private $role;

    /**
     * AttachDefaultRoleToUser constructor.
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
