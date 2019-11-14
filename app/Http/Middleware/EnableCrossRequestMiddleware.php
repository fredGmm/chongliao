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
        $origin = $request->server('HTTP_ORIGIN') ? $request->server('HTTP_ORIGIN') : '*';
        $allow_origin = [
            'http://127.0.0.1:9528',
            'http://localhost:9528',
            'http://127.0.0.1:8080',
            'http://192.168.0.101:8080',
            'http://192.168.0.102:8080',
            'http://192.168.0.100:8080',
            'http://192.168.0.44:9528',
            'chongliaoweb.me'
        ];

        if (in_array($origin, $allow_origin) || true) {
            $response->header('Access-Control-Allow-Origin', '*');
            $response->header('Access-Control-Allow-Headers', 'Content-Type,XFILENAME,XFILECATEGORY,XFILESIZE,x-csrf-token,x-token,X-Requested-With, Content-Type, accept-language, accept-encoding, referer, user-agent, origin, accept, connection, host');
            $response->header('Access-Control-Expose-Headers', 'Authorization, authenticated');
            $response->header('Access-Control-Allow-Methods', 'GET,POST,PUT,OPTIONS,PATCH,DELETE,HEAD');
            $response->header('Content-Type', '*');
            $response->header('charset', 'utf-8');
            $response->header('Access-Control-Allow-Credentials', 'true');
        }
        return $response;
    }
}
