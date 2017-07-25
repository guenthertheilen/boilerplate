<?php

namespace App\Listeners;

use App\Events\UserCreated;
use App\Models\Role;
use App\Models\User;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

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
     * @param User $user
     * @param Role $role
     */
    public function __construct(User $user, Role $role)
    {
        $this->user = $user;
        $this->role = $role;
    }

    /**
     * Handle the event.
     *
     * @param  UserCreated  $event
     * @return void
     */
    public function handle(UserCreated $event)
    {
        $event->user->attachRole($this->role->defaultRole());
    }
}
