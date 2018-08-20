<?php

namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    /**
     * The application's global HTTP middleware stack.
     *
     * These middleware are run during every request to your application.
     *
     * @var array
     */
    protected $middleware = [
        \Illuminate\Foundation\Http\Middleware\CheckForMaintenanceMode::class,
    ];

    /**
     * The application's route middleware groups.
     *
     * @var array
     */
    protected $middlewareGroups = [
        'web' => [
            \App\Http\Middleware\EncryptCookies::class,
            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
            \Illuminate\Session\Middleware\StartSession::class,
            \Illuminate\View\Middleware\ShareErrorsFromSession::class,
            \App\Http\Middleware\VerifyCsrfToken::class,
        ],

        'api' => [
            'throttle:60,1',
        ],
    ];

    /**
     * The application's route middleware.
     *
     * These middleware may be assigned to groups or used individually.
     *
     * @var array
     */
    protected $routeMiddleware = [
        'auth' => \App\Http\Middleware\Authenticate::class,
        'auth.basic' => \Illuminate\Auth\Middleware\AuthenticateWithBasicAuth::class,
        'can' => \Illuminate\Foundation\Http\Middleware\Authorize::class,
        'guest' => \App\Http\Middleware\RedirectIfAuthenticated::class,
        'throttle' => \Illuminate\Routing\Middleware\ThrottleRequests::class,
        'Guest' => \App\Http\Middleware\Guest::class,
        'SentinelUser' => \App\Http\Middleware\SentinelUser::class,
        'SentinelAdmin' => \App\Http\Middleware\SentinelAdmin::class,
        'AdminPermissions' => \App\Http\Middleware\CheckAdminPermissions::class,
        'UserPermissions' => \App\Http\Middleware\CheckUserPermissions::class,
        'Geoip' => \App\Http\Middleware\GeoipMiddleware::class,
        'WordsFilter' => \App\Http\Middleware\WordsFilterMiddleware::class,
        'FilterRequest' => \App\Http\Middleware\FilterRequest::class,
        'Https' => \App\Http\Middleware\HttpsProtocol::class,
        'Maintenance' => \App\Http\Middleware\Maintenance::class,
        'Search' => \App\Http\Middleware\Search::class,
        'CreditCard' => \App\Http\Middleware\CreditCard::class,
        'InternalAjax' => \App\Http\Middleware\InternalAjax::class,
        'CheckUserLocationData' => \App\Http\Middleware\CheckUserLocationData::class,
    ];
}
