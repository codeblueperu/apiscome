<?php

namespace App\Http\Middleware;

use Closure;
use Exception;
use Tymon\JWTAuth\JWTAuth;
use Tymon\JWTAuth\Exceptions\TokenInvalidException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Http\Middleware\BaseMiddleware;


class JwtMiddleware extends BaseMiddleware
{

        /**
        * Validate Token
        *
        * returns Illuminate\Http\JsonResponse
        */
    public function handle($request, Closure $next)
    {
        try {
            $user = \Tymon\JWTAuth\Facades\JWTAuth::parseToken()->authenticate();
        } catch (Exception $e) {
            if ($e instanceof TokenInvalidException){
                return response()->json(['status' => 'Token is Invalid']);
            }else if ($e instanceof TokenExpiredException){
                return response()->json(['status' => 'Token is Expired']);
            }else{
                return response()->json(['status' => 'Authorization Token not found']);
            }
        }
        return $next($request);
       /*  try {
            if (!($user = JWTAuth::parseToken()->authenticate())) {
                return response()->json(['user_not_found'], 404);
            }
        } catch (\Exception $e) {
            if ($e instanceof TokenExpiredException){
                $newToken = JWTAuth::parseToken()->refresh();
                return response()->json(['success' => false ,'message' => $newToken, 'status' => 'expired'], 401);
            }else if ($e instanceof TokenInvalidException){
                return response()->json(['success' => false ,'message' => 'Su token es invalido.'], 401);
            }else{
                return response()->json(['success' => false ,'message' => 'Su token no existe.'], 401);
            }
        }
        return response()->json(compact('user')); */
    }
}
