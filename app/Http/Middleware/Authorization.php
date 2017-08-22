<?php

namespace App\Http\Middleware;

use App\Services\Authorizer;
use Closure;

class Authorization
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
        if ($this->authorizer->denies()) {
            return response("Not authorized", 403);
        }

        return $next($request);
    }
}
