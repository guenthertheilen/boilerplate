<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name'
    ];

    /**
     * Name of default role for newly created users.
     *
     * @var string
     */
    private $defaultRoleName = 'user';

    /**
     * A Role can have many users.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function users()
    {
        return $this->belongsToMany(User::class);
    }

    /**
     * A Role can have many Permissions.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function permissions()
    {
        return $this->belongsToMany(Permission::class);
    }

    /**
     * Attach Permission to Role.
     *
     * @param $permission
     * @return $this
     */
    public function attachPermission($permission)
    {
        if (is_string($permission)) {
            $permission = Permission::whereName($permission)->first();
        }

        if (!$this->hasPermission($permission)) {
            $this->permissions()->attach($permission->id);
            $this->refresh();
        }

        return $this;
    }

    /**
     * Check if Role has given Permission.
     *
     * @param $permission
     * @return bool
     */
    public function hasPermission($permission)
    {
        if (is_string($permission)) {
            return $this->hasPermissionByName($permission);
        }
        return in_array($permission->id, $this->permissions->pluck('id')->toArray());
    }

    /**
     * Check if Role has Permission with given name.
     *
     * @param $permission
     * @return bool
     */
    private function hasPermissionByName($permission)
    {
        return in_array($permission, $this->permissions->pluck('name')->toArray());
    }


    /**
     * Get default role for new users.
     * Create it if it does not exist.
     *
     * @return $this|Model|mixed
     */
    public function defaultRole()
    {
        return $this->firstOrCreate(['name' => $this->defaultRoleName]);
    }

    /**
     * Check if role is default role
     *
     * @return bool
     */
    public function isDefaultRole()
    {
        return $this->name == $this->defaultRoleName;
    }
}
