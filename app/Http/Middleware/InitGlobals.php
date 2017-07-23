<?php

namespace App\Http\Middleware;

use Globals;
use Closure;
use App\Models\AccessToken;

class InitGlobals
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
    $token = $request->cookie('EXMUM_U');

    if (empty($token)) {
      Globals::$user = null;
      return $next($request);
    }

    $tokenObject = AccessToken::getEffectiveToken($token);
    if (empty($tokenObject)) {
      Globals::$user = null;
      return $next($request);
    }

    Globals::$user = $tokenObject->user;

    return $next($request);
  }
}
