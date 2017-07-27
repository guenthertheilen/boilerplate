<?php

namespace App\Http\Middleware;

use Auth;
use Closure;
use Illuminate\Routing\Router;

class Authorize
{
    private $router;
    private $user;

    /**
     * Authorize constructor.
     * @param Router $router
     */
    public function __construct(Router $router)
    {
        $this->router = $router;
        $this->user = Auth::user();
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if ($this->router->getCurrentRoute()->uri == 'foo' && !$this->user->isAdmin()) {
            dd("FOO");
        }

        return $next($request);
    }
}
