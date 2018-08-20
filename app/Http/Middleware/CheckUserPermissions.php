<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Routing\Route;
use Sentinel;
use App\Helpers\UserPermissionsList;
use App\Helpers\Template;

class CheckUserPermissions
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
		$user = Sentinel::getUser();
		$route = $request->route();
		$action = str_replace('/', '.', $route->getName());
		$permissions_list = UserPermissionsList::getPermissions();
        
        if($item = Template::findArrayKey($permissions_list, $action)) {
            $value = $item[$action][key($item[$action])];

            if(!$user->hasAccess(key($item[$action]))) {
				return redirect('access/'.$value);
			}
		}
		
		
        return $next($request);
    }
}
