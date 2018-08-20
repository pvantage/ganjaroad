<?php

namespace App\Http\Middleware;

use Closure;
use Sentinel;
use Redirect;
use App\User;

class Guest
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
        if (User::check()) {
            return Redirect::route('my-account');
        }
        
        return $next($request);
    }
}
