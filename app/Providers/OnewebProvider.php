<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Oneweb\Extensions\PublishedCheckpoint;

class OnewebProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        
        $checkpoint = new PublishedCheckpoint();
        
		\Sentinel::addCheckpoint('published', $checkpoint);
		$this->app->singleton('sentinel.published', function($app) {
			$config = $app['config']->get('cartalyst.sentinel');
		});
        
    }
}
