<?php

namespace App\Listeners;

use App\Events\UserCreated;
use App\Models\Role;

class AttachDefaultRoleToUser
{
    private $user;
    private $role;

    public function __construct(Role $role)
    {
        $this->role = $role;
    }

    public function handle(UserCreated $event)
    {
        $event->user->attachRole($this->role->defaultRole());
    }
}
