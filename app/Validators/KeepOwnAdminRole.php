<?php

namespace App\Validators;

use App\Models\Role;
use App\Models\User;

class KeepOwnAdminRole
{
    public function validate($attribute, $value, $parameters, $validator)
    {
        return $this->userIsNoAdmin($parameters[0]) || $this->itDoesNotRemoveAdminRole($value);
    }

    private function userIsNoAdmin($userId)
    {
        return app(User::class)->find($userId)->isNotAdmin();
    }

    private function itDoesNotRemoveAdminRole($roles)
    {
        return in_array($this->adminRoleId(), $roles);
    }

    private function adminRoleId()
    {
        return app(Role::class)->where('name', '=', 'admin')->pluck('id')->first();
    }
}