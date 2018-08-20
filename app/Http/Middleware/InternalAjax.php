<?php

namespace App\Http\Middleware;

use Closure;

class InternalAjax
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
        if ($request->ajax()) {
            //all good
        } else {
			return response('Unauthorized.', 401);
		}
        
        return $next($request);
    }
}
