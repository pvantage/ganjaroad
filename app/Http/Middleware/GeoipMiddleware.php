<?php

namespace App\Http\Middleware;

use Closure;
use Response;
use GeoIP;
use App\Helpers\Template;
use Session;

class GeoipMiddleware
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
		$location = GeoIP::getLocation();
		$white_list = explode(',', Template::getSetting('whitelist_ip'));
		$black_list = explode(',',Template::getSetting('blacklist_ip'));
		$countries = explode(',',Template::getSetting('allowed_countries'));

        if($location && $user_ip = $location['ip']) {

            if(in_array($user_ip, $white_list)) {
				return $next($request);
			}

            if(in_array($user_ip, $black_list)) {
				$error = trans('geoip/general.blacklisted');

                return redirect('forbidden')->withErrors([trans('geoip/general.blacklisted')]);
			}
			
			if(!in_array($location['iso_code'], $countries)) {
				$error = trans('geoip/general.countryblocked');
				
				return redirect('forbidden')->withErrors([trans('geoip/general.countryblocked')]);
			}
		}
		if($request->route()->getName() != 'nearme' && Session::get('nearme_search')) {
            Session::forget('nearme_search');
        }

        return $next($request);
    }
}
