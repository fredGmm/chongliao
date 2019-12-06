<?php

namespace App\Http\Middleware;

use Closure;

class EnableCrossRequestMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $response = $next($request);
        $origin = $request->server('HTTP_ORIGIN') ? $request->server('HTTP_ORIGIN') : '*';

        $response->header('Access-Control-Allow-Origin', $origin);
        $response->header('Access-Control-Allow-Headers', 'x-csrf-token,x-token,X-XSRF-TOKEN,X-Requested-With,Origin,Content-Type,Cookie,Accept,Authorization');
        $response->header('Access-Control-Expose-Headers', 'Authorization, authenticated');
        $response->header('Access-Control-Allow-Methods', 'GET,POST,PUT,OPTIONS,PATCH,DELETE,HEAD');
        $response->header('Content-Type', 'application/json;charset=UTF-8');
        $response->header('Access-Control-Allow-Credentials', 'true');

        return $response;
    }

}
