<?php

namespace App\Http\Middleware;

use Closure;

class EnableCrossRequestMiddleware
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
        $response = $next($request);
        $origin = $request->server('HTTP_ORIGIN') ? $request->server('HTTP_ORIGIN') : '';
        $allow_origin = [
            'http://localhost:8080',
            'http://127.0.0.1:8080',
            'http://192.168.0.101:8080',
            'http://192.168.0.102:8080',
            'http://192.168.0.100:8080',

        ];
        if (in_array($origin, $allow_origin)) {
            $response->header('Access-Control-Allow-Origin', $origin);
            $response->header('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, accept-language, accept-encoding, referer, user-agent, origin, accept, connection, host');
            $response->header('Access-Control-Expose-Headers', 'Authorization, authenticated');
            $response->header('Access-Control-Allow-Methods', 'GET, POST, PATCH, PUT, OPTIONS');
            $response->header('Content-Type', 'application/json; charset=UTF-8');
            $response->header('Access-Control-Allow-Credentials', 'true');
        }
        return $response;
    }
}
