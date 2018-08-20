<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/

/**
 * Model binding into route
 */
Route::model('blogcategory', 'App\BlogCategory');
Route::model('ads', 'App\Ads');
Route::model('blog', 'App\Blog');
Route::model('page', 'App\Page');
Route::model('classified', 'App\Classified');
Route::model('nearme', 'App\Nearme');
Route::model('classifiedcategory', 'App\ClassifiedCategory');
Route::model('images', 'App\Images');
Route::model('users', 'App\User');
Route::model('adrenewoption', 'App\AdRenewOption');

Route::pattern('slug', '[a-z0-9- _]+');


Route::get('forbidden', array('as' => 'forbidden', function () {
    return View::make('forbidden');
}));

Route::group(array('prefix' => 'admin', 'middleware' => ['Geoip', 'Https']), function () {

	# Error pages should be shown without requiring login
	Route::get('404', function () {
		return View('admin/404');
	});
	Route::get('500', function () {
		return View::make('admin/500');
	});

    Route::post('secureImage', array('as' => 'secureImage', 'uses' => 'WeedController@secureImage'));

	# Lock screen
	Route::get('{id}/lockscreen', array('as' => 'lockscreen', 'uses' =>'UsersController@lockscreen'));

	# All basic routes defined here

    Route::get('signin', array('as' => 'signin', 'uses' => 'AuthController@getSignin'));
	Route::post('signin', 'AuthController@postSignin');
    //admin signup
	//Route::post('signup', array('as' => 'signup', 'uses' => 'AuthController@postSignup'));
	Route::post('forgot-password', array('as' => 'forgot-password', 'uses' => 'AuthController@postForgotPassword'));

    /*
	# Register2
	Route::get('register2', function () {
		return View::make('admin/register2');
	});
	Route::post('register2', array('as' => 'register2', 'uses' => 'AuthController@postRegister2'));
    */

	# Forgot Password Confirmation
	Route::get('forgot-password/{userId}/{passwordResetCode}', array('as' => 'forgot-password-confirm', 'uses' => 'AuthController@getForgotPasswordConfirm'));
	Route::post('forgot-password/{userId}/{passwordResetCode}', 'AuthController@postForgotPasswordConfirm');

	# Logout
	Route::get('logout', array('as' => 'logout', 'uses' => 'AuthController@getLogout'));

	# Account Activation
	//Route::get('activate/{userId}/{activationCode}', array('as' => 'activate', 'uses' => 'AuthController@getActivate'));
});

