<?php

namespace App\Rules;

use App\Models\Role;
use App\Models\User;
use Auth;
use Illuminate\Contracts\Validation\Rule;

class KeepOwnAdminRole implements Rule
{
    private $user;

    /**
     * Create a new rule instance.
     *
     * @param User $user
     */
    public function __construct(User $user)
    {
        $this->user = $user;
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
        return $this->isNotEditingOwnRoles($this->user) || $this->isNotRemovingAdminRole($value);
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
     * @param User $user
     * @return bool
     */
    private function isNotEditingOwnRoles(User $user)
    {
        return Auth::id() != $user->id;
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
     * Get id of Role named 'admin'.
     *
     * @return mixed
     */
    private function adminRoleId()
    {
        return Role::whereName('admin')->pluck('id')->first();
    }
}
