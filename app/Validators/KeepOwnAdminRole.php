<?php

namespace App\Validators;

use App\Models\Role;
use Illuminate\Support\Facades\Auth;

class KeepOwnAdminRole
{
    /**
     * Custom validation rule.
     *
     * @param $attribute
     * @param $value
     * @param $parameters
     * @param $validator
     * @return bool
     */
    public function validate($attribute, $value, $parameters, $validator)
    {
        return $this->isNotEditingOwnRoles($parameters[0]) || $this->isNotRemovingAdminRole($value);
    }

    /**
     * Check if user does not edit own role.
     *
     * @param $userId
     * @return bool
     */
    private function isNotEditingOwnRoles($userId)
    {
        return Auth::id() != $userId;
    }

    /**
     * Check if user does not try to remove admin role.
     *
     * @param $roles
     * @return bool
     */
    private function isNotRemovingAdminRole($roles)
    {
        return in_array($this->adminRoleId(), $roles);
    }

    /**
     * Id of Role named 'admin'.
     *
     * @return mixed
     */
    private function adminRoleId()
    {
        return Role::whereName('admin')->pluck('id')->first();
    }
}