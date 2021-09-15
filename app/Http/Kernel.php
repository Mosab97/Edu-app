<?php

namespace App\Http;

use App\Http\Middleware\BranchMiddleware;
use App\Http\Middleware\RestaurantMiddleware;
use App\Http\Middleware\SetLocalLanguage;
use App\Http\Middleware\SetLocalLanguageWeb;
use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    protected $middleware = [
        // \App\Http\Middleware\TrustHosts::class,
        \App\Http\Middleware\TrustProxies::class,
        \Fruitcake\Cors\HandleCors::class,
        \App\Http\Middleware\CheckForMaintenanceMode::class,
        \Illuminate\Foundation\Http\Middleware\ValidatePostSize::class,
        \App\Http\Middleware\TrimStrings::class,
        \Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull::class,
    ];
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
            SetLocalLanguage::class,
        ],
    ];

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
        'restaurant' => RestaurantMiddleware::class,
        'role' => \Spatie\Permission\Middlewares\RoleMiddleware::class,
        'permission' => \Spatie\Permission\Middlewares\PermissionMiddleware::class,
        'localization' => \App\Http\Middleware\localization::class,
        'CheckIsClient' => \App\Http\Middleware\CheckIsClient::class,
        'CheckIsDistributor' => \App\Http\Middleware\CheckIsDistributor::class,
        'CheckIsNotBlocked' => \App\Http\Middleware\CheckIsNotBlocked::class,
        'CheckIsActive' => \App\Http\Middleware\CheckIsActive::class,
        'CheckIsVerified' => \App\Http\Middleware\CheckIsVerified::class,
        'CheckHasCountry' => \App\Http\Middleware\CheckHasCountry::class,
        'AppVersion' => \App\Http\Middleware\AppVersion::class,
        'CheckAppIsStopped' => \App\Http\Middleware\CheckAppIsStopped::class,
        'CheckOrderOwner' => \App\Http\Middleware\CheckOrderOwner::class,
        'CheckTokenFromDashboard' => \App\Http\Middleware\CheckTokenFromDashboard::class,

    ];
    protected $middlewarePriority = [
        'localization' => \App\Http\Middleware\localization::class,
    ];

}
