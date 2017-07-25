<?php

namespace App\Models;

use App\Events\UserCreated;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The event map for the model.
     *
     * @var array
     */
    protected $events = [
        'created' => UserCreated::class
    ];

    /**
     * A User can have many roles.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function roles()
    {
        return $this->belongsToMany(Role::class);
    }

    /**
     * Attach Role to User.
     *
     * @param Role $role
     * @return $this
     */
    public function attachRole($role)
    {
        $this->roles()->attach($role->id);

        return $this;
    }

    /**
     * Detach Role from User.
     *
     * @param $role
     * @return $this
     */
    public function detachRole($role)
    {
        $this->roles()->detach($role->id);

        return $this;
    }

    /**
     * Check if User has given Role.
     *
     * @param $role
     * @return bool
     */
    public function hasRole($role)
    {
        if (is_string($role)) {
            return $this->hasRoleByName($role);
        }
        return in_array($role->id, $this->roles->pluck('id')->toArray());
    }

    /**
     * Check if User has role with given name.
     *
     * @param $role
     * @return bool
     */
    private function hasRoleByName($role)
    {
        return in_array($role, $this->roles->pluck('name')->toArray());
    }
}
