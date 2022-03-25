<?php

namespace App\Http\Middleware;

use Closure;

class UserRoleverify
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next,$role = null)
    {
        $user = auth()->user();
        if($role == 'admin'){
            if($user->is_admin){
                return $next($request);
            }else{
                abort(403);
            }
        }

        if($role == 'user'){
            if(!$user->is_admin){
                return $next($request);
            }else{
                abort(403);
            }
        }
        return $next($request);
    }
}
