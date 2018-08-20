<?php

namespace App\Providers;

use Artisan;
use App\Helpers\CacheHelper;
use App\Classified;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
		parent::boot();
        \Blade::setEchoFormat('e(utf8_encode(%s))');

        //clear cached when classified is saved
        Classified::saved(function($classified) {
            CacheHelper::reloadActiveClassifieds();
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
	{

	}
}
