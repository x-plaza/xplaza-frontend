<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Session;

class authAndAcl
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $isAuthenticated = session::get('auth_user_id');
        if (isset($isAuthenticated) && $isAuthenticated != null) {
            return $next($request);
        } else {
            Session::forget('auth_user_id');

            return redirect('/');
            exit;
        }
        // return $next($request);
    }
}
