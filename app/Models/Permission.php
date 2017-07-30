<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Permission extends Model
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
     * A Permission can belong to many Roles.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function roles()
    {
        return $this->belongsToMany(Role::class);
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
}
