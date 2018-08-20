<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use File;

class MacroServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        $directory = base_path() . '/resources/macros/';
        $files = File::allFiles($directory);
        foreach($files as $file) {
            require $file->getPathname();
        }
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        
    }
}
