<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Application's Global Middleware Stack
    |--------------------------------------------------------------------------
    |
    | The application's global HTTP middleware stack. These middleware are run during every request to your application.
    | @var array
    */

    'middleware' => [
        \EaseAppPHP\Http\Middleware\EARequestResponseTimeMiddleware::class,
        \EaseAppPHP\Http\Middleware\EACorsMiddleware::class,
        \EaseAppPHP\Http\Middleware\EAAppBrowserCacheHeadersMiddleware::class,
        
        
    ],
    
    /*
    |--------------------------------------------------------------------------
    | Application's Route Middleware Groups
    |--------------------------------------------------------------------------
    |
    | The application's route middleware groups.
    | @var array
    */

    'middlewareGroups' => [
        'ajax' => [
            \EaseAppPHP\Http\Middleware\EAAppSecurityHeadersMiddleware::class,
            \EaseAppPHP\Http\Middleware\StartSession::class,
        ],
        'web' => [
            
        ],
        'api' => [
            'throttle:60,1',
            'bindings',
        ],
        
    ],
    
    /*
    |--------------------------------------------------------------------------
    | Application's Route Middleware
    |--------------------------------------------------------------------------
    |
    | The application's route middleware. These middleware may be assigned to groups or used individually.
    | @var array
    */

    'routeMiddleware' => [
        'throttle' => \EaseAppPHP\Http\Middleware\HelloMiddleware::class,
        'auth' => \EaseAppPHP\Http\Middleware\Auth::class,
        'hostnamecheck' => \EaseAppPHP\Http\Middleware\HostnameCheck::class,
        'startsession' => \EaseAppPHP\Http\Middleware\StartSession::class,
    ],
    
    /*
    |--------------------------------------------------------------------------
    | Application's Priority-sorted list of Middleware
    |--------------------------------------------------------------------------
    |
    | The priority-sorted list of middleware. This forces non-global middleware to always be in the given order.
    | @var array
    */

    /*'middlewarePriority' => [
        \Illuminate\Session\Middleware\StartSession::class,
        \Illuminate\View\Middleware\ShareErrorsFromSession::class,
        \App\Http\Middleware\Authenticate::class,
        \Illuminate\Routing\Middleware\ThrottleRequests::class,
        \Illuminate\Session\Middleware\AuthenticateSession::class,
        \Illuminate\Routing\Middleware\SubstituteBindings::class,
        \Illuminate\Auth\Middleware\Authorize::class,
    ],*/
    	
];