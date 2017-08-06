<?php

namespace App\Services;

class Password
{
    /**
     * Generate random password
     *
     * @return string
     */
    public function generate()
    {
        return str_random(12);
    }

    /**
     * Encrypt password
     *
     * @param $password
     * @return string
     */
    public function encrypt($password)
    {
        return bcrypt($password);
    }
}