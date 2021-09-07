<?php

namespace App\Http;

use App\Http\Middleware\AssignGuard;
use App\Http\Middleware\BranchMiddleware;
use App\Http\Middleware\CheckForAllScopes;
use App\Http\Middleware\CheckIsAuth;
use App\Http\Middleware\RestaurantMiddleware;
use App\Http\Middleware\SetLocalLanguage;
use App\Http\Middleware\SetLocalLanguageWeb;
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
        // \App\Http\Middleware\TrustHosts::class,
        \App\Http\Middleware\TrustProxies::class,
        \Fruitcake\Cors\HandleCors::class,
        \App\Http\Middleware\CheckForMaintenanceMode::class,
        \Illuminate\Foundation\Http\Middleware\ValidatePostSize::class,
        \App\Http\Middleware\TrimStrings::class,
        \Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull::class,
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
            // \Illuminate\Session\Middleware\AuthenticateSession::class,
            \Illuminate\View\Middleware\ShareErrorsFromSession::class,
            \App\Http\Middleware\VerifyCsrfToken::class,
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
            SetLocalLanguage::class,
        ],

        'api' => [
            'throttle:60,1',
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
//            SetLocalLanguage::class,
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
        'manager' => \App\Http\Middleware\RedirectIfNotManager::class,
        'manager.guest' => \App\Http\Middleware\RedirectIfManager::class,
        'auth' => \App\Http\Middleware\Authenticate::class,
        'auth.basic' => \Illuminate\Auth\Middleware\AuthenticateWithBasicAuth::class,
        'bindings' => \Illuminate\Routing\Middleware\SubstituteBindings::class,
        'cache.headers' => \Illuminate\Http\Middleware\SetCacheHeaders::class,
        'can' => \Illuminate\Auth\Middleware\Authorize::class,
        'guest' => \App\Http\Middleware\RedirectIfAuthenticated::class,
        'password.confirm' => \Illuminate\Auth\Middleware\RequirePassword::class,
        'signed' => \Illuminate\Routing\Middleware\ValidateSignature::class,
        'throttle' => \Illuminate\Routing\Middleware\ThrottleRequests::class,
        'verified' => \Illuminate\Auth\Middleware\EnsureEmailIsVerified::class,
        'localWeb' => SetLocalLanguageWeb::class,
        'branch' => BranchMiddleware::class,
        'CheckIsAuth' => CheckIsAuth::class,
        'role' => \Spatie\Permission\Middlewares\RoleMiddleware::class,
        'permission' => \Spatie\Permission\Middlewares\PermissionMiddleware::class,
//        'localization' => \App\Http\Middleware\localization::class,
//        'scopes' => CheckForAllScopes::class,
//        'scopes' => \Laravel\Passport\Http\Middleware\CheckScopes::class,
//        'scope' => \Laravel\Passport\Http\Middleware\CheckForAnyScope::class,
        'auth_guard' => AssignGuard::class,
    ];


//    protected $middlewarePriority = [
//        'localization' => \App\Http\Middleware\localization::class,
//    ];

}