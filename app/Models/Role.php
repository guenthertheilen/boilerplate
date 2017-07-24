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
     * A Role can have many users.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function users()
    {
        return $this->belongsToMany(User::class);
    }

    /**
     * Get default role for new users.
     * Create it if it does not exist.
     *
     * @return $this|Model|mixed
     */
    public function defaultRole()
    {
        $role = $this->where(['name' => 'user'])->first();
        if (empty($role)) {
            $role = $this->create(['name' => 'user']);
        }
        return $role;
    }
}