Route::group(array('prefix' => 'admin',  'middleware' => ['Geoip', 'Https', 'SentinelAdmin', 'AdminPermissions']), function () {
    # Dashboard / Index
	Route::get('/', array('as' => 'dashboard','uses' => 'WeedController@showHome'));
    Route::get('modal/', array('as' => 'admin/modal', 'uses' => 'WeedController@modal'));

    Route::post('uploadimage','WeedController@cropImage');

    # User Management
    Route::group(array('prefix' => 'users'), function () {
        Route::get('export', 'UsersController@export');
        Route::post('export', 'UsersController@postExport');
        Route::get('imported', 'UsersController@importedList');

        Route::get('/', array('as' => 'users', 'uses' => 'UsersController@index'));
        Route::get('data',['as' => 'users.data', 'uses' =>'UsersController@data']);
        Route::get('create', 'UsersController@create');
        Route::post('create', 'UsersController@store');
        Route::get('{userId}/delete', array('as' => 'delete/user', 'uses' => 'UsersController@destroy'));
        Route::get('{userId}/confirm-delete', array('as' => 'confirm-delete/user', 'uses' => 'UsersController@getModalDelete'));
        Route::get('{userId}/restore', array('as' => 'restore/user', 'uses' => 'UsersController@getRestore'));
        Route::get('{userId}', array('as' => 'users.show', 'uses' => 'UsersController@show'));
        Route::post('{userId}/passwordreset', array('as' => 'passwordreset', 'uses' => 'UsersController@passwordreset'));

        Route::post('spammer/set', array('as' => 'spammer/set', 'uses' => 'UsersController@setSpammer'));
    });
    Route::resource('users', 'UsersController');
    Route::get('deleted_users',array('as' => 'deleted_users','before' => 'Sentinel', 'uses' => 'UsersController@getDeletedUsers'));

    Route::group(array('prefix' => 'access'), function () {
        Route::get('/', array('as' => 'access', 'uses' => 'AccessController@index'));
        Route::get('{access}/delete', array('as' => 'delete/access', 'uses' => 'AccessController@destroy'));
        Route::get('{access}/confirm-delete', array('as' => 'confirm-delete/access', 'uses' => 'AccessController@getModalDelete'));
        Route::get('{access}/edit', array('as' => 'edit/access', 'uses' => 'AccessController@edit'));
        Route::get('{access}/grant', array('as' => 'grant/access', 'uses' => 'AccessController@grantAccess'));
    });

	# Group Management
    Route::group(array('prefix' => 'groups'), function () {
        Route::get('/', array('as' => 'groups', 'uses' => 'GroupsController@index'));
        Route::get('create', array('as' => 'create/group', 'uses' => 'GroupsController@create'));
        Route::post('create', 'GroupsController@store');
        Route::get('{groupId}/edit', array('as' => 'update/group', 'uses' => 'GroupsController@edit'));
        Route::post('{groupId}/edit', 'GroupsController@update');
        Route::get('{groupId}/delete', array('as' => 'delete/group', 'uses' => 'GroupsController@destroy'));
        Route::get('{groupId}/confirm-delete', array('as' => 'confirm-delete/group', 'uses' => 'GroupsController@getModalDelete'));
        Route::get('{groupId}/restore', array('as' => 'restore/group', 'uses' => 'GroupsController@getRestore'));
    });

    /*routes for blog*/
	Route::group(array('prefix' => 'blog'), function () {
        Route::get('/', array('as' => 'blogs', 'uses' => 'BlogController@index'));
        Route::get('create', array('as' => 'create/blog', 'uses' => 'BlogController@create'));
        Route::post('create', 'BlogController@store');
        Route::get('{blog}/edit', array('as' => 'update/blog', 'uses' => 'BlogController@edit'));
        Route::post('{blog}/edit', 'BlogController@update');
        Route::get('{blog}/delete', array('as' => 'delete/blog', 'uses' => 'BlogController@destroy'));
		Route::get('{blog}/confirm-delete', array('as' => 'confirm-delete/blog', 'uses' => 'BlogController@getModalDelete'));
		Route::get('{blog}/restore', array('as' => 'restore/blog', 'uses' => 'BlogController@getRestore'));
        Route::get('{blog}/show', array('as' => 'blog/show', 'uses' => 'BlogController@show'));
        Route::post('{blog}/storecomment', array('as' => 'restore/blog', 'uses' => 'BlogController@storecomment'));
	});

    /*routes for blog category*/
	Route::group(array('prefix' => 'blogcategory'), function () {
        Route::get('/', array('as' => 'blogcategories', 'uses' => 'BlogCategoryController@index'));
        Route::get('create', array('as' => 'create/blogcategory', 'uses' => 'BlogCategoryController@create'));
        Route::post('create', 'BlogCategoryController@store');
        Route::get('{blogcategory}/edit', array('as' => 'update/blogcategory', 'uses' => 'BlogCategoryController@edit'));
        Route::post('{blogcategory}/edit', 'BlogCategoryController@update');
        Route::get('{blogcategory}/delete', array('as' => 'delete/blogcategory', 'uses' => 'BlogCategoryController@destroy'));
		Route::get('{blogcategory}/confirm-delete', array('as' => 'confirm-delete/blogcategory', 'uses' => 'BlogCategoryController@getModalDelete'));
		Route::get('{blogcategory}/restore', array('as' => 'restore/blogcategory', 'uses' => 'BlogCategoryController@getRestore'));
	});

	/*routes for page*/
	Route::group(array('prefix' => 'page'), function () {
        Route::get('/', array('as' => 'pages', 'uses' => 'PageController@index'));
        Route::get('create', array('as' => 'create/page', 'uses' => 'PageController@create'));
        Route::post('create', 'PageController@store');
        Route::get('{page}/edit', array('as' => 'edit/page', 'uses' => 'PageController@edit'));
        Route::post('{page}/edit', 'PageController@update');
        Route::get('{page}/delete', array('as' => 'delete/page', 'uses' => 'PageController@destroy'));
		Route::get('{page}/confirm-delete', array('as' => 'confirm-delete/page', 'uses' => 'PageController@getModalDelete'));
		Route::get('{page}/restore', array('as' => 'restore/page', 'uses' => 'PageController@getRestore'));
        Route::get('{page}/show', array('as' => 'show/page', 'uses' => 'PageController@show'));
	});

	/*routes for blocks*/
	Route::group(array('prefix' => 'block'), function () {
        Route::get('/', array('as' => 'blocks', 'uses' => 'BlockController@index'));
        Route::get('create', array('as' => 'create/block', 'uses' => 'BlockController@create'));
        Route::post('create', 'BlockController@store');
        Route::get('{block}/edit', array('as' => 'edit/block', 'uses' => 'BlockController@edit'));
        Route::post('{block}/edit', 'BlockController@update');
        Route::get('{block}/delete', array('as' => 'delete/block', 'uses' => 'BlockController@destroy'));
		Route::get('{block}/confirm-delete', array('as' => 'confirm-delete/block', 'uses' => 'BlockController@getModalDelete'));
		Route::get('{block}/restore', array('as' => 'restore/block', 'uses' => 'BlockController@getRestore'));
        Route::get('{block}/show', array('as' => 'show/block', 'uses' => 'BlockController@show'));
	});

	/*routes for menu*/
	Route::group(array('prefix' => 'menu'), function () {
        Route::get('/', array('as' => 'menu', 'uses' => 'MenuController@index'));
        Route::get('data', ['as' => 'menu.data', 'uses' =>'MenuController@data']);
        Route::get('move', ['as' => 'menu.data.move', 'uses' =>'MenuController@move']);
        Route::get('quickcreate', ['as' => 'menu.data.quickcreate', 'uses' =>'MenuController@quickCreate']);
        Route::get('quickrename', ['as' => 'menu.data.quickrename', 'uses' =>'MenuController@quickRename']);
        Route::get('quickremove', ['as' => 'menu.data.quickremove', 'uses' =>'MenuController@quickRemove']);
		Route::get('confirm-delete', array('as' => 'confirm-delete/menu', 'uses' => 'MenuController@getModalDelete'));

        Route::get('create', array('as' => 'create/menu', 'uses' => 'MenuController@create'));
        Route::post('create', 'MenuController@store');
        Route::get('{menu}/edit', array('as' => 'update/menu', 'uses' => 'MenuController@edit'));
        Route::post('{menu}/edit', 'MenuController@update');
    });

    /*routes for classified*/
	Route::group(array('prefix' => 'classified'), function () {
        Route::get('/', array('as' => 'admin.classifieds', 'uses' => 'ClassifiedController@index'));
		Route::get('data', ['as' => 'classifieds.data', 'uses' =>'ClassifiedController@data']);
        Route::get('create', array('as' => 'create/classified', 'uses' => 'ClassifiedController@create'));
        Route::post('create', 'ClassifiedController@store');
        Route::get('{classified}/edit', array('as' => 'update/classified', 'uses' => 'ClassifiedController@edit'));
        Route::post('{classified}/edit', 'ClassifiedController@update');
        Route::get('{classified}/delete', array('as' => 'delete/classified', 'uses' => 'ClassifiedController@destroy'));
		Route::get('{classified}/confirm-delete', array('as' => 'confirm-delete/classified', 'uses' => 'ClassifiedController@getModalDelete'));
		Route::get('{classified}/restore', array('as' => 'restore/classified', 'uses' => 'ClassifiedController@getRestore'));
        Route::get('{classified}/show', array('as' => 'classified/show', 'uses' => 'ClassifiedController@show'));
        Route::get('datalist', ['as' => 'classifieds.datalist', 'uses' =>'ClassifiedController@dataList']);

        Route::post('newclassified.category', ['as' => 'newclassified.category', 'uses' =>'ClassifiedCategoryController@getChildrensForSelect']);
    });

    /*routes for classified category schema*/
	Route::group(array('prefix' => 'classifiedschema'), function () {
        Route::get('/', array('as' => 'classifiedschema', 'uses' => 'ClassifiedSchemaController@index'));
        Route::get('data', ['as' => 'classifiedschema.data', 'uses' =>'ClassifiedSchemaController@data']);
        Route::get('move', ['as' => 'classifiedschema.data.move', 'uses' =>'ClassifiedSchemaController@move']);
        Route::get('quickcreate', ['as' => 'classifiedschema.data.quickcreate', 'uses' =>'ClassifiedSchemaController@quickCreate']);
        Route::get('quickrename', ['as' => 'classifiedschema.data.quickrename', 'uses' =>'ClassifiedSchemaController@quickRename']);
        Route::get('quickremove', ['as' => 'classifiedschema.data.quickremove', 'uses' =>'ClassifiedSchemaController@quickRemove']);
		Route::get('confirm-delete', array('as' => 'confirm-delete/classifiedschema', 'uses' => 'ClassifiedSchemaController@getModalDelete'));

        Route::get('create', array('as' => 'create/classifiedschema', 'uses' => 'ClassifiedSchemaController@create'));
        Route::post('create', 'ClassifiedSchemaController@store');
        Route::get('{classifiedschema}/edit', array('as' => 'update/classifiedschema', 'uses' => 'ClassifiedSchemaController@edit'));
        Route::post('{classifiedschema}/edit', 'ClassifiedSchemaController@update');

        Route::get('recreate', array('as' => 'recreateclassifiedcategories', 'uses' => 'ClassifiedSchemaController@recreateCategories'));
        Route::post('recreate', 'ClassifiedSchemaController@postRecreateCategories');
    });

    /*routes for classified category*/
	Route::group(array('prefix' => 'classifiedcategory'), function () {
        Route::get('/', array('as' => 'classifiedcategories', 'uses' => 'ClassifiedCategoryController@index'));
        Route::get('data', ['as' => 'classifiedcategories.data', 'uses' =>'ClassifiedCategoryController@data']);
        Route::get('data.move', ['as' => 'classifiedcategories.data.move', 'uses' =>'ClassifiedCategoryController@move']);
        Route::get('data.quickcreate', ['as' => 'classifiedcategories.data.quickcreate', 'uses' =>'ClassifiedCategoryController@quickcreate']);
        Route::get('data.quickrename', ['as' => 'classifiedcategories.data.quickrename', 'uses' =>'ClassifiedCategoryController@quickrename']);
        Route::get('data.quickremove', ['as' => 'classifiedcategories.data.quickremove', 'uses' =>'ClassifiedCategoryController@quickremove']);
        Route::get('create', array('as' => 'create/classifiedcategory', 'uses' => 'ClassifiedCategoryController@create'));
        Route::post('create', 'ClassifiedCategoryController@store');
        Route::get('{classifiedcategory}/edit', array('as' => 'update/classifiedcategory', 'uses' => 'ClassifiedCategoryController@edit'));
        Route::post('{classifiedcategory}/edit', 'ClassifiedCategoryController@update');
        Route::get('{classifiedcategory}/delete', array('as' => 'delete/classifiedcategory', 'uses' => 'ClassifiedCategoryController@destroy'));
		Route::get('confirm-delete', array('as' => 'confirm-delete/classifiedcategory', 'uses' => 'ClassifiedCategoryController@getModalDelete'));
		Route::get('{classifiedcategory}/restore', array('as' => 'restore/classifiedcategory', 'uses' => 'ClassifiedCategoryController@getRestore'));
	});

	 /*routes for nearme*/
	Route::group(array('prefix' => 'nearme'), function () {
        Route::get('/', array('as' => 'nearmes', 'uses' => 'NearmeController@index'));
        Route::get('approval', array('as' => 'nearmeapproval', 'uses' => 'NearmeController@approvalQueue'));
        Route::get('create', array('as' => 'create/nearme', 'uses' => 'NearmeController@create'));
        Route::post('create', 'NearmeController@store');
        Route::get('{nearme}/edit', array('as' => 'update/nearme', 'uses' => 'NearmeController@edit'));
        Route::post('{nearme}/edit', 'NearmeController@update');
        Route::get('{nearme}/delete', array('as' => 'delete/nearme', 'uses' => 'NearmeController@destroy'));
		Route::get('{nearme}/confirm-delete', array('as' => 'confirm-delete/nearme', 'uses' => 'NearmeController@getModalDelete'));
		Route::get('{nearme}/restore', array('as' => 'restore/nearme', 'uses' => 'NearmeController@getRestore'));
        Route::get('{nearme}/show', array('as' => 'nearme/show', 'uses' => 'NearmeController@show'));
        Route::get('datalist', ['as' => 'nearme.datalist', 'uses' =>'NearmeController@dataList']);
	});

    /*routes for nearme category*/
	Route::group(array('prefix' => 'nearmecategory'), function () {
        Route::get('/', array('as' => 'nearmecategories', 'uses' => 'NearmeCategoryController@index'));
        Route::get('create', array('as' => 'create/nearmecategory', 'uses' => 'NearmeCategoryController@create'));
        Route::post('create', 'NearmeCategoryController@store');
        Route::get('{nearmecategory}/edit', array('as' => 'update/nearmecategory', 'uses' => 'NearmeCategoryController@edit'));
        Route::post('{nearmecategory}/edit', 'NearmeCategoryController@update');
        Route::get('{nearmecategory}/delete', array('as' => 'delete/nearmecategory', 'uses' => 'NearmeCategoryController@destroy'));
		Route::get('{nearmecategory}/confirm-delete', array('as' => 'confirm-delete/nearmecategory', 'uses' => 'NearmeCategoryController@getModalDelete'));
		Route::get('{nearmecategory}/restore', array('as' => 'restore/nearmecategory', 'uses' => 'NearmeCategoryController@getRestore'));
	});

    /*routes for nearme items categories*/
	Route::group(array('prefix' => 'nearmeitemscategory'), function () {
        Route::get('/', array('as' => 'categories', 'uses' => 'NearmeItemsCategoryController@index'));
        Route::get('create', array('as' => 'create/nearmeitemscategory', 'uses' => 'NearmeItemsCategoryController@create'));
        Route::post('create', 'NearmeItemsCategoryController@store');
        Route::get('{nearmeitemscategory}/edit', array('as' => 'update/nearmeitemscategory', 'uses' => 'NearmeItemsCategoryController@edit'));
        Route::post('{nearmeitemscategory}/edit', 'NearmeItemsCategoryController@update');
        Route::get('{nearmeitemscategory}/delete', array('as' => 'delete/nearmeitemscategory', 'uses' => 'NearmeItemsCategoryController@destroy'));
		Route::get('{nearmeitemscategory}/confirm-delete', array('as' => 'confirm-delete/nearmeitemscategory', 'uses' => 'NearmeItemsCategoryController@getModalDelete'));
	});

	/*routes for ads*/
	Route::group(array('prefix' => 'ads'), function () {
        Route::get('/', array('as' => 'ads', 'uses' => 'AdsController@index'));
        Route::get('pending', array('as' => 'pendingads', 'uses' => 'AdsController@pending'));
        Route::get('create', array('as' => 'create/ads', 'uses' => 'AdsController@create'));
        Route::post('create', 'AdsController@store');
        Route::get('{ads}/edit', array('as' => 'update/ads', 'uses' => 'AdsController@edit'));
        Route::post('{ads}/edit', 'AdsController@update');
        Route::get('{ads}/delete', array('as' => 'delete/ads', 'uses' => 'AdsController@destroy'));
		Route::get('{ads}/confirm-delete', array('as' => 'confirm-delete/ads', 'uses' => 'AdsController@getModalDelete'));
		Route::get('{ads}/restore', array('as' => 'restore/ads', 'uses' => 'AdsController@getRestore'));
        Route::get('{ads}/show', array('as' => 'ads/show', 'uses' => 'AdsController@show'));
        Route::get('datalist', ['as' => 'ads.datalist', 'uses' =>'AdsController@dataList']);
	});

	/*routes for ads companies*/
	Route::group(array('prefix' => 'adscompanies'), function () {
        Route::get('/', array('as' => 'adscompanies', 'uses' => 'AdsCompaniesController@index'));
        Route::get('create', array('as' => 'create/adscompanies', 'uses' => 'AdsCompaniesController@create'));
        Route::post('create', 'AdsCompaniesController@store');
        Route::get('{adscompanies}/edit', array('as' => 'update/adscompanies', 'uses' => 'AdsCompaniesController@edit'));
        Route::post('{adscompanies}/edit', 'AdsCompaniesController@update');
        Route::get('{adscompanies}/delete', array('as' => 'delete/adscompanies', 'uses' => 'AdsCompaniesController@destroy'));
		Route::get('{adscompanies}/confirm-delete', array('as' => 'confirm-delete/adscompanies', 'uses' => 'AdsCompaniesController@getModalDelete'));
		Route::get('{adscompanies}/restore', array('as' => 'restore/adscompanies', 'uses' => 'AdsCompaniesController@getRestore'));
	});

    /*routes for ads positions*/
	Route::group(array('prefix' => 'adspositions'), function () {
        Route::get('/', array('as' => 'adspositions', 'uses' => 'AdsPositionsController@index'));
        Route::get('create', array('as' => 'create/adspositions', 'uses' => 'AdsPositionsController@create'));
        Route::post('create', 'AdsPositionsController@store');
        Route::get('{adspositions}/edit', array('as' => 'update/adspositions', 'uses' => 'AdsPositionsController@edit'));
        Route::post('{adspositions}/edit', 'AdsPositionsController@update');
        Route::get('{adspositions}/delete', array('as' => 'delete/adspositions', 'uses' => 'AdsPositionsController@destroy'));
		Route::get('{adspositions}/confirm-delete', array('as' => 'confirm-delete/adspositions', 'uses' => 'AdsPositionsController@getModalDelete'));
	});

	Route::group(array('prefix' => 'reporteditems'), function () {
        Route::get('/', array('as' => 'reporteditems', 'uses' => 'ReportedItemsController@index'));
		Route::get('{reporteditems}/approve', array('as' => 'approve/reporteditems', 'uses' => 'ReportedItemsController@approve'));
		Route::get('{reporteditems}/confirm', array('as' => 'confirm/reporteditems', 'uses' => 'ReportedItemsController@getModalConfirm'));
	});

	Route::group(array('prefix' => 'issues'), function () {
        Route::get('/', array('as' => 'issues', 'uses' => 'IssuesController@index'));
		Route::get('{issues}/approve', array('as' => 'approve/issues', 'uses' => 'IssuesController@approve'));
		Route::get('{issues}/confirm', array('as' => 'confirm/issues', 'uses' => 'IssuesController@getModalConfirm'));
	});

    Route::group(array('prefix' => 'settings'), function () {
        Route::get('/', array('as' => 'settings', 'uses' => 'SettingsController@index'));
        Route::post('edit', 'SettingsController@update');

        Route::get('cache', array('as' => 'cache', 'uses' => 'SettingsController@cache'));
        Route::get('clearcache', array('as' => 'clear.cache', 'uses' => 'SettingsController@clearCache'));

        Route::get('import', array('as' => 'nearmeimport', 'uses' => 'NearmeController@import'));
        Route::post('import', 'NearmeController@postImport');
	});

    Route::group(array('prefix' => 'adrenewoption'), function() {
        Route::get('/', array('as' => 'adrenewoption', 'uses' => 'AdRenewOptionController@index'));
        Route::get('create', array('as' => 'create/adrenewoption', 'uses' => 'AdRenewOptionController@create'));
        Route::post('create', 'AdRenewOptionController@store');
        Route::get('{adrenewoption}/edit', array('as' => 'edit/adrenewoption', 'uses' => 'AdRenewOptionController@edit'));
        Route::post('{adrenewoption}/edit', 'AdRenewOptionController@update');
        Route::get('{adrenewoption}/delete', array('as' => 'delete/adrenewoption', 'uses' => 'AdRenewOptionController@destroy'));
        Route::get('{adrenewoption}/confirm-delete', array('as' => 'confirm-delete/adrenewoption', 'uses' => 'AdRenewOptionController@getModalDelete'));
        Route::get('{adrenewoption}/show', array('as' => 'show/adrenewoption', 'uses' => 'AdRenewOptionController@show'));
    });

	Route::group(array('prefix' => 'payments'), function () {
        Route::get('/', array('as' => 'payments', 'uses' => 'PaymentsController@index'));
		Route::get('{payments}/delete', array('as' => 'delete/payments', 'uses' => 'PaymentsController@destroy'));
		Route::get('{payments}/confirm-delete', array('as' => 'confirm-delete/payments', 'uses' => 'PaymentsController@getModalDelete'));
        Route::get('datalist', ['as' => 'payments.datalist', 'uses' =>'PaymentsController@dataList']);
    });

	Route::group(array('prefix' => 'plans'), function () {
        Route::get('/', array('as' => 'plans', 'uses' => 'PlansController@index'));
        Route::get('classifiedcategories', ['as' => 'plans.classifiedcategories.data', 'uses' =>'PlansController@classifiedcategories']);
        Route::get('nearmecategories', ['as' => 'plans.nearmecategories.data', 'uses' =>'PlansController@nearmecategories']);
        Route::get('adspositions', ['as' => 'plans.adspositions.data', 'uses' =>'PlansController@adspositions']);
        Route::get('create', array('as' => 'create/plan', 'uses' => 'PlansController@create'));
        Route::post('create', 'PlansController@store');
        Route::get('{plan}/edit', array('as' => 'edit/plan', 'uses' => 'PlansController@edit'));
        Route::post('{plan}/edit', 'PlansController@update');
        Route::get('{plan}/delete', array('as' => 'delete/plan', 'uses' => 'PlansController@destroy'));
		Route::get('{plan}/confirm-delete', array('as' => 'confirm-delete/plan', 'uses' => 'PlansController@getModalDelete'));
		Route::get('{plan}/restore', array('as' => 'restore/plan', 'uses' => 'PlansController@getRestore'));
	});

    Route::group(array('prefix' => 'claim'), function () {
        Route::get('{user}/confirm-claim', array('as' => 'confirm-claim', 'uses' => 'ClaimController@getModalClaim'));
        Route::get('{user}/remove-claim', array('as' => 'remove-claim', 'uses' => 'ClaimController@getModalRemoveClaim'));
        Route::get('user/{user}', array('as' => 'claim.user', 'uses' => 'ClaimController@claimUser'));
        Route::get('unclaim/user/{user}', array('as' => 'unclaim.user', 'uses' => 'ClaimController@unClaimUser'));
    });

    Route::group(array('prefix' => 'sales'), function () {
        Route::get('reps', ['as' => 'sales.reps', 'uses' =>'SalesController@salesRepsReport']);
        Route::get('users', ['as' => 'sales.users', 'uses' =>'SalesController@salesUsersReport']);
        Route::get('usersDatalist', ['as' => 'sales.users.datalist', 'uses' =>'SalesController@usersDataList', 'middleware' => ['InternalAjax']]);

        Route::get('print/reps', ['as' => 'sales.reps.print', 'uses' =>'SalesController@printRepsReport', 'middleware' => ['InternalAjax']]);
        Route::get('print/users', ['as' => 'sales.users.print', 'uses' =>'SalesController@printUsersReport', 'middleware' => ['InternalAjax']]);

        Route::get('approve', ['as' => 'sales.approve', 'uses' =>'SalesController@salesApproval']);

        Route::get('{claim}/confirm-approve', array('as' => 'confirm-approve', 'uses' => 'SalesController@getApproveModal'));
        Route::get('{claim}/approve', array('as' => 'sales/approve/claim', 'uses' => 'SalesController@approveClaim'));

        Route::get('{claim}/confirm-disapprove', array('as' => 'confirm-disapprove', 'uses' => 'SalesController@getDisapproveModal'));
        Route::get('{claim}/disapprove', array('as' => 'sales/disapprove/claim', 'uses' => 'SalesController@disapproveClaim'));

        Route::get('reps/{user}/info', array('as' => 'sales/reps/info', 'uses' => 'SalesController@repsInfo'));
        Route::get('reps/{user}/info/print', array('as' => 'sales.reps.info.print', 'uses' => 'SalesController@printRepsDetailReport', 'middleware' => ['InternalAjax']));

        Route::get('users/{user}/info', array('as' => 'sales/users/info', 'uses' => 'SalesController@usersInfo'));
        Route::get('users/{user}/info/print', array('as' => 'sales.users.info.print', 'uses' => 'SalesController@printUsersDetailReport', 'middleware' => ['InternalAjax']));

        Route::get('downloadreport/{file}', array('as' => 'downloadfile', 'uses' => 'SalesController@downloadReport'));

		Route::group(array('prefix' => 'coupons'), function () {
			Route::get('', array('as' => 'coupons', 'uses' => 'CouponsController@index'));
			Route::get('create', array('as' => 'coupons.create', 'uses' => 'CouponsController@create'));
			Route::post('create', 'CouponsController@store');
            Route::get('{coupons}/edit', array('as' => 'update/coupons', 'uses' => 'CouponsController@edit'));
            Route::post('{coupons}/edit', 'CouponsController@update');
			Route::get('datalist', ['as' => 'coupons.datalist', 'uses' =>'CouponsController@dataList']);

			Route::put('{coupons}/edit', array('as' => 'updatead', 'uses' => 'CouponsController@edit'));
			Route::get('{coupons}/confirm-delete', array('as' => 'confirm-delete/coupons', 'uses' => 'CouponsController@getModalDelete'));
			Route::get('{coupons}/delete', array('as' => 'delete/coupons', 'uses' => 'CouponsController@destroy'));
		});
    });

    Route::group(array('prefix' => 'refund'), function () {
        Route::get('/', array('as' => 'refunds', 'uses' => 'RefundsController@index'));
        Route::get('{refunds}/delete', array('as' => 'delete/refunds', 'uses' => 'RefundsController@destroy'));
		Route::get('{refunds}/confirm-delete', array('as' => 'confirm-delete/refund', 'uses' => 'RefundsController@getModalDelete'));
        Route::get('datalist', ['as' => 'refunds.datalist', 'uses' =>'RefundsController@dataList']);

        Route::post('refund', array('as' => 'refund', 'uses' => 'RefundsController@refund'));
    });

    Route::group(array('prefix' => 'reports'), function () {
        Route::get('ads', array('as' => 'reports/ads', 'uses' => 'ReportsController@adsReport'));
        Route::get('datalist/ads', ['as' => 'reports.ads.datalist', 'uses' =>'ReportsController@adsDataList', 'middleware' => ['InternalAjax']]);
        Route::get('export/ads', array('as' => 'reports/export/ads', 'uses' => 'ReportsController@adsExportReport'));

        Route::get('nearme', array('as' => 'reports/nearme', 'uses' => 'ReportsController@nearmeReport'));
        Route::get('datalist/nearme', ['as' => 'reports.nearme.datalist', 'uses' =>'ReportsController@nearmeDataList', 'middleware' => ['InternalAjax']]);
        Route::get('export/nearme', array('as' => 'reports/export/nearme', 'uses' => 'ReportsController@nearmeExportReport'));

        Route::get('classified', array('as' => 'reports/classified', 'uses' => 'ReportsController@classifiedReport'));
        Route::get('datalist/classified', ['as' => 'reports.classified.datalist', 'uses' =>'ReportsController@classifiedDataList'/*, 'middleware' => ['InternalAjax']*/]);
        Route::get('export/classified', array('as' => 'reports/export/classified', 'uses' => 'ReportsController@classifiedExportReport'));
    });

    Route::get('downloadagreement/{id}', array('as' => 'downloadagreement', 'uses' => 'PaymentsController@downloadAgreement'));
	Route::get('{name?}', 'WeedController@showView');

});

