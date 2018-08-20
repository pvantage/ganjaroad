<?php

namespace App\Http\Middleware;

use Closure;
use App\Helpers\Template;

class Search
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
        if ($query = $request->get('changelocation')) {
			$query = strtoupper($query);
            $state = Template::matchState($query);

			if($state) {
				$request->replace(array('changelocation' => $state));
			}
        }
        return $next($request);
    }
    
    
}
