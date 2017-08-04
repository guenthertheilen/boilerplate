<?php

namespace App\Validators;

use App\Models\Role;
use Auth;

class KeepOwnAdminRole
{
    public function validate($attribute, $value, $parameters, $validator)
    {
        return $this->isUpdatingOtherUser($parameters[0]) || $this->itDoesNotRemoveAdminRole($value);
    }

    private function isUpdatingOtherUser($userId)
    {
        return Auth::id() != $userId;
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