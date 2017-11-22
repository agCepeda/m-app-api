<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Closure;

use App\Session;
use App\User;

class UserAuthenticate
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
        $sessionToken = $request->header('Session-Token', null);

        if ($sessionToken != null) {
            $session = Session::where('token', $sessionToken)
                        ->with("user")->first();

            if (isset($session) && $session != null) {
                app()[Session::class] = $session;
                app()[User::class]    = $session->user;

                $response = $next($request);
                $session->touch();

                return $response;
            } else {
                return response('Usuario no autenticado', '401');
            }
        } else {
            return response('Usuario no autenticado', '401');
        }
    }
}
