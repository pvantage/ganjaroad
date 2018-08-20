<?php

namespace App\Http\Middleware;

use Closure;
use Sentinel;
use Redirect;
use App\User;

class SentinelUser
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
        if (!User::check()) {
            if ($request->ajax()) {
                return response('Unauthorized.', 401);
            } else {
                //return Redirect::route('login');
                return Redirect::route('login', array('redirect' => base64_encode($request->fullUrl())));
            }
        }
        return $next($request);
    }
}