#only guests
Route::group(array('prefix' => '',  'middleware' => ['Geoip', 'Maintenance', 'Https', 'Guest']), function () {
    Route::get('modal', array('as' => 'modal', 'uses' => 'WeedController@modal'));

    Route::get('login', array('as' => 'login','uses' => 'FrontEndController@getLogin'));
    Route::post('login','FrontEndController@postLogin');
    Route::get('register', array('as' => 'register','uses' => 'FrontEndController@getRegister'));
    Route::post('register','FrontEndController@postRegister');

    //Social Login
    Route::get('login/{provider?}',['uses' => 'SocialController@getSocialAuth', 'as' => 'auth.getSocialAuth']);
    Route::get('login/callback/{provider?}',['uses' => 'SocialController@getSocialAuthCallback', 'as' => 'auth.getSocialAuthCallback']);

    Route::get('activation', array('as' =>'activation','uses'=>'FrontEndController@getActivation'));
    Route::post('activation','FrontEndController@resendActivation');
    Route::get('activate/{userId}/{activationCode}',array('as' =>'activate','uses'=>'FrontEndController@getActivate'));
    Route::get('forgot-password',array('as' => 'forgot-password','uses' => 'FrontEndController@getForgotPassword'));
    Route::post('forgot-password','FrontEndController@postForgotPassword');
    # Forgot Password Confirmation
    Route::get('forgot-password/{userId}/{passwordResetCode}', array('as' => 'forgot-password-confirm', 'uses' => 'FrontEndController@getForgotPasswordConfirm'));
    Route::post('forgot-password/{userId}/{passwordResetCode}', 'FrontEndController@postForgotPasswordConfirm');
    # My account display and update details
});

