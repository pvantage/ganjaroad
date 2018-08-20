<?php namespace Oneweb\Facades;

use Illuminate\Support\Facades\Facade;

class Published extends Facade
{
    protected static function getFacadeAccessor()
    {
    	return 'sentinel.published';
    }
}
