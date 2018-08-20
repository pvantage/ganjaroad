<?php

namespace App\Http\Middleware;

use Closure;
use Sentinel;
use Redirect;
use App\User;

class SentinelAdmin
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
        if(!User::check())
            return Redirect::to('admin/signin')->with('info', 'You must be logged in!');
        elseif(!Sentinel::inRole('admin'))
            return Redirect::to('my-account');

        return $next($request);
    }
}
