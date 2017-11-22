<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Closure;

class AppAuthenticate
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
        $appKey = $request->header('App-Key', null);

        if ($appKey != null) {
        	if($appKey == env('APP_KEY')) {
		        return $next($request);
        	}
        }

        return response('Credencial de la aplicación no valida', '404');
    }
}
