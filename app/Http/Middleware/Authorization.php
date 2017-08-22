<?php

namespace App\Http\Middleware;

use App\Services\Authorizer;
use Closure;
use Illuminate\Http\Request;

class Authorization
{
    private $authorizer;

    public function __construct(Authorizer $authorizer)
    {
        $this->authorizer = $authorizer;
    }

    public function handle(Request $request, Closure $next)
    {
        if ($this->authorizer->denies()) {
            return response("Not authorized", 403);
        }

        return $next($request);
    }
}
