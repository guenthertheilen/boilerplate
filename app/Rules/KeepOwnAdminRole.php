<?php

namespace App\Rules;

use App\Models\Role;
use Auth;
use Illuminate\Contracts\Validation\Rule;

class KeepOwnAdminRole implements Rule
{
    private $userId;

    /**
     * Create a new rule instance.
     *
     * @param $userId
     */
    public function __construct($userId)
    {
        $this->userId = $userId;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string $attribute
     * @param  mixed $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        return $this->isNotEditingOwnRoles($this->userId) || $this->isNotRemovingAdminRole($value);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return __('Own admin role can not be removed.');
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
