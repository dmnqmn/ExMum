<?php

namespace App\Http\Middleware;

use Closure;

class Response
{
    public function handle($request, Closure $next)
    {
        return $next($request);
    }
}
