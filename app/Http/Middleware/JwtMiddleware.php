<?php

namespace App\Http\Middleware;

use Closure;
use Exception;
use URL;

use Illuminate\Http\Request;

use Tymon\JWTAuth\Http\Middleware\BaseMiddleware;
use Tymon\JWTAuth\Facades\JWTAuth;

class JwtMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $endpoint = URL::current();

        try {
            $user = JWTAuth::parseToken()->authenticate();
        } catch (\Exception $e) {
            if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenInvalidException) {
                return response()->json([
                    'success' => false,
                    'message' => 'Token is invalid, ' . $e->getMessage(),
                    'url' => $endpoint
                ], 401);
            } else if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenExpiredException) {
                try {
                    $refreshed = JWTAuth::refresh(JWTAuth::getToken());
                    $user = JWTAuth::setToken($refreshed)->toUser();

                    header('Authorization: Bearer ' . $refreshed);
                } catch (\Tymon\JWTAuth\Exceptions\TokenBlacklistedException $e) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Token has been blacklisted',
                        'url' => $endpoint
                    ], 500);
                } catch (\Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Token is expired',
                        'url' => $endpoint
                    ], 401);
                }
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Token is not found',
                    'url' => $endpoint
                ], 401);
            }
        }

        return $next($request);
    }
}