Route::group(array('prefix' => '',  'middleware' => ['Geoip', 'Maintenance', 'Https', 'CheckUserLocationData']), function () {
    #FrontEndController
    Route::post('changecity','FrontEndController@changeClassifiedsCategory');

    Route::group(array('middleware' => 'SentinelUser'), function () {
        Route::get('my-account', array('as' => 'my-account', 'uses' => 'FrontEndController@myAccount'));
        Route::put('my-account', 'FrontEndController@update');

        Route::post('uploadimage','WeedController@cropImage');


        Route::group(array('middleware' => 'UserPermissions'), function () {
            #nearme
            Route::group(array('prefix' => '', 'middleware' => ['FilterRequest']), function () {
                Route::post('newnearme', array('as' => 'savenewnearme', 'uses' => 'NearmeController@frontendStore'));
                Route::put('nearme/{nearme}/edit', array('as' => 'updatenearme', 'uses' => 'NearmeController@frontendUpdate'));
            });

            Route::get('newnearme', array('as' => 'newnearme', 'uses' => 'NearmeController@frontendCreate'));
            Route::get('mynearme', array('as' => 'mynearme', 'uses' => 'NearmeController@userList'));
            Route::get('nearmeedit/{slug?}', array('as' => 'nearmeedit', 'uses' => 'NearmeController@frontendEdit'));
            Route::get('nearme/{nearme}/confirm-delete', array('as' => 'nearme/confirm-delete/nearme', 'uses' => 'NearmeController@getFrontModalDelete'));
            Route::get('nearme/{nearme}/delete', array('as' => 'delete/nearme/nearme', 'uses' => 'NearmeController@frontDestroy'));
            Route::get('nearme/{nearme}/confirm-unpublish', array('as' => 'nearme/confirm-unpublish/nearme', 'uses' => 'NearmeController@getFrontModalUnpublish'));
            Route::get('nearme/{nearme}/unpublish', array('as' => 'unpublish/nearme/nearme', 'uses' => 'NearmeController@frontUnpublish'));

            #classifieds
            Route::get('newclassified', array('as' => 'newclassified', 'uses' => 'ClassifiedController@frontendCreate'));

            Route::group(array('prefix' => '', 'middleware' => ['WordsFilter', 'FilterRequest']), function () {
                Route::post('newclassified', array('as' => 'saveclassified', 'uses' => 'ClassifiedController@frontendStore'));
                Route::put('classified/{classified}/edit', array('as' => 'updateclassified', 'uses' => 'ClassifiedController@frontendUpdate'));
            });

            Route::post('newclassified.category', ['as' => 'newclassified.category', 'uses' =>'ClassifiedCategoryController@getChildrensForSelect']);
            Route::post('classified.category.showmulticity', ['as' => 'classified.category.showmulticity', 'uses' =>'ClassifiedCategoryController@canHaveMuliticity']);
            Route::post('classified.category.multicity', ['as' => 'classified.category.multicity', 'uses' =>'ClassifiedCategoryController@getMulticityDropdown']);
            Route::get('myclassifieds', array('as' => 'myclassifieds', 'uses' => 'ClassifiedController@userList'));
            Route::get('classifiededit/{slug?}', array('as' => 'classifiededit', 'uses' => 'ClassifiedController@frontendEdit'));
            Route::get('classifieditem/{classified}/confirm-delete', array('as' => 'classifieditem/confirm-delete/classified', 'uses' => 'ClassifiedController@getFrontModalDelete'));
            Route::get('classifieditem/{classified}/delete', array('as' => 'classifieditem/delete/classified', 'uses' => 'ClassifiedController@frontDestroy'));
            Route::get('classifieditem/{classified}/confirm-unpublish', array('as' => 'classifieditem/confirm-unpublish/classified', 'uses' => 'ClassifiedController@getFrontModalUnpublish'));
            Route::get('classifieditem/{classified}/unpublish', array('as' => 'classifieditem/unpublish/classified', 'uses' => 'ClassifiedController@frontUnpublish'));

            #ads
            Route::group(array('prefix' => '', 'middleware' => ['FilterRequest']), function () {
                Route::post('newad', array('as' => 'savead', 'uses' => 'AdsController@frontendStore'));
                Route::put('ad/{ads}/edit', array('as' => 'updatead', 'uses' => 'AdsController@frontendUpdate'));
            });
            Route::get('newad', array('as' => 'newad', 'uses' => 'AdsController@frontendCreate'));
            Route::get('myads', array('as' => 'myads', 'uses' => 'AdsController@userList'));
            Route::get('adedit/{slug?}', array('as' => 'adedit', 'uses' => 'AdsController@frontendEdit'));
            Route::get('ad/{ads}/confirm-delete', array('as' => 'ad/confirm-delete/ad', 'uses' => 'AdsController@getFrontModalDelete'));
            Route::get('ad/{ads}/delete', array('as' => 'delete/ad/ad', 'uses' => 'AdsController@frontDestroy'));

            #payments
            Route::get('transactions', array('as' => 'transactions', 'uses' => 'PaymentsController@userList'));
            Route::get('payment', array('as' => 'payment', 'uses' => 'PaymentsController@paymentForm'));
            Route::get('update/vault', array('as' => 'vaultlist', 'uses' => 'PaymentsController@vaultList'));
            Route::get('update/vault/{vault_id}', array('as' => 'updatevault', 'uses' => 'PaymentsController@updateVault'));
            Route::post('processpayment', array('as' => 'processpayment', 'uses' => 'PaymentsController@proccessPayment', 'middleware' => ['CreditCard']));
            Route::post('update/vault/{vault_id}', array('as' => 'postupdatevault', 'uses' => 'PaymentsController@postUpdateVault', 'middleware' => ['CreditCard']));
            Route::get('processpayment/success', array('as' => 'successpayment', 'uses' => 'PaymentsController@successPayment'));
            Route::get('processpayment/fail', array('as' => 'failedpayment', 'uses' => 'PaymentsController@failedPayment'));
            Route::get('freeads', array('as' => 'freeads', 'uses' => 'PaymentsController@freeExpiredAdsReview'));
            Route::post('freeads', array('as' => 'freeads', 'uses' => 'PaymentsController@freeExpiredAdsReviewToCheckout'));
        });

        #redirect for user which do not have access to above
        Route::get('access', array('as' => 'access', 'uses' => 'AccessController@indexFront'));
        Route::get('access/{type}', array('as' => 'access', 'uses' => 'AccessController@userAccess'));
        Route::group(array('prefix' => '', 'middleware' => ['FilterRequest']), function () {
            Route::post('access', array('as' => 'postaccess', 'uses' => 'AccessController@postAccess'));
        });

        #cart
        Route::get('review', array('as' => 'review', 'uses' => 'PaymentsController@review'));
        Route::get('pendingpayment', array('as' => 'pendingpayment', 'uses' => 'PaymentsController@pending'));
        Route::get('review/{item}/confirm-delete', array('as' => 'review/confirm-delete/item', 'uses' => 'PaymentsController@reviewRemoveItemConfirm'));
        Route::get('review/{item}/delete', array('as' => 'review/item/delete', 'uses' => 'PaymentsController@reviewRemoveItem'));
        Route::get('review/{item}/add/{type}', array('as' => 'review/item/add/type', 'uses' => 'PaymentsController@reviewAddItem'));

        Route::post('coupon/submit', 'CouponsController@submitCoupon');

        Route::get('getagreement', array('as' => 'getagreement', 'uses' => 'PaymentsController@getAgreement'));
    });

    Route::get('logout', array('as' => 'logout','uses' => 'FrontEndController@getLogout'));

    # contact form
    Route::group(array('prefix' => '', 'middleware' => ['FilterRequest']), function () {
        Route::post('contact', array('as' => 'contact','uses' => 'FrontEndController@postContact'));
    });

    #frontend views

    #home page
    Route::group(array('prefix' => ''), function () {
        Route::get('/', array('as' => 'home', 'uses' => 'FrontEndController@index'));
        Route::get('/changenearmelocation', array('as' => 'changenearmelocation', 'uses' => 'FrontEndController@changenearmelocation'));
        Route::get('/changelocation', array('as' => 'changelocation', 'uses' => 'FrontEndController@changeLocation', 'middleware' => ['Search']));
        Route::get('/changeclassifiedslocation', array('as' => 'changeclassifiedslocation', 'uses' => 'FrontEndController@changeclassifiedslocation'));
    });

    #blog
    Route::group(array('prefix' => ''), function () {
        Route::get('blog', array('as' => 'blog', 'uses' => 'BlogController@getIndexFrontend'));
        Route::get('blogcategory/{slug?}', array('as' => 'blogcategory', 'uses' => 'BlogController@getCategoryList'));
        Route::get('blog/{slug}/tag', 'BlogController@getBlogTagFrontend');
        Route::get('blogitem/{slug?}', 'BlogController@getBlogFrontend');
        Route::group(array('prefix' => '', 'middleware' => ['FilterRequest']), function () {
            Route::post('blogitem/{blog}/comment', 'BlogController@storeCommentFrontend');
        });
    });

    #nearme
    Route::group(array('prefix' => ''), function () {
        Route::get('nearme', array('as' => 'nearme', 'uses' => 'NearmeController@getIndexFrontend'));
        Route::get('searchnearme/{squery?}', array('as' => 'searchnearme', 'uses' => 'NearmeController@getIndexFrontend'));
        Route::get('nearmecategory/{slug?}', array('as' => 'nearmecategory', 'uses' => 'NearmeController@getNearmeCategory'));
        Route::get('nearme/{slug?}', array('as' => 'nearme', 'uses' => 'NearmeController@getNearmeFrontend'));
    });

    #classifieds
    Route::group(array('prefix' => ''), function () {
        Route::get('classifieds', array('as' => 'classifieds', 'uses' => 'ClassifiedCategoryController@getIndexFrontend'));
        Route::get('data.move', ['as' => 'classifiedcategories.data.move', 'uses' =>'ClassifiedCategoryController@move']);
        Route::get('classifiedscountry', 'ClassifiedCategoryController@changeCategoryCountry');
        Route::get('classifieds/{slug?}', array('as' => 'classifiedscategory', 'uses' => 'ClassifiedCategoryController@getFrontendCategory'));
        Route::get('classified/{slug?}', array('as' => 'classified', 'uses' => 'ClassifiedController@getClassifiedFrontend'));

        Route::get('classifieditem/{classified}/confirm-report', array('as' => 'classifieditem/confirm-report/classified', 'uses' => 'ClassifiedController@getReportModal'));
        Route::post('classified/{classified}/report', array('as' => 'classified/report', 'uses' => 'ClassifiedController@reportItem'));
    });

    #ads
    Route::group(array('prefix' => ''), function () {
        Route::get('add/link/{id?}', 'AdsController@adLink');
    });

    #search
    Route::group(array('prefix' => 'search'), function () {
        Route::get('{query?}', array('as' => 'search', 'uses' => 'SearchController@search'));
        Route::get('type/{stype}/query/{squery}', array('as' => 'type/stype/query/squery', 'uses' => 'SearchController@typeSearch'));
        Route::get('type/{stype}/query/{squery}/category/{scategory}', array('as' => 'type/stype/query/squery/category/scategory', 'uses' => 'SearchController@typeSearch'));
    });

    Route::get('report', 'WeedController@reportIssue');
    Route::post('report', 'WeedController@postReportIssue');
    Route::get('sitemap', 'WeedController@getSitemap');

    //Route::get('test', 'WeedController@test');

    Route::post('signagreement', array('as' => 'postsignagreement', 'uses' => 'PaymentsController@helloSignPostCallback'));
    Route::get('signagreement', array('as' => 'getsignagreement', 'uses' => 'PaymentsController@helloSignGetCallback'));

    Route::get('{name?}', 'WeedController@showFrontEndView');
    # End of frontend views

});
