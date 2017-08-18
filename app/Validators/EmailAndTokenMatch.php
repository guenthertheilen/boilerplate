<?php

namespace App\Validators;

use App\Models\Role;
use Auth;

class EmailAndTokenMatch
{
    public function validate($attribute, $value, $parameters, $validator)
    {
        return $this->isNotEditingOwnRoles($parameters[0]) || $this->isNotRemovingAdminRole($value);
    }

    private function isNotEditingOwnRoles($userId)
    {
        return Auth::id() != $userId;
    }

    private function isNotRemovingAdminRole($roles)
    {
        return in_array($this->adminRoleId(), $roles);
    }

    private function adminRoleId()
    {
        return app(Role::class)->where('name', '=', 'admin')->pluck('id')->first();
    }
}
