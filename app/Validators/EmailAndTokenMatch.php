<?php

namespace App\Validators;

use App\Models\User;

class EmailAndTokenMatch
{
    /**
     * Custom validaition rule.
     *
     * @param $attribute
     * @param $value
     * @param $parameters
     * @param $validator
     * @return bool
     */
    public function validate($attribute, $value, $parameters, $validator)
    {
        return User::where([
            ['email', '=', $value],
            ['activation_token', '=', $parameters[0]]
        ])->exists();
    }
}
