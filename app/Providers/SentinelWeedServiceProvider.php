<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Cartalyst\Sentinel\Laravel\SentinelServiceProvider as SentinelProvider;
use Cartalyst\Sentinel\Sessions\IlluminateSession;
use Cartalyst\Sentinel\Cookies\IlluminateCookie;

use App\Helpers\Template;

class SentinelWeedServiceProvider extends SentinelProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
       parent::boot();
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        parent::register();
    }
    
    protected function registerSession()
    {
        if(Template::isAdminRoute()) {
            $this->app->singleton('sentinel.session', function ($app) {
                $key = $app['config']->get('cartalyst.sentinel.session');

                return new IlluminateSession($app['session.store'], $key);
            });
        } else {
            $this->app->singleton('sentinel.session', function ($app) {
                $key = $app['config']->get('cartalyst.sentinel.session_admin');

                return new IlluminateSession($app['session.store'], $key);
            });
        }
    }
    
    protected function registerCookie()
    {
        if(Template::isAdminRoute()) {
            $this->app->singleton('sentinel.cookie', function ($app) {
                $key = $app['config']->get('cartalyst.sentinel.cookie_admin');

                return new IlluminateCookie($app['request'], $app['cookie'], $key);
            });
        } else {
            $this->app->singleton('sentinel.cookie', function ($app) {
                $key = $app['config']->get('cartalyst.sentinel.cookie');

                return new IlluminateCookie($app['request'], $app['cookie'], $key);
            });
        }
    }
}
