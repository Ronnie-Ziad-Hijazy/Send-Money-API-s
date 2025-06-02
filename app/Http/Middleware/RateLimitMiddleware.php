<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Redis as RedisManager;

class RateLimitMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $prefix, $limit, $duration)
    {
        if (env('APP_ENV') == 'local') {
            // local environment
            return $next($request);
        }

        $limit = (int) $limit;
        $duration = (int) $duration;

        $ip = $_SERVER['HTTP_CF_CONNECTING_IP'] ?? $request->ip();
        $index = $prefix . ':' . $ip;

        $res = RedisManager::get($index);
        if ($res) {
            $res = json_decode($res, true);

            if (time() <= $res['t'] && $res['h'] >= $limit) {
                return response()->json(['error_msg' => 'Too many requests.'], 429);
            }

            if (time() > $res['t']) {
                RedisManager::del($index);
                $res = null;
            }
        }

        $response = $next($request);

        if ($response->isSuccessful()) {
            if ($res) {
                $res['h']++;
            } else {
                $res = ['h' => 1, 't' => time() + $duration];
            }

            RedisManager::setex($index, $duration, json_encode($res));
        }

        return $response;
    }
}
