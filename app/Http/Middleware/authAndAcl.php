<?php

namespace App\Http\Middleware;

use Illuminate\Support\Facades\Session;
use Closure;

class authAndAcl
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
        $isAuthenticated = session::get('auth_user_id');
        if(isset($isAuthenticated) && $isAuthenticated != null){
            return $next($request);
        }
        else{
            Session::forget('auth_user_id');
            return redirect('/');
            exit;
        }
       // return $next($request);
    }
}
