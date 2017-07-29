<?php

namespace App\Services;

use Auth;
use Illuminate\Routing\Router;

class Authorizer
{
    private $router;
    private $user;

    public function __construct(Router $router)
    {
        $this->router = $router;
        $this->user = Auth::user();
    }

    public function denies($permission = null)
    {
        return !$this->allows($permission);
    }

    public function allows($permission = null)
    {
        if ($permission == null) {
            $permission = $this->router->getCurrentRoute()->getName();
        }
        return $this->user->hasPermission($permission);
    }
}