<?php

namespace App\Validators;

use App\Models\User;

class EmailAndTokenMatch
{
    public function validate($attribute, $value, $parameters, $validator)
    {
        return app(User::class)->where([
            ['email', '=', $value],
            ['activation_token', '=', $parameters[0]]
        ])->exists();
    }
}
