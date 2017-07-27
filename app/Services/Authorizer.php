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

    public function allowsAccess($route = null)
    {
        if ($route == null) {
            if ($this->router->getCurrentRoute()->uri == 'foo' && $this->user->isAdmin()) {
                return true;
            }
            if ($this->router->getCurrentRoute()->uri != 'foo') {
                return true;
            }
            return false;
        }
        return $this->user->isAdmin();

    }
}