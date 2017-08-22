<?php

namespace App\Services;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

class Authorizer
{
    /**
     * Check if user does not has permission.
     *
     * @param null $permission
     * @return bool
     */
    public function denies($permission = null)
    {
        return !$this->allows($permission);
    }

    /**
     * Check if user has permission.
     *
     * @param null $permission
     * @return mixed
     */
    public function allows($permission = null)
    {
        if ($permission == null) {
            $permission = Route::currentRouteName();
        }
        return Auth::user()->hasPermission($permission);
    }
}