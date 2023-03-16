<?php

namespace App\Http\Middleware;


use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleUserMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  $role
     * @return mixed
     */
    public function handle(Request $request, Closure $next, $role)
    {
        if (Auth::guest()) {
            return redirect('/');
        }

        if (Auth::user()->role->name != $role) {
            notify()->error('Sorry, You Are Not Authorized');
            return redirect()->back();
        }

        return $next($request);
    }
}
