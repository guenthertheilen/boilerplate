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
        'name', 'email', 'password', 'active', 'activation_token',
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
    protected $dispatchesEvents = [
        'created' => UserCreated::class,
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
     * @param Role|string $role
     * @return $this
     */
    public function attachRole($role)
    {
        if (is_string($role)) {
            $role = Role::whereName($role)->first();
        }

        if (!$this->hasRole($role)) {
            $this->roles()->attach($role->id);
            $this->refresh();
        }

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
        if (count($this->roles) > 1) {
            $this->roles()->detach($role->id);
            $this->refresh();
        }

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
     * Check if user password is set
     *
     * @return bool
     */
    public function hasNoPassword()
    {
        return $this->password == '';
    }

    /**
     * Check if the user has Role named 'admin'.
     *
     * @return bool
     */
    public function isAdmin()
    {
        return $this->hasRole('admin');
    }

    /**
     * Check if the user does not have Role named 'admin'.
     *
     * @return bool
     */
    public function isNotAdmin()
    {
        return !$this->isAdmin();
    }

    /**
     * Activate user and remove activation_token
     *
     * @return void
     */
    public function activate()
    {
        $this->update([
            'active' => 1,
            'activation_token' => null,
        ]);
    }

    /**
     * Check if user is activated
     *
     * @return boolean
     */
    public function isActive()
    {
        return $this->active == 1;
    }

    /**
     * Check if the User has Permission by given name.
     *
     * @param $permission
     * @return bool
     */
    public function hasPermission($permission)
    {
        foreach ($this->roles as $role) {
            if ($role->hasPermission($permission)) {
                return true;
            }
        }
        return false;
    }

    /**
     * Return the names of the attached rols in alphabetical order as comma seperated string
     *
     * @return string
     */
    public function rolesAsString()
    {
        return $this->roles
            ->pluck('name')
            ->sort()
            ->implode(', ');
    }

    /**
     * Create token used to activate user.
     *
     * @return void
     */
    public function createActivationToken()
    {
        do {
            $token = str_random(32);
        } while (static::whereActivationToken($token)->first() instanceof $this);

        $this->update(
            ['activation_token' => $token]
        );
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
