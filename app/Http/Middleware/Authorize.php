<?php

namespace App\Http\Middleware;

use App\Services\Authorizer;
use Closure;

class Authorize
{
    private $authorizer;

    public function __construct(Authorizer $authorizer)
    {
        $this->authorizer = $authorizer;
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
        if ($this->authorizer->deniesAccess()) {
            return response("Not authorized", 403);
        }

        return $next($request);
    }
}
