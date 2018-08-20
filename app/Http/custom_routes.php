<?php

Route::group(array('middleware' => 'SentinelUser'), function () {
    Route::get('placead', 'WeedController@userPage');
});