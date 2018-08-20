<?php

namespace App\Http\Middleware;

use Closure;

class CreditCard
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
        $date = $request->get('cc_date');
        $date = str_replace(' ', '', $date);
        if ($date) {
            $request->merge(array('cc_date' => $date));
        }
        
        $number = $request->get('cc_number');
        $number = str_replace(' ', '', $number);
        if ($number) {
            $request->merge(array('cc_number' => $number));
        }
        
        return $next($request);
    }
    
    
}
