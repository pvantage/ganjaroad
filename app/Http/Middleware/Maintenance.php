<?php

namespace App\Http\Middleware;

use Closure;
use File;
use View;
use Illuminate\Http\Response;

class Maintenance
{
	private $maitenance_file = '.maintenance';
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
		$flag = public_path() . DIRECTORY_SEPARATOR . $this->maitenance_file;
        if (File::exists($flag)) {
            return Response(View('maintenance'));
        }
        
        return $next($request);
    }
}
