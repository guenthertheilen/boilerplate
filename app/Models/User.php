<?php

namespace App\Models;

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
     */
    public function attachRole($role)
    {
        $this->roles()->attach($role->id);
    }

    /**
     * Detach Role from User.
     *
     * @param $role
     */
    public function detachRole($role)
    {
        $this->roles()->detach($role->id);
    }

    /**
     * Check if User has given Role.
     *
     * @param $role
     * @return bool
     */
    public function hasRole($role)
    {
        return in_array($role->id, $this->roles->pluck('id')->toArray());
    }
}
