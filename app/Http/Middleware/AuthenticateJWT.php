<?php

namespace App\Http\Middleware;

use Closure;
use Firebase\JWT\ExpiredException;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class AuthenticateJWT
{
    public function handle($request, Closure $next, $guard = null)
    {
        $local = 'en';
        $message = [
            0 => [ // 0
                'en' => "Token not provided.",
            ],
            1 => [
                'en' => "Provided token is expired.",
            ],
            2 => [
                'en' => "An error while decoding token.",
            ],
        ];

        
        $token = $request->cookie('token');
        $token = $token ? $token : $request->header('Authorization');
        $token = $token ? str_replace("Bearer ", '', $token) : null;
        if (!$token) {
            return response()->json([
                'isSuccess' => false,
                'errors' => [
                    "errorno" => 1001,
                    "message" => $message[0][$local],
                ],
            ], 401);
        }

        try {
            $secret = base64_decode(strtr(env('JWT_SECRET'), '-_', '+/'));
            $credentials = JWT::decode($token, new Key($secret, 'HS256'));
        } catch (ExpiredException $e) {
            error_log($e->getMessage());
            return response()->json([
                'isSuccess' => false,
                'errors' => [
                    "errorno" => 1002,
                    "message" => $message[1][$local],
                ],
            ], 400);
        } catch (\Exception$e) {
            error_log($e->getMessage());
            return response()->json([
                'isSuccess' => false,
                'errors' => [
                    "errorno" => 1003,
                    "message" => $message[2][$local],
                ],
            ], 400);
        }

        $request->auth = $credentials;
        return $next($request);
    }
}
