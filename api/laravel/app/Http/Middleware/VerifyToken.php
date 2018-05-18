<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Facades\JWTFactory;

class VerifyToken
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
        try{
            $token = JWTAuth::getToken();
            if(!is_bool($token)){
                $payload = JWTAuth::decode($token);
                if (!isset($payload['uid'])) {
                    return response('INVALID_TOKEN', 302);
                }
            }
        }catch(exception $e)
        {
            return response('INVALID_TOKEN', 302);
        }

        return $next($request);
    }
}
