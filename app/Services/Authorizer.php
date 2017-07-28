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

    public function deniesAccess()
    {
        return !$this->allowsAccess();
    }

    public function allowsAccess()
    {
        return $this->user->hasPermission($this->router->getCurrentRoute()->getName());
    }
}