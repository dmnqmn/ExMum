<?php

namespace App\Http\Middleware;

use Closure;

class Logined
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (is_null(\Globals::$user)) {
            return response()->json(['error' => 'LOGINED_USERS_ONLY'], 403);
        }

        return $next($request);
    }
}
