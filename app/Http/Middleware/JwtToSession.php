<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Contracts\Providers\Auth;
use Symfony\Component\HttpFoundation\Response;

class JwtToSession
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        if ($token = $request->bearerToken()) {
            try {
                $user = JWTAuth::setToken($token)->authenticate();
                Auth::login($user);
            } catch (\Exception $e) {
                return $e;
            }
        }
        return $next($request);
    }
}
