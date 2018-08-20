<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Route;
use Sentinel;
use Illuminate\Support\Facades\Session;

class CheckUserLocationData {
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure                 $next
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $user      = Sentinel::getUser();
        $route     = Route::getCurrentRoute();
        $routePath = $route->getPath();
        $routeName = $route->getName();

        if ($user && !$user->hasAddress()) {
            if ($routeName === 'my-account') {
                Session::set('important_message',
                    'Please verify your residence data below and click the <strong>"Save"</strong> button to confirm them'
                );

                $user->setAddressFromLocation();
            }

            if ($routePath !== 'my-account' && $routePath !== 'logout') {
                return redirect('my-account');
            }
        }

        return $next($request);
    }
}
