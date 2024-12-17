<?php

namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    /**
     * The application's global HTTP middleware stack.
     *
     * @var array<int, class-string|string>
     */
    protected $middleware = [
        // \App\Http\Middleware\TrustHosts::class,
        \App\Http\Middleware\TrustProxies::class,
        \Illuminate\Http\Middleware\HandleCors::class,
        \App\Http\Middleware\PreventRequestsDuringMaintenance::class,
        \Illuminate\Foundation\Http\Middleware\ValidatePostSize::class,
        \App\Http\Middleware\TrimStrings::class,
        \Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull::class,
    ];

    /**
     * The application's route middleware groups.
     *
     * @var array<string, array<int, class-string|string>>
     */
    protected $middlewareGroups = [
        'web' => [
            \App\Http\Middleware\EncryptCookies::class,
            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
            \Illuminate\Session\Middleware\StartSession::class,
            \Illuminate\View\Middleware\ShareErrorsFromSession::class,
            \App\Http\Middleware\VerifyCsrfToken::class,
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ],

        'api' => [
            \Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class,
            \Illuminate\Routing\Middleware\ThrottleRequests::class.':api',
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ],
    ];

    /**
     * The application's middleware aliases.
     * The application's route middleware aliases.
     *
     * Aliases may be used instead of class names to assign middleware to routes and groups.
     *
     * @var array
     */
    protected $middlewareAliases = [
        'auth' => \App\Http\Middleware\Authenticate::class,
        'auth.basic' => \Illuminate\Auth\Middleware\AuthenticateWithBasicAuth::class,
        'permission' => \App\Http\Middleware\CheckPermission::class,
        // ... other middleware aliases
    ];

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Configure the rate limiters for the application.
     *
     * @return void
     */
    protected function configureRateLimiting()
    {
        //
    }

    /**
     * Configure the error handlers for the application.
     *
     * @return void
     */
    protected function configureExceptionHandling()
    {
        //
    }

    /**
     * Configure the request middleware for the application.
     *
     * @return void
     */
    protected function configureRequestMiddleware()
    {
        //
    }

    /**
     * Configure the response middleware for the application.
     *
     * @return void
     */
    protected function configureResponseMiddleware()
    {
        //
    }

    /**
     * Configure the session middleware for the application.
     *
     * @return void
     */
    protected function configureSessionMiddleware()
    {
        //
    }

    /**
     * Configure the route middleware for the application.
     *
     * @return void
     */
    protected function configureRouteMiddleware()
    {
        //
    }

    /**
     * Configure the route middleware groups for the application.
     *
     * @return void
     */
    protected function configureRouteMiddlewareGroups()
    {
        //
    }

    /**
     * Configure the route middleware aliases for the application.
     *
     * @return void
     */
    protected function configureRouteMiddlewareAliases()
    {
        //
    }

    /**
     * Configure the application's route middleware.
     *
     * @return void
     */
    protected function configureMiddleware()
    {
        //
    }

    /**
     * Configure the application's route middleware groups.
     *
     * @return void
     */
    protected function configureMiddlewareGroups()
    {
        //
    }

    /**
     * Configure the application's route middleware aliases.
     *
     * @return void
     */
    protected function configureMiddlewareAliases()
    {
        //
    }

    /**
     * Configure the application's route middleware.
     *
     * @return void
     */
    protected function configureRouteMiddleware()
    {
        //
    }

    /**
     * Configure the application's route middleware groups.
     *
     * @return void
     */
    protected function configureRouteMiddlewareGroups()
    {
        //
    }

    /**
     * Configure the application's route middleware aliases.
     *
     * @return void
     */
    protected function configureRouteMiddlewareAliases()
    {
        //
    }

    /**
     * Configure the application's route middleware.
     *
     * @return void
     */
    protected function configureMiddleware()
    {
        //
    }

    /**
     * Configure the application's route middleware groups.
     *
     * @return void
     */
    protected function configureMiddlewareGroups()
    {
        //
    }

    /**
     * Configure the application's route middleware aliases.
     *
     * @return void
     */
    protected function configureMiddlewareAliases()
    {
        //
    }

    /**
     * Configure the application's route middleware.
     *
     * @return void
     */
    protected function configureRouteMiddleware()
    {
        //
    }

    /**
     * Configure the application's route middleware groups.
     *
     * @return void
     */
    protected function configureRouteMiddlewareGroups()
    {
        //
    }

    /**
     * Configure the application's route middleware aliases.
     *
     * @return void
     */
    protected function configureRouteMiddlewareAliases()
    {
        //
    }

    /**
     * Configure the application's route middleware.
     *
     * @return void
     */
    protected function configureMiddleware()
    {
        //
    }

    /**
     * Configure the application's route middleware groups.
     *
     * @return void
     */
    protected function configureMiddlewareGroups()
    {
        //
    }

    /**
     * Configure the application's route middleware aliases.
     *
     * @return void
     */
    protected function configureMiddlewareAliases()
    {
        //
    }

    /**
     * Configure the application's route middleware.
     *
     * @return void
     */
    protected function configureRouteMiddleware()
    {
        //
    }

    /**
     * Configure the application's route middleware groups.
     *
     * @return void
     */
    protected function configureRouteMiddlewareGroups()
    {
        //
    }

    /**
     * Configure the application's route middleware aliases.
     *
     * @return void
     */
    protected function configureRouteMiddlewareAliases()
    {
        //
    }

    /**
     * Configure the application's route middleware.
     *
     * @return void
     */
    protected function configureMiddleware()
    {
        //
    }

    /**
     * Configure the application's route middleware groups.
     *
     * @return void
     */
    protected function configureMiddlewareGroups()
    {
        //
    }

    /**
     * Configure the application's route middleware aliases.
     *
     * @return void
     */
    protected function configureMiddlewareAliases()
    {
        //
    }

    /**
     * Configure the application's route middleware.
     *
     * @return void
     */
    protected function configureRouteMiddleware()
    {
        //
    }

    /**
     * Configure the application's route middleware groups.
     *
     * @return void
     */
    protected function configureRouteMiddlewareGroups()
    {
        //
    }

    /**
     * Configure the application's route middleware aliases.
     *
     * @return void
     */
    protected function configureRouteMiddlewareAliases()
    {
        //
    }

    /**
     * Configure the application's route middleware.
     *
     * @return void
     */
    protected function configureMiddleware()
    {
        //
    }

    /**
     * Configure the application's route middleware groups.
     *
     * @return void
     */
    protected function configureMiddlewareGroups()
    {
        //
    }

    /**
     * Configure the application's route middleware aliases.
     *
     * @return void
     */
    protected function configureMiddlewareAliases()
    {
        //
    }

    /**
     * Configure the application's route middleware.
     *
     * @return void
     */
    protected function configureRouteMiddleware()
    {
        //
    }

    /**
     * Configure the application's route middleware groups.
     *
     * @return void
     */
    protected function configureRouteMiddlewareGroups()
    {
        //
    }

    /**
     * Configure the application's route middleware aliases.
     *
     * @return void
     */
    protected function configureRouteMiddlewareAliases()
    {
        //
    }

    /**
     * Configure the application's route middleware.
     *
     * @return void
     */
    protected function configureMiddleware()
    {
        //
    }

    /**
     * Configure the application's route middleware groups.
     *
     * @return void
     */
    protected function configureMiddlewareGroups()
    {
        //
    }

    /**
     * Configure the application's route middleware aliases.
     *
     * @return void
     */
    protected function configureMiddlewareAliases()
    {
        //
    }

    /**
     * Configure the application's route middleware.
     *
     * @return void
     */
    protected function configureRouteMiddleware()
    {
        //
    }

    /**
     * Configure the application's route middleware groups.
     *
     * @return void
     */
    protected function configureRouteMiddlewareGroups()
    {
        //
    }

    /**
     * Configure the application's route middleware aliases.
     *
     * @return void
     */
    protected function configureRouteMiddlewareAliases()
    {
        //
    }

    /**
     * Configure the application's route middleware.
     *
     * @return void
     */
    protected function configureMiddleware()
    {
        //
    }

    /**
     * Configure the application's route middleware groups.
     *
     * @return void
     */
    protected function configureMiddlewareGroups()
    {
        //
    }

    /**
     * Configure the application's route middleware aliases.
     *
     * @return void
     */
    protected function configureMiddlewareAliases()
    {
        //
    }

    /**
     * Configure the application's route middleware.
     *
     * @return void
     */
    protected function configureRouteMiddleware()
    {
        //
    }

    /**
     * Configure the application's route middleware groups.
     *
     * @return void
     */
    protected function configureRouteMiddlewareGroups()
    {
        //
    }

    /**
     * Configure the application's route middleware aliases.
     *
     * @return void
     */
    protected function configureRouteMiddlewareAliases()
    {
        //
    }

    /**
     * Configure the application's route middleware.
     *
     * @return void
     */
    protected function configureMiddleware()
    {
        //
    }

    /**
     * Configure the application's route middleware groups.
     *
     * @return void
     */
    protected function configureMiddlewareGroups()
    {
        //
    }

    /**
     * Configure the application's route middleware aliases.
     *
     * @return void
     */
    protected function configureMiddlewareAliases()
    {
        //
    }

    /**
     * Configure the application's route middleware.
     *
     * @return void
     */
    protected function configureRouteMiddleware()
    {
        //
    }

    /**
     * Configure the application's route middleware groups.
     *
     * @return void
     */
    protected function configureRouteMiddlewareGroups()
    {
        //
    }

    /**
     * Configure the application's route middleware aliases.
     *
     * @return void
     */
    protected function configureRouteMiddlewareAliases()
    {
        //
    }

    /**
     * Configure the application's route middleware.
     *
     * @return void
     */
    protected function configureMiddleware()
    {
        //
    }

    /**
     * Configure the application's route middleware groups.
     *
     * @return void
     */
    protected function configureMiddlewareGroups()
    {
        //
    }

    /**
     * Configure the application's route middleware aliases.
     *
     * @return void
     */
    protected function configureMiddlewareAliases()
    {
        //
    }

    /**
     * Configure the application's route middleware.
     *
     * @return void
     */
    protected function configureRouteMiddleware()
    {
        //
    }

    /**
     * Configure the application's route middleware groups.
     *
     * @return void
     */
    protected function configureRouteMiddlewareGroups()
    {
        //
    }

    /**
     * Configure the application's route middleware aliases.
     *
     * @return void
     */
    protected function configureRouteMiddlewareAliases()
    {
        //
    }

    /**
     * Configure the application's route middleware.
     *
     * @return void
     */
    protected function configureMiddleware()
    {
        //
    }

    /**
     * Configure the application's route middleware groups.
     *
     * @return void
     */
    protected function configureMiddlewareGroups()
    {
        //
    }

    /**
     * Configure the application's route middleware aliases.
     *
     * @return void
     */
    protected function configureMiddlewareAliases()
    {
        //
    }

    /**
     * Configure the application's route middleware.
     *
     * @return void
     */
    protected function configureRouteMiddleware()
    {
        //
    }

    /**
     * Configure the application's route middleware groups.
     *
     * @return void
     */
    protected function configureRouteMiddlewareGroups()
    {
        //
    }

    /**
     * Configure the application's route middleware aliases.
     *
     * @return void
     */
    protected function configureRouteMiddlewareAliases()
    {
        //
    }

    /**
     * Configure the application's route middleware.
     *
     * @return void
     */
    protected function configureMiddleware()
    {
        //
    }

    /**
     * Configure the application's route middleware groups.
     *
     * @return void
     */
    protected function configureMiddlewareGroups()
    {
        //
    }

    /**
     * Configure the application's route middleware aliases.
     *
     * @return void
     */
    protected function configureMiddlewareAliases()
    {
        //
    }

    /**
     * Configure the application's route middleware.
     *
     * @return void
     */
    protected function configureRouteMiddleware()
    {
        //
    }

    /**
     * Configure the application's route middleware groups.
     *
     * @return void
     */
    protected function configureRouteMiddlewareGroups()
    {
        //
    }

    /**
     * Configure the application's route middleware aliases.
     *
     * @return void
     */
    protected function configureRouteMiddlewareAliases()
    {
        //
    }

    /**
     * Configure the application's route middleware.
     *
     * @return void
     */
    protected function configureMiddleware()
    {
        //
    }

    /**
     * Configure the application's route middleware groups.
     *
     * @return void
     */
    protected function configureMiddlewareGroups()
    {
        //
    }

    /**
     * Configure the application's route middleware aliases.
     *
     * @return void
     */
    protected function configureMiddlewareAliases()
    {
        //
    }

    /**
     * Configure the application's route middleware.
     *
     * @return void
     */
    protected function configureRouteMiddleware()
    {
        //
    }

    /**
     * Configure the application's route middleware groups.
     *
     * @return void
     */
    protected function configureRouteMiddlewareGroups()
    {
        //
    }

    /**
     * Configure the application's route middleware aliases.
     *
     * @return void
     */
    protected function configureRouteMiddlewareAliases()
    {
        //
    }

    /**
     * Configure the application's route middleware.
     *
     * @return void
     */
    protected function configureMiddleware()
    {
        //
    }

    /**
     * Configure the application's route middleware groups.
     *
     * @return void
     */
    protected function configureMiddlewareGroups()
    {
        //
    }

    /**
     * Configure the application's route middleware aliases.
     *
     * @return void
     */
    protected function configureMiddlewareAliases()
    {
        //
    }

    /**
     * Configure the application's route middleware.
     *
     * @return void
     */
    protected function configureRouteMiddleware()
    {
        //
    }

    /**
     * Configure the application's route middleware groups.
     *
     * @return void
     */
    protected function configureRouteMiddlewareGroups()
    {
        //
    }

    /**
     * Configure the application's route middleware aliases.
     *
     * @return void
     */
    protected function configureRouteMiddlewareAliases()
    {
        //
    }

    /**
     * Configure the application's route middleware.
     *
     * @return void
     */
    protected function configureMiddleware()
    {
        //
    }

    /**
     * Configure the application's route middleware groups.
     *
     * @return void
     */
    protected function configureMiddlewareGroups()
    {
        //
    }

    /**
     * Configure the application's route middleware aliases.
     *
     * @return void
     */
    protected function configureMiddlewareAliases()
    {
        //
    }

    /**
     * Configure the application's route middleware.
     *
     * @return void
     */
    protected function configureRouteMiddleware()
    {
        //
    }

    /**
     * Configure the application's route middleware groups.
     *
     * @return void
     */
    protected function configureRouteMiddlewareGroups()
    {
        //
    }

    /**
     * Configure the application's route middleware aliases.
     *
     * @return void
     */
    protected function configureRouteMiddlewareAliases()
    {
        //
    }

    /**
     * Configure the application's route middleware.
     *
     * @return void
     */
    protected function configureMiddleware()
    {
        //
    }

    /**
     * Configure the application's route middleware groups.
     *
     * @return void
     */
    protected function configureMiddlewareGroups()
    {
        //
    }

    /**
     * Configure the application's route middleware aliases.
     *
     * @return void
     */
    protected function configureMiddlewareAliases()
    {
        //
    }

    /**
     * Configure the application's route middleware.
     *
     * @return void
     */
    protected function configureRouteMiddleware()
    {
        //
    }

    /**
     * Configure the application's route middleware groups.
     *
     * @return void
     */
    protected function configureRouteMiddlewareGroups()
    {
        //
    }

    /**
     * Configure the application's route middleware aliases.
     *
     * @return void
     */
    protected function configureRouteMiddlewareAliases()
    {
        //
    }

    /**
     * Configure the application's route middleware.
     *
     * @return void
     */
    protected function configureMiddleware()
    {
        //
    }

    /**
     * Configure the application's route middleware groups.
     *
     * @return void
     */
    protected function configureMiddlewareGroups()
    {
        //
    }

    /**
     * Configure the application's route middleware aliases.
     *
     * @return void
     */
    protected function configureMiddlewareAliases()
    {
        //
    }

    /**
     * Configure the application's route middleware.
     *
     * @return void
     */
    protected function configureRouteMiddleware()
    {
        //
    }

    /**
     * Configure the application's route middleware groups.
     *
     * @return void
     */
    protected function configureRouteMiddlewareGroups()
    {
        //
    }

    /**
     * Configure the application's route middleware aliases.
     *
     * @return void
     */
    protected function configureRouteMiddlewareAliases()
    {
        //
    }

    /**
     * Configure the application's route middleware.
     *
     * @return void
     */
    protected function configureMiddleware()
    {
        //
    }

    /**
     * Configure the application's route middleware groups.
     *
     * @return void
     */
    protected function configureMiddlewareGroups()
    {
        //
    }

    /**
     * Configure the application's route middleware aliases.
     *
     * @return void
     */
    protected function configureMiddlewareAliases()
    {
        //
    }

    /**
     * Configure the application's route middleware.
     *
     * @return void
     */
    protected function configureRouteMiddleware()
    {
        //
    }

    /**
     * Configure the application's route middleware groups.
     *
     * @return void
     */
    protected function configureRouteMiddlewareGroups()
    {
        //
    }

    /**
     * Configure the application's route middleware aliases.
     *
     * @return void
     */
    protected function configureRouteMiddlewareAliases()
    {
        //
    }

    /**
     * Configure the application's route middleware.
     *
     * @return void
     */
    protected function configureMiddleware()
    {
        //
    }

    /**
     * Configure the application's route middleware groups.
     *
     * @return void
     */
    protected function configureMiddlewareGroups()
    {
        //
    }

    /**
     * Configure the application's route middleware aliases.
     *
     * @return void
     */
    protected function configureMiddlewareAliases()
    {
        //
    }

    /**
     * Configure the application's route middleware.
     *
     * @return void
     */
    protected function configureRouteMiddleware()
    {
        //
    }

    /**
     * Configure the application's route middleware groups.
     *
     * @return void
     */
    protected function configureRouteMiddlewareGroups()
    {
        //
    }

    /**
     * Configure the application's route middleware aliases.
     *
     * @return void
     */
    protected function configureRouteMiddlewareAliases()
    {
        //
    }

    /**
     * Configure the application's route middleware.
     *
     * @return void
     */
    protected function configureMiddleware()
    {
        //
    }

    /**
     * Configure the application's route middleware groups.
     *
     * @return void
     */
    protected function configureMiddlewareGroups()
    {
        //
    }

    /**
     * Configure the application's route middleware aliases.
     *
     * @return void
     */
    protected function configureMiddlewareAliases()
    {
        //
    }

    /**
     * Configure the application's route middleware.
     *
     * @return void
     */
    protected function configureRouteMiddleware()
    {
        //
    }

    /**
     * Configure the application's route middleware groups.
     *
     * @return void
     */
    protected function configureRouteMiddlewareGroups()
    {
        //
    }

    /**
     * Configure the application's route middleware aliases.
     *
     * @return void
     */
    protected function configureRouteMiddlewareAliases()
    {
        //
    }

    /**
     * Configure the application's route middleware.
     *
     * @return void
     */
    protected function configureMiddleware()
    {
        //
    }

    /**
     * Configure the application's route middleware groups.
     *
     * @return void
     */
    protected function configureMiddlewareGroups()
    {
        //
    }

    /**
     * Configure the application's route middleware aliases.
     *
     * @return void
     */
    protected function configureMiddlewareAliases()
    {
        //
    }

    /**
     * Configure the application's route middleware.
     *
     * @return void
     */
    protected function configureRouteMiddleware()
    {
        //
    }

    /**
     * Configure the application's route middleware groups.
     *
     * @return void
     */
    protected function configureRouteMiddlewareGroups()
    {
        //
    }

    /**
     * Configure the application's route middleware aliases.
     *
     * @return void
     */
    protected function configureRouteMiddlewareAliases()
    {
        //
    }

    /**
     * Configure the application's route middleware.
     *
     * @return void
     */
    protected function configureMiddleware()
    {
        //
    }

    /**
     * Configure the application's route middleware groups.
     *
     * @return void
     */
    protected function configureMiddlewareGroups()
    {
        //
    }

    /**
     * Configure the application's route middleware aliases.
     *
     * @return void
     */
    protected function configureMiddlewareAliases()
    {
        //
    }

    /**
     * Configure the application's route middleware.
     *
     * @return void
     */
    protected function configureRouteMiddleware()
    {
        //
    }

    /**
     * Configure the application's route middleware groups.
     *
     * @return void
     */
    protected function configureRouteMiddlewareGroups()
    {
        //
    }

    /**
     * Configure the application's route middleware aliases.
     *
     * @return void
     */
    protected function configureRouteMiddlewareAliases()
    {
        //
    }

    /**
     * Configure the application's route middleware.
     *
     * @return void
     */
    protected function configureMiddleware()
    {
        //
    }

    /**
     * Configure the application's route middleware groups.
     *
     * @return void
     */
    protected function configureMiddlewareGroups()
    {
        //
    }

    /**
     * Configure the application's route middleware aliases.
     *
     * @return void
     */
    protected function configureMiddlewareAliases()
    {
        //
    }

    /**
     * Configure the application's route middleware.
     *
     * @return void
     */
    protected function configureRouteMiddleware()
    {
        //
    }

    /**
     * Configure the application's route middleware groups.
     *
     * @return void
     */
    protected function configureRouteMiddlewareGroups()
    {
        //
    }

    /**
     * Configure the application's route middleware aliases.
     *
     * @return void
     */
    protected function configureRouteMiddlewareAliases()
    {
        //
    }

    /**
     * Configure the application's route middleware.
     *
     * @return void
     */
    protected function configureMiddleware()
    {
        //
    }

    /**
     * Configure the application's route middleware groups.
     *
     * @return void
     */
    protected function configureMiddlewareGroups()
    {
        //
    }

    /**
     * Configure the application's route middleware aliases.
     *
     * @return void
     */
    protected function configureMiddlewareAliases()
    {
        //
    }

    /**
     * Configure the application's route middleware.
     *
     * @return void
     */
    protected function configureRouteMiddleware()
    {
        //
    }

    /**
     * Configure the application's route middleware groups.
     *
     * @return void
     */
    protected function configureRouteMiddlewareGroups()
    {
        //
    }

    /**
     * Configure the application's route middleware aliases.
     *
     * @return void
     */
    protected function configureRouteMiddlewareAliases()
    {
        //
    }

    /**
     * Configure the application's route middleware.
     *
     * @return void
     */
    protected function configureMiddleware()
    {
        //
    }

    /**
     * Configure the application's route middleware groups.
     *
     * @return void
     */
    protected function configureMiddlewareGroups()
    {
        //
    }

    /**
     * Configure the application's route middleware aliases.
     *
     * @return void
     */
    protected function configureMiddlewareAliases()
    {
        //
    }

    /**
     * Configure the application's route middleware.
     *
     * @return void
     */
    protected function configureRouteMiddleware()
    {
        //
    }

    /**
     * Configure the application's route middleware groups.
     *
     * @return void
     */
    protected function configureRouteMiddlewareGroups()
    {
        //
    }

    /**
     * Configure the application's route middleware aliases.
     *
     * @return void
     */
    protected function configureRouteMiddlewareAliases()
    {
        //
    }

    /**
     * Configure the application's route middleware.
     *
     * @return void
     */
    protected function configureMiddleware()
    {
        //
    }

    /**
     * Configure the application's route middleware groups.
     *
     * @return void
     */
    protected function configureMiddlewareGroups()
    {
        //
    }

    /**
     * Configure the application's route middleware aliases.
     *
     * @return void
     */
    protected function configureMiddlewareAliases()
    {
        //
    }

    /**
     * Configure the application's route middleware.
     *
     * @return void
     */
    protected function configureRouteMiddleware()
    {
        //
    }

    /**
     * Configure the application's route middleware groups.
     *
     * @return void
     */
    protected function configureRouteMiddlewareGroups()
    {
        //
    }

    /**
     * Configure the application's route middleware aliases.
     *
     * @return void
     */
    protected function configureRouteMiddlewareAliases()
    {
        //
    }

    /**
     * Configure the application's route middleware.
     *
     * @return void
     */
    protected function configureMiddleware()
    {
        //
    }

    /**
     * Configure the application's route middleware groups.
     *
     * @return void
     */
    protected function configureMiddlewareGroups()
    {
        //
    }

    /**
     * Configure the application's route middleware aliases.
     *
     * @return void
     */
    protected function configureMiddlewareAliases()
    {
        //
    }

    /**
     * Configure the application's route middleware.
     *
     * @return void
     */
    protected function configureRouteMiddleware()
    {
        //
    }

    /**
     * Configure the application's route middleware groups.
     *
     * @return void
     */
    protected function configureRouteMiddlewareGroups()
    {
        //
    }

    /**
     * Configure the application's route middleware aliases.
     *
     * @return void
     */
    protected function configureRouteMiddlewareAliases()
    {
        //
    }

    /**
     * Configure the application's route middleware.
     *
     * @return void
     */
    protected function configureMiddleware()
    {
        //
    }

    /**
     * Configure the application's route middleware groups.
     *
     * @return void
     */
    protected function configureMiddlewareGroups()
    {
        //
    }

    /**
     * Configure the application's route middleware aliases.
     *
     * @return void
     */
    protected function configureMiddlewareAliases()
    {
        //
    }

    /**
     * Configure the application's route middleware.
     *
     * @return void
     */
    protected function configureRouteMiddleware()
    {
        //
    }

    /**
     * Configure the application's route middleware groups.
     *
     * @return void
     */
    protected function configureRouteMiddlewareGroups()
    {
        //
    }

    /**
     * Configure the application's route middleware aliases.
     *
     * @return void
     */
    protected function configureRouteMiddlewareAliases()
    {
        //
    }

    /**
     * Configure the application's route middleware.
     *
     * @return void
     */
    protected function configureMiddleware()
    {
        //
    }

    /**
     * Configure the application's route middleware groups.
     *
     * @return void
     */
    protected function configureMiddlewareGroups()
    {
        //
    }

    /**
     * Configure the application's route middleware aliases.
     *
     * @return void
     */
    protected function configureMiddlewareAliases()
    {
        //
    }

    /**
     * Configure the application's route middleware.
     *
     * @return void
     */
    protected function configureRouteMiddleware()
    {
        //
    }

    /**
     * Configure the application's route middleware groups.
     *
     * @return void
     */
    protected function configureRouteMiddlewareGroups()
    {
        //
    }

    /**
     * Configure the application's route middleware aliases.
     *
     * @return void
     */
    protected function configureRouteMiddlewareAliases()
    {
        //
    }

    /**
     * Configure the application's route middleware.
     *
     * @return void
     */
    protected function configureMiddleware()
    {
        //
    }

    /**
     * Configure the application's route middleware groups.
     *
     * @return void
     */
    protected function configureMiddlewareGroups()
    {
        //
    }

    /**
     * Configure the application's route middleware aliases.
     *
     * @return void
     */
    protected function configureMiddlewareAliases()
    {
        //
    }

    /**
     * Configure the application's route middleware.
     *
     * @return void
     */
    protected function configureRouteMiddleware()
    {
        //
    }

    /**
     * Configure the application's route middleware groups.
     *
     * @return void
     */
    protected function configureRouteMiddlewareGroups()
    {
        //
    }

    /**
     * Configure the application's route middleware aliases.
     *
     * @return void
     */
    protected function configureRouteMiddlewareAliases()
    {
        //
    }

    /**
     * Configure the application's route middleware.
     *
     * @return void
     */
    protected function configureMiddleware()
    {
        //
    }

    /**
     * Configure the application's route middleware groups.
     *
     * @return void
     */
    protected function configureMiddlewareGroups()
    {
        //
    }

    /**
     * Configure the application's route middleware aliases.
     *
     * @return void
     */
    protected function configureMiddlewareAliases()
    {
        //
    }

    /**
     * Configure the application's route middleware.
     *
     * @return void
     */
    protected function configureRouteMiddleware()
    {
        //
    }

    /**
     * Configure the application's route middleware groups.
     *
     * @return void
     */
    protected function configureRouteMiddlewareGroups()
    {
        //
    }

    /**
     * Configure the application's route middleware aliases.
     *
     * @return void
     */
    protected function configureRouteMiddlewareAliases()
    {
        //
    }

    /**
     * Configure the application's route middleware.
     *
     * @return void
     */
    protected function configureMiddleware()
    {
        //
    }

    /**
     * Configure the application's route middleware groups.
     *
     * @return void
     */
    protected function configureMiddlewareGroups()
    {
        //
    }

    /**
     * Configure the application's route middleware aliases.
     *
     * @return void
     */
    protected function configureMiddlewareAliases()
    {
        //
    }

    /**
     * Configure the application's route middleware.
     *
     * @return void
     */
    protected function configureRouteMiddleware()
    {
        //
    }

    /**
     * Configure the application's route middleware groups.
     *
     * @return void
     */
    protected function configureRouteMiddlewareGroups()
    {
        //
    }

    /**
     * Configure the application's route middleware aliases.
     *
     * @return void
     */
    protected function configureRouteMiddlewareAliases()
    {
        //
    }

    /**
     * Configure the application's route middleware.
     *
     * @return void
     */
    protected function configureMiddleware()
    {
        //
    }

    /**
     * Configure the application's route middleware groups.
     *
     * @return void
     */
    protected function configureMiddlewareGroups()
    {
        //
    }

    /**
     * Configure the application's route middleware aliases.
     *
     * @return void
     */
    protected function configureMiddlewareAliases()
    {
        //
    }

    /**
     * Configure the application's route middleware.
     *
     * @return void
     */
    protected function configureRouteMiddleware()
    {
        //
    }

    /**
     * Configure the application's route middleware groups.
     *
     * @return void
     */
    protected function configureRouteMiddlewareGroups()
    {
        //
    }

    /**
     * Configure the application's route middleware aliases.
     *
     * @return void
     */
    protected function configureRouteMiddlewareAliases()
    {
        //
    }

    /**
     * Configure the application's route middleware.
     *
     * @return void
     */
    protected function configureMiddleware()
    {
        //
    }

    /**
     * Configure the application's route middleware groups.
     *
     * @return void
     */
    protected function configureMiddlewareGroups()
    {
        //
    }

    /**
     * Configure the application's route middleware aliases.
     *
     * @return void
     */
    protected function configureMiddlewareAliases()
    {
        //
    }

    /**
     * Configure the application's route middleware.
     *
     * @return void
     */
    protected function configureRouteMiddleware()
    {
        //
    }

    /**
     * Configure the application's route middleware groups.
     *
     * @return void
     */
    protected function configureRouteMiddlewareGroups()
    {
        //
    }

    /**
     * Configure the application's route middleware aliases.
     *
     * @return void
     */
    protected function configureRouteMiddlewareAliases()
    {
        //
    }

    /**
     * Configure the application's route middleware.
     *
     * @return void
     */
    protected function configureMiddleware()
    {
        //
    }

    /**
     * Configure the application's route middleware groups.
     *
     * @return void
     */
    protected function configureMiddlewareGroups()
    {
        //
    }

    /**
     * Configure the application's route middleware aliases.
     *
     * @return void
     */
    protected function configureMiddlewareAliases()
    {
        //
    }

    /**
     * Configure the application's route middleware.
     *
     * @return void
     */
    protected function configureRouteMiddleware()
    {
        //
    }

    /**
     * Configure the application's route middleware groups.
     *
     * @return void
     */
    protected function configureRouteMiddlewareGroups()
    {
        //
    }

    /**
     * Configure the application's route middleware aliases.
     *
     * @return void
     */
    protected function configureRouteMiddlewareAliases()
    {
        //
    }

    /**
     * Configure the application's route middleware.
     *
     * @return void
     */
    protected function configureMiddleware()
    {
        //
    }

    /**
     * Configure the application's route middleware groups.
     *
     * @return void
     */
    protected function configureMiddlewareGroups()
    {
        //
    }

    /**
     * Configure the application's route middleware aliases.
     *
     * @return void
     */
    protected function configureMiddlewareAliases()
    {
        //
    }

    /**
     * Configure the application's route middleware.
     *
     * @return void
     */
    protected function configureRouteMiddleware()
    {
        //
    }

    /**
     * Configure the application's route middleware groups.
     *
     * @return void
     */
    protected function configureRouteMiddlewareGroups()
    {
        //
    }

    /**
     * Configure the application's route middleware aliases.
     *
     * @return void
     */
    protected function configureRouteMiddlewareAliases()
    {
        //
    }

    /**
     * Configure the application's route middleware.
     *
     * @return void
     */
    protected function configureMiddleware()
    {
        //
    }

    /**
     * Configure the application's route middleware groups.
     *
     * @return void
     */
    protected function configureMiddlewareGroups()
    {
        //
    }

    /**
     * Configure the application's route middleware aliases.
     *
     * @return void
     */
    protected function configureMiddlewareAliases()
    {
        //
    }

    /**
     * Configure the application's route middleware.
     *
     * @return void
     */
    protected function configureRouteMiddleware()
    {
        //
    }

    /**
     * Configure the application's route middleware groups.
     *
     * @return void
     */
    protected function configureRouteMiddlewareGroups()
    {
        //
    }

    /**
     * Configure the application's route middleware aliases.
     *
     * @return void
     */
    protected function configureRouteMiddlewareAliases()
    {
        //
    }

    /**
     * Configure the application's route middleware.
     *
     * @return void
     */
    protected function configureMiddleware()
    {
        //
    }

    /**
     * Configure the application's route middleware groups.
     *
     * @return void
     */
    protected function configureMiddlewareGroups()
    {
        //
    }

    /**
     * Configure the application's route middleware aliases.
     *
     * @return void
     */
    protected function configureMiddlewareAliases()
    {
        //
    }

    /**
     * Configure the application's route middleware.
     *
     * @return void
     */
    protected function configureRouteMiddleware()
    {
        //
    }

    /**
     * Configure the application's route middleware groups.
     *
     * @return void
     */
    protected function configureRouteMiddlewareGroups()
    {
        //
    }

    /**
     * Configure the application's route middleware aliases.
     *
     * @return void
     */
    protected function configureRouteMiddlewareAliases()
    {
        //
    }

    /**
     * Configure the application's route middleware.
     *
     * @return void
     */
    protected function configureMiddleware()
    {
        //
    }

    /**
     * Configure the application's route middleware groups.
     *
     * @return void
     */
    protected function configureMiddlewareGroups()
    {
        //
    }

    /**
     * Configure the application's route middleware aliases.
     *
     * @return void
     */
    protected function configureMiddlewareAliases()
    {
        //
    }

    /**
     * Configure the application's route middleware.
     *
     * @return void
     */
    protected function configureRouteMiddleware()
    {
        //
    }

    /**
     * Configure the application's route middleware groups.
     *
     * @return void
     */
    protected function configureRouteMiddlewareGroups()
    {
        //
    }

    /**
     * Configure the application's route middleware aliases.
     *
     * @return void
     */
    protected function configureRouteMiddlewareAliases()
    {
        //
    }

    /**
     * Configure the application's route middleware.
     *
     * @return void
     */
    protected function configureMiddleware()
    {
        //
    }

    /**
     * Configure the application's route middleware groups.
     *
     * @return void
     */
    protected function configureMiddlewareGroups()
    {
        //
    }

    /**
     * Configure the application's route middleware aliases.
     *
     * @return void
     */
    protected function configureMiddlewareAliases()
    {
        //
    }

    /**
     * Configure the application's route middleware.
     *
     * @return void
     */
    protected function configureRouteMiddleware()
    {
        //
    }

    /**
     * Configure the application's route middleware groups.
     *
     * @return void
     */
    protected function configureRouteMiddlewareGroups()
    {
        //
    }

    /**
     * Configure the application's route middleware aliases.
     *
     * @return void
     */
    protected function configureRouteMiddlewareAliases()
    {
        //
    }

    /**
     * Configure the application's route middleware.
     *
     * @return void
     */
    protected function configureMiddleware()
    {
        //
    }

    /**
     * Configure the application's route middleware groups.
     *
     * @return void
     */
    protected function configureMiddlewareGroups()
    {
        //
    }

    /**
     * Configure the application's route middleware aliases.
     *
     * @return void
     */
    protected function configureMiddlewareAliases()
    {
        //
    }

    /**
     * Configure the application's route middleware.
     *
     * @return void
     */
    protected function configureRouteMiddleware()
    {
        //
    }

    /**
     * Configure the application's route middleware groups.
     *
     * @return void
     */
    protected function configureRouteMiddlewareGroups()
    {
        //
    }

    /**
     * Configure the application's route middleware aliases.
     *
     * @return void
     */
    protected function configureRouteMiddlewareAliases()
    {
        //
    }

    /**
     * Configure the application's route middleware.
     *
     * @return void
     */
    protected function configureMiddleware()
    {
        //
    }

    /**
     * Configure the application's route middleware groups.
     *
     * @return void
     */
    protected function configureMiddlewareGroups()
    {
        //
    }

    /**
     * Configure the application's route middleware aliases.
     *
     * @return void
     */
    protected function configureMiddlewareAliases()
    {
        //
    }

    /**
     * Configure the application's route middleware.
     *
     * @return void
     */
    protected function configureRouteMiddleware()
    {
        //
    }

    /**
     * Configure the application's route middleware groups.
     *
     * @return void
     */
    protected function configureRouteMiddlewareGroups()
    {
        //
    }

    /**
     * Configure the application's route middleware aliases.
     *
     * @return void
     */
    protected function configureRouteMiddlewareAliases()
    {
        //
    }

    /**
     * Configure the application's route middleware.
     *
     * @return void
     */
    protected function configureMiddleware()
    {
        //
    }

    /**
     * Configure the application's route middleware groups.
     *
     * @return void
     */
    protected function configureMiddlewareGroups()
    {
        //
    }

    /**
     * Configure the application's route middleware aliases.
     *
     * @return void
     */
    protected function configureMiddlewareAliases()
    {
        //
    }

    /**
     * Configure the application's route middleware.
     *
     * @return void
     */
    protected function configureRouteMiddleware()
    {
        //
    }

    /**
     * Configure the application's route middleware groups.
     *
     * @return void
     */
    protected function configureRouteMiddlewareGroups()
    {
        //
    }

    /**
     * Configure the application's route middleware aliases.
     *
     * @return void
     */
    protected function configureRouteMiddlewareAliases()
    {
        //
    }

    /**
     * Configure the application's route middleware.
     *
     * @return void
     */
    protected function configureMiddleware()
    {
        //
    }

    /**
     * Configure the application's route middleware groups.
     *
     * @return void
     */
    protected function configureMiddlewareGroups()
    {
        //
    }

    /**
     * Configure the application's route middleware aliases.
     *
     * @return void
     */
    protected function configureMiddlewareAliases()
    {
        //
    }

    /**
     * Configure the application's route middleware.
     *
     * @return void
     */
    protected function configureRouteMiddleware()
    {
        //
    }

    /**
     * Configure the application's route middleware groups.
     *
     * @return void
     */
    protected function configureRouteMiddlewareGroups()
    {
        //
    }

    /**
     * Configure the application's route middleware aliases.
     *
     * @return void
     */
    protected function configureRouteMiddlewareAliases()
    {
        //
    }

    /**
     * Configure the application's route middleware.
     *
     * @return void
     */
    protected function configureMiddleware()
    {
        //
    }

    /**
     * Configure the application's route middleware groups.
     *
     * @return void
     */
    protected function configureMiddlewareGroups()
    {
        //
    }

    /**
     * Configure the application's route middleware aliases.
     *
     * @return void
     */
    protected function configureMiddlewareAliases()
    {
        //
    }

    /**
     * Configure the application's route middleware.
     *
     * @return void
     */
    protected function configureRouteMiddleware()
    {
        //
    }

    /**
     * Configure the application's route middleware groups.
     *
     * @return void
     */
    protected function configureRouteMiddlewareGroups()
    {
        //
    }

    /**
     * Configure the application's route middleware aliases.
     *
     * @return void
     */
    protected function configureRouteMiddlewareAliases()
    {
        //
    }

    /**
     * Configure the application's route middleware.
     *
     * @return void
     */
    protected function configureMiddleware()
    {
        //
    }

    /**
     * Configure the application's route middleware groups.
     *
     * @return void
     */
    protected function configureMiddlewareGroups()
    {
        //
    }

    /**
     * Configure the application's route middleware aliases.
     *
     * @return void
     */
    protected function configureMiddlewareAliases()
    {
        //
    }

    /**
     * Configure the application's route middleware.
     *
     * @return void
     */
    protected function configureRouteMiddleware()
    {
        //
    }

    /**
     * Configure the application's route middleware groups.
     *
     * @return void
     */
    protected function configureRouteMiddlewareGroups()
    {
        //
    }

    /**
     * Configure the application's route middleware aliases.
     *
     * @return void
     */
    protected function configureRouteMiddlewareAliases()
    {
        //
    }

    /**
     * Configure the application's route middleware.
     *
     * @return void
     */
    protected function configureMiddleware()
    {
        //
    }

    /**
     * Configure the application's route middleware groups.
     *
     * @return void
     */
    protected function configureMiddlewareGroups()
    {
        //
    }

    /**
     * Configure the application's route middleware aliases.
     *
     * @return void
     */
    protected function configureMiddlewareAliases()
    {
        //
    }

    /**
     * Configure the application's route middleware.
     *
     * @return void
     */
    protected function configureRouteMiddleware()
    {
        //
    }

    /**
     * Configure the application's route middleware groups.
     *
     * @return void
     */
    protected function configureRouteMiddlewareGroups()
    {
        //
    }

    /**
     * Configure the application's route middleware aliases.
     *
     * @return void
     */
    protected function configureRouteMiddlewareAliases()
    {
        //
    }

    /**
     * Configure the application's route middleware.
     *
     * @return void
     */
    protected function configureMiddleware()
    {
        //
    }

    /**
     * Configure the application's route middleware groups.
     *
     * @return void
     */
    protected function configureMiddlewareGroups()
    {
        //
    }

    /**
     * Configure the application's route middleware aliases.
     *
     * @return void
     */
    protected function configureMiddlewareAliases()
    {
        //
    }

    /**
     * Configure the application's route middleware.
     *
     * @return void
     */
    protected function configureRouteMiddleware()
    {
        //
    }

    /**
     * Configure the application's route middleware groups.
     *
     * @return void
     */
    protected function configureRouteMiddlewareGroups()
    {
        //
    }

    /**
     * Configure the application's route middleware aliases.
     *
     * @return void
     */
    protected function configureRouteMiddlewareAliases()
    {
        //
    }

    /**
     * Configure the application's route middleware.
     *
     * @return void
     */
    protected function configureMiddleware()
    {
        //
    }

    /**
     * Configure the application's route middleware groups.
     *
     * @return void
     */
    protected function configureMiddlewareGroups()
    {
        //
    }

    /**
     * Configure the application's route middleware aliases.
     *
     * @return void
     */
    protected function configureMiddlewareAliases()
    {
        //
    }

    /**
     * Configure the application's route middleware.
     *
     * @return void
     */
    protected function configureRouteMiddleware()
    {
        //
    }

    /**
     * Configure the application's route middleware groups.
     *
     * @return void
     */
    protected function configureRouteMiddlewareGroups()
    {
        //
    }

    /**
     * Configure the application's route middleware aliases.
     *
     * @return void
     */
    protected function configureRouteMiddlewareAliases()
    {
        //
    }

    /**
     * Configure the application's route middleware.
     *
     * @return void
     */
    protected function configureMiddleware()
    {
        //
    }

    /**
     * Configure the application's route middleware groups.
     *
     * @return void
     */
    protected function configureMiddlewareGroups()
    {
        //
    }

    /**
     * Configure the application's route middleware aliases.
     *
     * @return void
     */
    protected function configureMiddlewareAliases()
    {
        //
    }

    /**
     * Configure the application's route middleware.
     *
     * @return void
     */
    protected function configureRouteMiddleware()
    {
        //
    }

    /**
     * Configure the application's route middleware groups.
     *
     * @return void
     */
    protected function configureRouteMiddlewareGroups()
    {
        //
    }

    /**
     * Configure the application's route middleware aliases.
     *
     * @return void
     */
    protected function configureRouteMiddlewareAliases()
    {
        //
    }

    /**
     * Configure the application's route middleware.
     *
     * @return void
     */
    protected function configureMiddleware()
    {
        //
    }

    /**
     * Configure the application's route middleware groups.
     *
     * @return void
     */
    protected function configureMiddlewareGroups()
    {
        //
    }

    /**
     * Configure the application's route middleware aliases.
     *
     * @return void
     */
    protected function configureMiddlewareAliases()
    {
        //
    }

    /**
     * Configure the application's route middleware.
     *
     * @return void
     */
    protected function configureRouteMiddleware()
    {
        //
    }

    /**
     * Configure the application's route middleware groups.
     *
     * @return void
     */
    protected function configureRouteMiddlewareGroups()
    {
        //
    }

    /**
     * Configure the application's route middleware aliases.
     *
     * @return void
     */
    protected function configureRouteMiddlewareAliases()
    {
        //
    }

    /**
     * Configure the application's route middleware.
     *
     * @return void
     */
    protected function configureMiddleware()
    {
        //
    }

    /**
     * Configure the application's route middleware groups.
     *
     * @return void
     */
    protected function configureMiddlewareGroups()
    {
        //
    }

    /**
     * Configure the application's route middleware aliases.
     *
     * @return void
     */
    protected function configureMiddlewareAliases()
    {
        //
    }

    /**
     * Configure the application's route middleware.
     *
     * @return void
     */
    protected function configureRouteMiddleware()
    {
        //
    }

    /**
     * Configure the application's route middleware groups.
     *
     * @return void
     */
    protected function configureRouteMiddlewareGroups()
    {
        //
    }

    /**
     * Configure the application's route middleware aliases.
     *
     * @return void
     */
    protected function configureRouteMiddlewareAliases()
    {
        //
    }

    /**
     * Configure the application's route middleware.
     *
     * @return void
     */
    protected function configureMiddleware()
    {
        //
    }

    /**
     * Configure the application's route middleware groups.
     *
     * @return void
     */
    protected function configureMiddlewareGroups()
    {
        //
    }

    /**
     * Configure the application's route middleware aliases.
     *
     * @return void
     */
    protected function configureMiddlewareAliases()
    {
        //
    }

    /**
     * Configure the application's route middleware.
     *
     * @return void
     */
    protected function configureRouteMiddleware()
    {
        //
    }

    /**
     * Configure the application's route middleware groups.
     *
     * @return void
     */
    protected function configureRouteMiddlewareGroups()
    {
        //
    }

    /**
     * Configure the application's route middleware aliases.
     *
     * @return void
     */
    protected function configureRouteMiddlewareAliases()
    {
        //
    }

    /**
     * Configure the application's route middleware.
     *
     * @return void
     */
    protected function configureMiddleware()
    {
        //
    }

    /**
     * Configure the application's route middleware groups.
     *
     * @return void
     */
    protected function configureMiddlewareGroups()
    {
        //
    }

    /**
     * Configure the application's route middleware aliases.
     *
     * @return void
     */
    protected function configureMiddlewareAliases()
    {
        //
    }

    /**
     * Configure the application's route middleware.
     *
     * @return void
     */
    protected function configureRouteMiddleware()
    {
        //
    }

    /**
     * Configure the application's route middleware groups.
     *
     * @return void
     */
    protected function configureRouteMiddlewareGroups()
    {
        //
    }

    /**
     * Configure the application's route middleware aliases.
     *
     * @return void
     */
    protected function configureRouteMiddlewareAliases()
    {
        //
    }

    /**
     * Configure the application's route middleware.
     *
     * @return void
     */
    protected function configureMiddleware()
    {
        //
    }

    /**
     * Configure the application's route middleware groups.
     *
     * @return void
     */
    protected function configureMiddlewareGroups()
    {
        //
    }

    /**
     * Configure the application's route middleware aliases.
     *
     * @return void
     */
    protected function configureMiddlewareAliases()
    {
        //
    }

    /**
     * Configure the application's route middleware.
     *
     * @return void
     */
    protected function configureRouteMiddleware()
    {
        //
    }

    /**
     * Configure the application's route middleware groups.
     *
     * @return void
     */
    protected function configureRouteMiddlewareGroups()
    {
        //
    }

    /**
     * Configure the application's route middleware aliases.
     *
     * @return void
     */
    protected function configureRouteMiddlewareAliases()
    {
        //
    }

    /**
     * Configure the application's route middleware.
     *
     * @return void
     */
    protected function configureMiddleware()
    {
        //
    }

    /**
     * Configure the application's route middleware groups.
     *
     * @return void
     */
    protected function configureMiddlewareGroups()
    {
        //
    }

    /**
     * Configure the application's route middleware aliases.
     *
     * @return void
     */
    protected function configureMiddlewareAliases()
    {
        //
    }

    /**
     * Configure the application's route middleware.
     *
     * @return void
     */
    protected function configureRouteMiddleware()
    {
        //
    }

    /**
     * Configure the application's route middleware groups.
     *
     * @return void
     */
    protected function configureRouteMiddlewareGroups()
    {
        //
    }

    /**
     * Configure the application's route middleware aliases.
     *
     * @return void
     */
    protected function configureRouteMiddlewareAliases()
    {
        //
    }

    /**
     * Configure the application's route middleware.
     *
     * @return void
     */
    protected function configureMiddleware()
    {
        //
    }

    /**
     * Configure the application's route middleware groups.
     *
     * @return void
     */
    protected function configureMiddlewareGroups()
    {
        //
    }

    /**
     * Configure the application's route middleware aliases.
     *
     * @return void
     */
    protected function configureMiddlewareAliases()
    {
        //
    }

    /**
     * Configure the application's route middleware.
     *
     * @return void
     */
    protected function configureRouteMiddleware()
    {
        //
    }

    /**
     * Configure the application's route middleware groups.
     *
     * @return void
     */
    protected function configureRouteMiddlewareGroups()
    {
        //
    }

    /**
     * Configure the application's route middleware aliases.
     *
     * @return void
     */
    protected function configureRouteMiddlewareAliases()
    {
        //
    }

    /**
     * Configure the application's route middleware.
     *
     * @return void
     */
    protected function configureMiddleware()
    {
        //
    }

    /**
     * Configure the application's route middleware groups.
     *
     * @return void
     */
    protected function configureMiddlewareGroups()
    {
        //
    }

    /**
     * Configure the application's route middleware aliases.
     *
     * @return void
     */
    protected function configureMiddlewareAliases()
    {
        //
    }

    /**
     * Configure the application's route middleware.
     *
     * @return void
     */
    protected function configureRouteMiddleware()
    {
        //
    }

    /**
     * Configure the application's route middleware groups.
     *
     * @return void
     */
    protected function configureRouteMiddlewareGroups()
    {
        //
    }

    /**
     * Configure the application's route middleware aliases.
     *
     * @return void
     */
    protected function configureRouteMiddlewareAliases()
    {
        //
    }

    /**
     * Configure the application's route middleware.
     *
     * @return void
     */
    protected function configureMiddleware()
    {
        //
    }

    /**
     * Configure the application's route middleware groups.
     *
     * @return void
     */
    protected function configureMiddlewareGroups()
    {
        //
    }

    /**
     * Configure the application's route middleware aliases.
     *
     * @return void
     */
    protected function configureMiddlewareAliases()
    {
        //
    }

    /**
     * Configure the application's route middleware.
     *
     * @return void
     */
    protected function configureRouteMiddleware()
    {
        //
    }

    /**
     * Configure the application's route middleware groups.
     *
     * @return void
     */
    protected function configureRouteMiddlewareGroups()
    {
        //
    }

    /**
     * Configure the application's route middleware aliases.
     *
     * @return void
     */
    protected function configureRouteMiddlewareAliases()
    {
        //
    }

    /**
     * Configure the application's route middleware.
     *
     * @return void
     */
    protected function configureMiddleware()
    {
        //
    }

    /**
     * Configure the application's route middleware groups.
     *
     * @return void
     */
    protected function configureMiddlewareGroups()
    {
        //
    }

    /**
     * Configure the application's route middleware aliases.
     *
     * @return void
     */
    protected function configureMiddlewareAliases()
    {
        //
    }

    /**
     * Configure the application's route middleware.
     *
     * @return void
     */
    protected function configureRouteMiddleware()
    {
        //
    }

    /**
     * Configure the application's route middleware groups.
     *
     * @return void
     */
    protected function configureRouteMiddlewareGroups()
    {
        //
    }

    /**
     * Configure the application's route middleware aliases.
     *
     * @return void
     */
    protected function configureRouteMiddlewareAliases()
    {
        //
    }

    /**
     * Configure the application's route middleware.
     *
     * @return void
     */
    protected function configureMiddleware()
    {
        //
    }

    /**
     * Configure the application's route middleware groups.
     *
     * @return void
     */
    protected function configureMiddlewareGroups()
    {
        //
    }

    /**
     * Configure the application's route middleware aliases.
     *
     * @return void
     */
    protected function configureMiddlewareAliases()
    {
        //
    }

    /**
     * Configure the application's route middleware.
     *
     * @return void
     */
    protected function configureRouteMiddleware()
    {
        //
    }

    /**
     * Configure the application's route middleware groups.
     *
     * @return void
     */
    protected function configureRouteMiddlewareGroups()
    {
        //
    }

    /**
     * Configure the application's route middleware aliases.
     *
     * @return void
     */
    protected function configureRouteMiddlewareAliases()
    {
        //
    }

    /**
     * Configure the application's route middleware.
     *
     * @return void
     */
    protected function configureMiddleware()
    {
        //
    }

    /**
     * Configure the application's route middleware groups.
     *
     * @return void
     */
    protected function configureMiddlewareGroups()
    {
        //
    }

    /**
     * Configure the application's route middleware aliases.
     *
     * @return void
     */
    protected function configureMiddlewareAliases()
    {
        //
    }

    /**
     * Configure the application's route middleware.
     *
     * @return void
     */
    protected function configureRouteMiddleware()
    {
        //
    }

    /**
     * Configure the application's route middleware groups.
     *
     * @return void
     */
    protected function configureRouteMiddlewareGroups()
    {
        //
    }

    /**
     * Configure the application's route middleware aliases.
     *
     * @return void
     */
    protected function configureRouteMiddlewareAliases()
    {
        //
    }

    /**
     * Configure the application's route middleware.
     *
     * @return void
     */
    protected function configureMiddleware()
    {
        //
    }

    /**
     * Configure the application's route middleware groups.
     *
     * @return void
     */
    protected function configureMiddlewareGroups()
    {
        //
    }

    /**
     * Configure the application's route middleware aliases.
     *
     * @return void
     */
    protected function configureMiddlewareAliases()
    {
        //
    }

    /**
     * Configure the application's route middleware.
     *
     * @return void
     */
    protected function configureRouteMiddleware()
    {
        //
    }

    /**
     * Configure the application's route middleware groups.
     *
     * @return void
     */
    protected function configureRouteMiddlewareGroups()
    {
        //
    }

    /**
     * Configure the application's route middleware aliases.
     *
     * @return void
     */
    protected function configureRouteMiddlewareAliases()
    {
        //
    }

    /**
     * Configure the application's route middleware.
     *
     * @return void
     */
    protected function configureMiddleware()
    {
        //
    }

    /**
     * Configure the application's route middleware groups.
     *
     * @return void
     */
    protected function configureMiddlewareGroups()
    {
        //
    }

    /**
     * Configure the application's route middleware aliases.
     *
     * @return void
     */
    protected function configureMiddlewareAliases()
    {
        //
    }

    /**
     * Configure the application's route middleware.
     *
     * @return void
     */
    protected function configureRouteMiddleware()
    {
        //
    }

    /**
     * Configure the application's route middleware groups.
     *
     * @return void
     */
    protected function configureRouteMiddlewareGroups()
    {
        //
    }

    /**
     * Configure the application's route middleware aliases.
     *
     * @return void
     */
    protected function configureRouteMiddlewareAliases()
    {
        //
    }

    /**
     * Configure the application's route middleware.
     *
     * @return void
     */
    protected function configureMiddleware()
    {
        //
    }

    /**
     * Configure the application's route middleware groups.
     *
     * @return void
     */
    protected function configureMiddlewareGroups()
    {
        //
    }

    /**
     * Configure the application's route middleware aliases.
     *
     * @return void
     */
    protected function configureMiddlewareAliases()
    {
        //
    }

    /**
     * Configure the application's route middleware.
     *
     * @return void
     */
    protected function configureRouteMiddleware()
    {
        //
    }

    /**
     * Configure the application's route middleware groups.
     *
     * @return void
     */
    protected function configureRouteMiddlewareGroups()
    {
        //
    }

    /**
     * Configure the application's route middleware aliases.
     *
     * @return void
     */
    protected function configureRouteMiddlewareAliases()
    {
        //
    }

    /**
     * Configure the application's route middleware.
     *
     * @return void
     */
    protected function configureMiddleware()
    {
        //
    }

    /**
     * Configure the application's route middleware groups.
     *
     * @return void
     */
    protected function configureMiddlewareGroups()
    {
        //
    }

    /**
     * Configure the application's route middleware aliases.
     *
     * @return void
     */
    protected function configureMiddlewareAliases()
    {
        //
    }

    /**
     * Configure the application's route middleware.
     *
     * @return void
     */
    protected function configureRouteMiddleware()
    {
        //
    }

    /**
     * Configure the application's route middleware groups.
     *
     * @return void
     */
    protected function configureRouteMiddlewareGroups()
    {
        //
    }

    /**
     * Configure the application's route middleware aliases.
     *
     * @return void
     */
    protected function configureRouteMiddlewareAliases()
    {
        //
    }

    /**
     * Configure the application's route middleware.
     *
     * @return void
     */
    protected function configureMiddleware()
    {
        //
    }

    /**
     * Configure the application's route middleware groups.
     *
     * @return void
     */
    protected function configureMiddlewareGroups()
    {
        //
    }

    /**
     * Configure the application's route middleware aliases.
     *
     * @return void
     */
    protected function configureMiddlewareAliases()
    {
        //
    }

    /**
     * Configure the application's route middleware.
     *
     * @return void
     */
    protected function configureRouteMiddleware()
    {
        //
    }

    /**
     * Configure the application's route middleware groups.
     *
     * @return void
     */
    protected function configureRouteMiddlewareGroups()
    {
        //
    }

    /**
     * Configure the application's route middleware aliases.
     *
     * @return void
     */
    protected function configureRouteMiddlewareAliases()
    {
        //
    }

    /**
     * Configure the application's route middleware.
     *
     * @return void
     */
    protected function configureMiddleware()
    {
        //
    }

    /**
     * Configure the application's route middleware groups.
     *
     * @return void
     */
    protected function configureMiddlewareGroups()
    {
        //
    }

    /**
     * Configure the application's route middleware aliases.
     *
     * @return void
     */
    protected function configureMiddlewareAliases()
    {
        //
    }

    /**
     * Configure the application's route middleware.
     *
     * @return void
     */
    protected function configureRouteMiddleware()
    {
        //
    }

    /**
     * Configure the application's route middleware groups.
     *
     * @return void
     */
    protected function configureRouteMiddlewareGroups()
    {
        //
    }

    /**
     * Configure the application's route middleware aliases.
     *
     * @return void
     */
    protected function configureRouteMiddlewareAliases()
    {
        //
    }

    /**
     * Configure the application's route middleware.
     *
     * @return void
     */
    protected function configureMiddleware()
    {
        //
    }

    /**
     * Configure the application's route middleware groups.
     *
     * @return void
     */
    protected function configureMiddlewareGroups()
    {
        //
    }

    /**
     * Configure the application's route middleware aliases.
     *
     * @return void
     */
    protected function configureMiddlewareAliases()
    {
        //
    }

    /**
     * Configure the application's route middleware.
     *
     * @return void
     */
    protected function configureRouteMiddleware()
    {
        //
    }

    /**
     * Configure the application's route middleware groups.
     *
     * @return void
     */
    protected function configureRouteMiddlewareGroups()
    {
        //
    }

    /**
     * Configure the application's route middleware aliases.
     *
     * @return void
     */
    protected function configureRouteMiddlewareAliases()
    {
        //
    }

    /**
     * Configure the application's route middleware.
     *
     * @return void
     */
    protected function configureMiddleware()
    {
        //
    }

    /**
     * Configure the application's route middleware groups.
     *
     * @return void
     */
    protected function configureMiddlewareGroups()
    {
        //
    }

    /**
     * Configure the application's route middleware aliases.
     *
     * @return void
     */
    protected function configureMiddlewareAliases()
    {
        //
    }

    /**
     * Configure the application's route middleware.
     *
     * @return void
     */
    protected function configureRouteMiddleware()
    {
        //
    }

    /**
     * Configure the application's route middleware groups.
     *
     * @return void
     */
    protected function configureRouteMiddlewareGroups()
    {
        //
    }

    /**
     * Configure the application's route middleware aliases.
     *
     * @return void
     */
    protected function configureRouteMiddlewareAliases()
    {
        //
    }

    /**
     * Configure the application's route middleware.
     *
     * @return void
     */
    protected function configureMiddleware()
    {
        //
    }

    /**
     * Configure the application's route middleware groups.
     *
     * @return void
     */
    protected function configureMiddlewareGroups()
    {
        //
    }

    /**
     * Configure the application's route middleware aliases.
     *
     * @return void
     */
    protected function configureMiddlewareAliases()
    {
        //
    }

    /**
     * Configure the application's route middleware.
     *
     * @return void
     */
    protected function configureRouteMiddleware()
    {
        //
    }

    /**
     * Configure the application's route middleware groups.
     *
     * @return void
     */
    protected function configureRouteMiddlewareGroups()
    {
        //
    }

    /**
     * Configure the application's route middleware aliases.
     *
     * @return void
     */
    protected function configureRouteMiddlewareAliases()
    {
        //
    }

    /**
     * Configure the application's route middleware.
     *
     * @return void
     */
    protected function configureMiddleware()
    {
        //
    }

    /**
     * Configure the application's route middleware groups.
     *
     * @return void
     */
    protected function configureMiddlewareGroups()
    {
        //
    }

    /**
     * Configure the application's route middleware aliases.
     *
     * @return void
     */
    protected function configureMiddlewareAliases()
    {
        //
    }

    /**
     * Configure the application's route middleware.
     *
     * @return void
     */
    protected function configureRouteMiddleware()
    {
        //
    }

    /**
     * Configure the application's route middleware groups.
     *
     * @return void
     */
    protected function configureRouteMiddlewareGroups()
    {
        //
    }

    /**
     * Configure the application's route middleware aliases.
     *
     * @return void
     */
    protected function configureRouteMiddlewareAliases()
    {
        //
    }

    /**
     * Configure the application's route middleware.
     *
     * @return void
     */
    protected function configureMiddleware()
    {
        //
    }

    /**
     * Configure the application's route middleware groups.
     *
     * @return void
     */
    protected function configureMiddlewareGroups()
    {
        //
    }

    /**
     * Configure the application's route middleware aliases.
     *
     * @return void
     */
    protected function configureMiddlewareAliases()
    {
        //
    }

    /**
     * Configure the application's route middleware.
     *
     * @return void
     */
    protected function configureRouteMiddleware()
    {
        //
    }

    /**
     * Configure the application's route middleware groups.
     *
     * @return void
     */
    protected function configureRouteMiddlewareGroups()
    {
        //
    }

    /**
     * Configure the application's route middleware aliases.
     *
     * @return void
     */
    protected function configureRouteMiddlewareAliases()
    {
        //
    }

    /**
     * Configure the application's route middleware.
     *
     * @return void
     */
    protected function configureMiddleware()
    {
        //
    }

    /**
     * Configure the application's route middleware groups.
     *
     * @return void
     */
    protected function configureMiddlewareGroups()
    {
        //
    }

    /**
     * Configure the application's route middleware aliases.
     *
     * @return void
     */
    protected function configureMiddlewareAliases()
    {
        //
    }

    /**
     * Configure the application's route middleware.
     *
     * @return void
     */
    protected function configureRouteMiddleware()
    {
        //
    }

    /**
     * Configure the application's route middleware groups.
     *
     * @return void
     */
    protected function configureRouteMiddlewareGroups()
    {
        //
    }

    /**
     * Configure the application's route middleware aliases.
     *
     * @return void
     */
    protected function configureRouteMiddlewareAliases()
    {
        //
    }

    /**
     * Configure the application's route middleware.
     *
     * @return void
     */
    protected function configureMiddleware()
    {
        //
    }

    /**
     * Configure the application's route middleware groups.
     *
     * @return void
     */
    protected function configureMiddlewareGroups()
    {
        //
    }

    /**
     * Configure the application's route middleware aliases.
     *
     * @return void
     */
    protected function configureMiddlewareAliases()
    {
        //
    }

    /**
     * Configure the application's route middleware.
     *
     * @return void
     */
    protected function configureRouteMiddleware()
    {
        //
    }

    /**
     * Configure the application's route middleware groups.
     *
     * @return void
     */
    protected function configureRouteMiddlewareGroups()
    {
        //
    }

    /**
     * Configure the application's route middleware aliases.
     *
     * @return void
     */
    protected function configureRouteMiddlewareAliases()
    {
        //
    }

    /**
     * Configure the application's route middleware.
     *
     * @return void
     */
    protected function configureMiddleware()
    {
        //
    }

    /**
     * Configure the application's route middleware groups.
     *
     * @return void
     */
    protected function configureMiddlewareGroups()
    {
        //
    }

    /**
     * Configure the application's route middleware aliases.
     *
     * @return void
     */
    protected function configureMiddlewareAliases()
    {
        //
    }

    /**
     * Configure the application's route middleware.
     *
     * @return void
     */
    protected function configureRouteMiddleware()
    {
        //
    }

    /**
     * Configure the application's route middleware groups.
     *
     * @return void
     */
    protected function configureRouteMiddlewareGroups()
    {
        //
    }

    /**
     * Configure the application's route middleware aliases.
     *
     * @return void
     */
    protected function configureRouteMiddlewareAliases()
    {
        //
    }

    /**
     * Configure the application's route middleware.
     *
     * @return void
     */
    protected function configureMiddleware()
    {
        //
    }

    /**
     * Configure the application's route middleware groups.
     *
     * @return void
     */
    protected function configureMiddlewareGroups()
    {
        //
    }

    /**
     * Configure the application's route middleware aliases.
     *
     * @return void
     */
    protected function configureMiddlewareAliases()
    {
        //
    }

    /**
     * Configure the application's route middleware.
     *
     * @return void
     */
    protected function configureRouteMiddleware()
    {
        //
    }

    /**
     * Configure the application's route middleware groups.
     *
     * @return void
     */
    protected function configureRouteMiddlewareGroups()
    {
        //
    }

    /**
     * Configure the application's route middleware aliases.
     *
     * @return void
     */
    protected function configureRouteMiddlewareAliases()
    {
        //
    }

    /**
     * Configure the application's route middleware.
     *
     * @return void
     */
    protected function configureMiddleware()
    {
        //
    }

    /**
     * Configure the application's route middleware groups.
     *
     * @return void
     */
    protected function configureMiddlewareGroups()
    {
        //
    }

    /**
     * Configure the application's route middleware aliases.
     *
     * @return void
     */
    protected function configureMiddlewareAliases()
    {
        //
    }

    /**
     * Configure the application's route middleware.
     *
     * @return void
     */
    protected function configureRouteMiddleware()
    {
        //
    }

    /**
     * Configure the application's route middleware groups.
     *
     * @return void
     */
    protected function configureRouteMiddlewareGroups()
    {
        //
    }

    /**
     * Configure the application's route middleware aliases.
     *
     * @return void
     */
    protected function configureRouteMiddlewareAliases()
    {
        //
    }

    /**
     * Configure the application's route middleware.
     *
     * @return void
     */
    protected function configureMiddleware()
    {
        //
    }

    /**
     * Configure the application's route middleware groups.
     *
     * @return void
     */
    protected function configureMiddlewareGroups()
    {
        //
    }

    /**
     * Configure the application's route middleware aliases.
     *
     * @return void
     */
    protected function configureMiddlewareAliases()
    {
        //
    }

    /**
     * Configure the application's route middleware.
     *
     * @return void
     */
    protected function configureRouteMiddleware()
    {
        //
    }

    /**
     * Configure the application's route middleware groups.
     *
     * @return void
     */
    protected function configureRouteMiddlewareGroups()
    {
        //
    }

    /**
     * Configure the application's route middleware aliases.
     *
     * @return void
     */
    protected function configureRouteMiddlewareAliases()
    {
        //
    }

    /**
     * Configure the application's route middleware.
     *
     * @return void
     */
    protected function configureMiddleware()
    {
        //
    }

    /**
     * Configure the application's route middleware groups.
     *
     * @return void
     */
    protected function configureMiddlewareGroups()
    {
        //
    }

    /**
     * Configure the application's route middleware aliases.
     *
     * @return void
     */
    protected function configureMiddlewareAliases()
    {
        //
    }

    /**
     * Configure the application's route middleware.
     *
     * @return void
     */
    protected function configureRouteMiddleware()
    {
        //
    }

    /**
     * Configure the application's route middleware groups.
     *
     * @return void
     */
    protected function configureRouteMiddlewareGroups()
    {
        //
    }

    /**
     * Configure the application's route middleware aliases.
     *
     * @return void
     */
    protected function configureRouteMiddlewareAliases()
    {
        //
    }

    /**
     * Configure the application's route middleware.
     *
     * @return void
     */
    protected function configureMiddleware()
    {
        //
    }

    /**
     * Configure the application's route middleware groups.
     *
     * @return void
     */
    protected function configureMiddlewareGroups()
    {
        //
    }

    /**
     * Configure the application's route middleware aliases.
     *
     * @return void
     */
    protected function configureMiddlewareAliases()
    {
        //
    }

    /**
     * Configure the application's route middleware.
     *
     * @return void
     */
    protected function configureRouteMiddleware()
    {
        //
    }

    /**
     * Configure the application's route middleware groups.
     *
     * @return void
     */
    protected function configureRouteMiddlewareGroups()
    {
        //
    }

    /**
     * Configure the application's route middleware aliases.
     *
     * @return void
     */
    protected function configureRouteMiddlewareAliases()
    {
        //
    }

    /**
     * Configure the application's route middleware.
     *
     * @return void
     */
    protected function configureMiddleware()
    {
        //
    }

    /**
     * Configure the application's route middleware groups.
     *
     * @return void
     */
    protected function configureMiddlewareGroups()
    {
        //
    }

    /**
     * Configure the application's route middleware aliases.
     *
     * @return void
     */
    protected function configureMiddlewareAliases()
    {
        //
    }

    /**
     * Configure the application's route middleware.
     *
     * @return void
     */
    protected function configureRouteMiddleware()
    {
        //
    }

    /**
     * Configure the application's route middleware groups.
     *
     * @return void
     */
    protected function configureRouteMiddlewareGroups()
    {
        //
    }

    /**
     * Configure the application's route middleware aliases.
     *
     * @return void
     */
    protected function configureRouteMiddlewareAliases()
    {
        //
    }

    /**
     * Configure the application's route middleware.
     *
     * @return void
     */
    protected function configureMiddleware()
    {
        //
    }

    /**
     * Configure the application's route middleware groups.
     *
     * @return void
     */
    protected function configureMiddlewareGroups()
    {
        //
    }

    /**
     * Configure the application's route middleware aliases.
     *
     * @return void
     */
    protected function configureMiddlewareAliases()
    {
        //
    }

    /**
     * Configure the application's route middleware.
     *
     * @return void
     */
    protected function configureRouteMiddleware()
    {
        //
    }

    /**
     * Configure the application's route middleware groups.
     *
     * @return void
     */
    protected function configureRouteMiddlewareGroups()
    {
        //
    }

    /**
     * Configure the application's route middleware aliases.
     *
     * @return void
     */
    protected function configureRouteMiddlewareAliases()
    {
        //
    }

    /**
     * Configure the application's route middleware.
     *
     * @return void
     */
    protected function configureMiddleware()
    {
        //
    }

    /**
     * Configure the application's route middleware groups.
     *
     * @return void
     */
    protected function configureMiddlewareGroups()
    {
        //
    }

    /**
     * Configure the application's route middleware aliases.
     *
     * @return void
     */
    protected function configureMiddlewareAliases()
    {
        //
    }

    /**
     * Configure the application's route middleware.
     *
     * @return void
     */
    protected function configureRouteMiddleware()
    {
        //
    }

    /**
     * Configure the application's route middleware groups.
     *
     * @return void
     */
    protected function configureRouteMiddlewareGroups()
    {
        //
    }

    /**
     * Configure the application's route middleware aliases.
     *
     * @return void
     */
    protected function configureRouteMiddlewareAliases()
    {
        //
    }

    /**
     * Configure the application's route middleware.
     *
     * @return void
     */
    protected function configureMiddleware()
    {
        //
    }

    /**
     * Configure the application's route middleware groups.
     *
     * @return void
     */
    protected function configureMiddlewareGroups()
    {
        //
    }

    /**
     * Configure the application's route middleware aliases.
     *
     * @return void
     */
    protected function configureMiddlewareAliases()
    {
        //
    }

    /**
     * Configure the application's route middleware.
     *
     * @return void
     */
    protected function configureRouteMiddleware()
    {
        //
    }

    /**
     * Configure the application's route middleware groups.
     *
     * @return void
     */
    protected function configureRouteMiddlewareGroups()
    {
        //
    }

    /**
     * Configure the application's route middleware aliases.
     *
     * @return void
     */
    protected function configureRouteMiddlewareAliases()
    {
        //
    }

    /**
     * Configure the application's route middleware.
     *
     * @return void
     */
    protected function configureMiddleware()
    {
        //
    }

    /**
     * Configure the application's route middleware groups.
     *
     * @return void
     */
    protected function configureMiddlewareGroups()
    {
        //
    }

    /**
     * Configure the application's route middleware aliases.
     *
     * @return void
     */
    protected function configureMiddlewareAliases()
    {
        //
    }

    /**
     * Configure the application's route middleware.
     *
     * @return void
     */
    protected function configureRouteMiddleware()
    {
        //
    }

    /**
     * Configure the application's route middleware groups.
     *
     * @return void
     */
    protected function configureRouteMiddlewareGroups()
    {
        //
    }

    /**
     * Configure the application's route middleware aliases.
     *
     * @return void
     */
    protected function configureRouteMiddlewareAliases()
    {
        //
    }

    /**
     * Configure the application's route middleware.
     *
     * @return void
     */
    protected function configureMiddleware()
    {
        //
    }

    /**
     * Configure the application's route middleware groups.
     *
     * @return void
     */
    protected function configureMiddlewareGroups()
    {
        //
    }

    /**
     * Configure the application's route middleware aliases.
     *
     * @return void
     */
    protected function configureMiddlewareAliases()
    {
        //
    }

    /**
     * Configure the application's route middleware.
     *
     * @return void
     */
    protected function configureRouteMiddleware()
    {
        //
    }

    /**
     * Configure the application's route middleware groups.
     *
     * @return void
     */
    protected function configureRouteMiddlewareGroups()
    {
        //
    }

    /**
     * Configure the application's route middleware aliases.
     *
     * @return void
     */
    protected function configureRouteMiddlewareAliases()
    {
        //
    }

    /**
     * Configure the application's route middleware.
     *
     * @return void
     */
    protected function configureMiddleware()
    {
        //
    }

    /**
     * Configure the application's route middleware groups.
     *
     * @return void
     */
    protected function configureMiddlewareGroups()
    {
        //
    }

    /**
     * Configure the application's route middleware aliases.
     *
     * @return void
     */
    protected function configureMiddlewareAliases()
    {
        //
    }

    /**
     * Configure the application's route middleware.
     *
     * @return void
     */
    protected function configureRouteMiddleware()
    {
        //
    }

    /**
     * Configure the application's route middleware groups.
     *
     * @return void
     */
    protected function configureRouteMiddlewareGroups()
    {
        //
    }

    /**
     * Configure the application's route middleware aliases.
     *
     * @return void
     */
    protected function configureRouteMiddlewareAliases()
    {
        //
    }

    /**
     * Configure the application's route middleware.
     *
     * @return void
     */
    protected function configureMiddleware()
    {
        //
    }

    /**
     * Configure the application's route middleware groups.
     *
     * @return void
     */
    protected function configureMiddlewareGroups()
    {
        //
    }

    /**
     * Configure the application's route middleware aliases.
     *
     * @return void
     */
    protected function configureMiddlewareAliases()
    {
        //
    }

    /**
     * Configure the application's route middleware.
     *
     * @return void
     */
    protected function configureRouteMiddleware()
    {
        //
    }

    /**
     * Configure the application's route middleware groups.
     *
     * @return void
     */
    protected function configureRouteMiddlewareGroups()
    {
        //
    }

    /**
     * Configure the application's route middleware aliases.
     *
     * @return void
     */
    protected function configureRouteMiddlewareAliases()
    {
        //
    }

    /**
     * Configure the application's route middleware.
     *
     * @return void
     */
    protected function configureMiddleware()
    {
        //
    }

    /**
     * Configure the application's route middleware groups.
     *
     * @return void
     */
    protected function configureMiddlewareGroups()
    {
        //
    }

    /**
     * Configure the application's route middleware aliases.
     *
     * @return void
     */
    protected function configureMiddlewareAliases()
    {
        //
    }

    /**
     * Configure the application's route middleware.
     *
     * @return void
     */
    protected function configureRouteMiddleware()
    {
        //
    }

    /**
     * Configure the application's route middleware groups.
     *
     * @return void
     */
    protected function configureRouteMiddlewareGroups()
    {
        //
    }

    /**
     * Configure the application's route middleware aliases.
     *
     * @return void
     */
    protected function configureRouteMiddlewareAliases()
    {
        //
    }

    /**
     * Configure the application's route middleware.
     *
     * @return void
     */
    protected function configureMiddleware()
    {
        //
    }

    /**
     * Configure the application's route middleware groups.
     *
     * @return void
     */
    protected function configureMiddlewareGroups()
    {
        //
    }

    /**
     * Configure the application's route middleware aliases.
     *
     * @return void
     */
    protected function configureMiddlewareAliases()
    {
        //
    }

    /**
     * Configure the application's route middleware.
     *
     * @return void
     */
    protected function configureRouteMiddleware()
    {
        //
    }

    /**
     * Configure the application's route middleware groups.
     *
     * @return void
     */
    protected function configureRouteMiddlewareGroups()
    {
        //
    }

    /**
     * Configure the application's route middleware aliases.
     *
     * @return void
     */
    protected function configureRouteMiddlewareAliases()
    {
        //
    }

    /**
     * Configure the application's route middleware.
     *
     * @return void
     */
    protected function configureMiddleware()
    {
        //
    }

    /**
     * Configure the application's route middleware groups.
     *
     * @return void
     */
    protected function configureMiddlewareGroups()
    {
        //
    }

    /**
     * Configure the application's route middleware aliases.
     *
     * @return void
     */
    protected function configureMiddlewareAliases()
    {
        //
    }

    /**
     * Configure the application's route middleware.
     *
     * @return void
     */
    protected function configureRouteMiddleware()
    {
        //
    }

    /**
     * Configure the application's route middleware groups.
     *
     * @return void
     */
    protected function configureRouteMiddlewareGroups()
    {
        //
    }

    /**
     * Configure the application's route middleware aliases.
     *
     * @return void
     */
    protected function configureRouteMiddlewareAliases()
    {
        //
    }

    /**
     * Configure the application's route middleware.
     *
     * @return void
     */
    protected function configureMiddleware()
    {
        //
    }

    /**
     * Configure the application's route middleware groups.
     *
     * @return void
     */
    protected function configureMiddlewareGroups()
    {
        //
    }

    /**
     * Configure the application's route middleware aliases.
     *
     * @return void
     */
    protected function configureMiddlewareAliases()
    {
        //
    }

    /**
     * Configure the application's route middleware.
     *
     * @return void
     */
    protected function configureRouteMiddleware()
    {
        //
    }

    /**
     * Configure the application's route middleware groups.
     *
     * @return void
     */
    protected function configureRouteMiddlewareGroups()
    {
        //
    }

    /**
     * Configure the application's route middleware aliases.
     *
     * @return void
     */
    protected function configureRouteMiddlewareAliases()
    {
        //
    }

    /**
     * Configure the application's route middleware.
     *
     * @return void
     */
    protected function configureMiddleware()
    {
        //
    }

    /**
     * Configure the application's route middleware groups.
     *
     * @return void
     */
    protected function configureMiddlewareGroups()
    {
        //
    }

    /**
     * Configure the application's route middleware aliases.
     *
     * @return void
     */
    protected function configureMiddlewareAliases()
    {
        //
    }

    /**
     * Configure the application's route middleware.
     *
     * @return void
     */
    protected function configureRouteMiddleware()
    {
        //
    }

    /**
     * Configure the application's route middleware groups.
     *
     * @return void
     */
    protected function configureRouteMiddlewareGroups()
    {
        //
    }

    /**
     * Configure the application's route middleware aliases.
     *
     * @return void
     */
    protected function configureRouteMiddlewareAliases()
    {
        //
    }

    /**
     * Configure the application's route middleware.
     *
     * @return void
     */
    protected function configureMiddleware()
    {
        //
    }

    /**
     * Configure the application's route middleware groups.
     *
     * @return void
     */
    protected function configureMiddlewareGroups()
    {
        //
    }

    /**
     * Configure the application's route middleware aliases.
     *
     * @return void
     */
    protected function configureMiddlewareAliases()
    {
        //
    }

    /**
     * Configure the application's route middleware.
     *
     * @return void
     */
    protected function configureRouteMiddleware()
    {
        //
    }

    /**
     * Configure the application's route middleware groups.
     *
     * @return void
     */
    protected function configureRouteMiddlewareGroups()
    {
        //
    }

    /**
     * Configure the application's route middleware aliases.
     *
     * @return void
     */
    protected function configureRouteMiddlewareAliases()
    {
        //
    }

    /**
     * Configure the application's route middleware.
     *
     * @return void
     */
    protected function configureMiddleware()
    {
        //
    }

    /**
     * Configure the application's route middleware groups.
     *
     * @return void
     */
    protected function configureMiddlewareGroups()
    {
        //
    }

    /**
     * Configure the application's route middleware aliases.
     *
     * @return void
     */
    protected function configureMiddlewareAliases()
    {
        //
    }

    /**
     * Configure the application's route middleware.
     *
     * @return void
     */
    protected function configureRouteMiddleware()
    {
        //
    }

    /**
     * Configure the application's route middleware groups.
     *
     * @return void
     */
    protected function configureRouteMiddlewareGroups()
    {
        //
    }

    /**
     * Configure the application's route middleware aliases.
     *
     * @return void
     */
    protected function configureRouteMiddlewareAliases()
    {
        //
    }

    /**
     * Configure the application's route middleware.
     *
     * @return void
     */
    protected function configureMiddleware()
    {
        //
    }

    /**
     * Configure the application's route middleware groups.
     *
     * @return void
     */
    protected function configureMiddlewareGroups()
    {
        //
    }

    /**
     * Configure the application's route middleware aliases.
     *
     * @return void
     */
    protected function configureMiddlewareAliases()
    {
        //
    }

    /**
     * Configure the application's route middleware.
     *
     * @return void
     */
    protected function configureRouteMiddleware()
    {
        //
    }

    /**
     * Configure the application's route middleware groups.
     *
     * @return void
     */
    protected function configureRouteMiddlewareGroups()
    {
        //
    }

    /**
     * Configure the application's route middleware aliases.
     *
     * @return void
     */
    protected function configureRouteMiddlewareAliases()
    {
        //
    }

    /**
     * Configure the application's route middleware.
     *
     * @return void
     */
    protected function configureMiddleware()
    {
        //
    }

    /**
     * Configure the application's route middleware groups.
     *
     * @return void
     */
    protected function configureMiddlewareGroups()
    {
        //
    }

    /**
     * Configure the application's route middleware aliases.
     *
     * @return void
     */
    protected function configureMiddlewareAliases()
    {
        //
    }

    /**
     * Configure the application's route middleware.
     *
     * @return void
     */
    protected function configureRouteMiddleware()
    {
        //
    }

    /**
     * Configure the application's route middleware groups.
     *
     * @return void
     */
    protected function configureRouteMiddlewareGroups()
    {
        //
    }

    /**
     * Configure the application's route middleware aliases.
     *
     * @return void
     */
    protected function configureRouteMiddlewareAliases()
    {
        //
    }

    /**
     * Configure the application's route middleware.
     *
     * @return void
     */
    protected function configureMiddleware()
    {
        //
    }

    /**
     * Configure the application's route middleware groups.
     *
     * @return void
     */
    protected function configureMiddlewareGroups()
    {
        //
    }

    /**
     * Configure the application's route middleware aliases.
     *
     * @return void
     */
    protected function configureMiddlewareAliases()
    {
        //
    }

    /**
     * Configure the application's route middleware.
     *
     * @return void
     */
    protected function configureRouteMiddleware()
    {
        //
    }

    /**
     * Configure the application's route middleware groups.
     *
     * @return void
     */
    protected function configureRouteMiddlewareGroups()
    {
        //
    }

    /**
     * Configure the application's route middleware aliases.
     *
     * @return void
     */
    protected function configureRouteMiddlewareAliases()
    {
        //
    }

    /**
     * Configure the application's route middleware.
     *
     * @return void
     */
    protected function configureMiddleware()
    {
        //
    }

    /**
     * Configure the application's route middleware groups.
     *
     * @return void
     */
    protected function configureMiddlewareGroups()
    {
        //
    }

    /**
     * Configure the application's route middleware aliases.
     *
     * @return void
     */
    protected function configureMiddlewareAliases()
    {
        //
    }

    /**
     * Configure the application's route middleware.
     *
     * @return void
     */
    protected function configureRouteMiddleware()
    {
        //
    }

    /**
     * Configure the application's route middleware groups.
     *
     * @return void
     */
    protected function configureRouteMiddlewareGroups()
    {
        //
    }

    /**
     * Configure the application's route middleware aliases.
     *
     * @return void
     */
    protected function configureRouteMiddlewareAliases()
    {
        //
    }

    /**
     * Configure the application's route middleware.
     *
     * @return void
     */
    protected function configureMiddleware()
    {
        //
    }

    /**
     * Configure the application's route middleware groups.
     *
     * @return void
     */
    protected function configureMiddlewareGroups()
    {
        //
    }

    /**
     * Configure the application's route middleware aliases.
     *
     * @return void
     */
    protected function configureMiddlewareAliases()
    {
        //
    }

    /**
     * Configure the application's route middleware.
     *
     * @return void
     */
    protected function configureRouteMiddleware()
    {
        //
    }

    /**
     * Configure the application's route middleware groups.
     *
     * @return void
     */
    protected function configureRouteMiddlewareGroups()
    {
        //
    }

    /**
     * Configure the application's route middleware aliases.
     *
     * @return void
     */
    protected function configureRouteMiddlewareAliases()
    {
        //
    }

    /**
     * Configure the application's route middleware.
     *
     * @return void
     */
    protected function configureMiddleware()
    {
        //
    }

    /**
     * Configure the application's route middleware groups.
     *
     * @return void
     */
    protected function configureMiddlewareGroups()
    {
        //
    }

    /**
     * Configure the application's route middleware aliases.
     *
     * @return void
     */
    protected function configureMiddlewareAliases()
    {
        //
    }

    /**
     * Configure the application's route middleware.
     *
     * @return void
     */
    protected function configureRouteMiddleware()
    {
        //
    }

    /**
     * Configure the application's route middleware groups.
     *
     * @return void
     */
    protected function configureRouteMiddlewareGroups()
    {
        //
    }

    /**
     * Configure the application's route middleware aliases.
     *
     * @return void
     */
    protected function configureRouteMiddlewareAliases()
    {
        //
    }

    /**
     * Configure the application's route middleware.
     *
     * @return void
     */
    protected function configureMiddleware()
    {
        //
    }

    /**
     * Configure the application's route middleware groups.
     *
     * @return void
     */
    protected function configureMiddlewareGroups()
    {
        //
    }

    /**
     * Configure the application's route middleware aliases.
     *
     * @return void
     */
    protected function configureMiddlewareAliases()
    {
        //
    }

    /**
     * Configure the application's route middleware.
     *
     * @return void
     */
    protected function configureRouteMiddleware()
    {
        //
    }

    /**
     * Configure the application's route middleware groups.
     *
     * @return void
     */
    protected function configureRouteMiddlewareGroups()
    {
        //
    }

    /**
     * Configure the application's route middleware aliases.
     *
     * @return void
     */
    protected function configureRouteMiddlewareAliases()
    {
        //
    }

    /**
     * Configure the application's route middleware.
     *
     * @return void
     */
    protected function configureMiddleware()
    {
        //
    }

    /**
     * Configure the application's route middleware groups.
     *
     * @return void
     */
    protected function configureMiddlewareGroups()
    {
        //
    }

    /**
     * Configure the application's route middleware aliases.
     *
     * @return void
     */
    protected function configureMiddlewareAliases()
    {
        //
    }

    /**
     * Configure the application's route middleware.
     *
     * @return void
     */
    protected function configureRouteMiddleware()
    {
        //
    }

    /**
     * Configure the application's route middleware groups.
     *
     * @return void
     */
    protected function configureRouteMiddlewareGroups()
    {
        //
    }

    /**
     * Configure the application's route middleware aliases.
     *
     * @return void
     */
    protected function configureRouteMiddlewareAliases()
    {
        //
    }

    /**
     * Configure the application's route middleware.
     *
     * @return void
     */
    protected function configureMiddleware()
    {
        //
    }

    /**
     * Configure the application's route middleware groups.
     *
     * @return void
     */
    protected function configureMiddlewareGroups()
    {
        //
    }

    /**
     * Configure the application's route middleware aliases.
     *
     * @return void
     */
    protected function configureMiddlewareAliases()
    {
        //
    }

    /**
     * Configure the application's route middleware.
     *
     * @return void
     */
    protected function configureRouteMiddleware()
    {
        //
    }

    /**
     * Configure the application's route middleware groups.
     *
     * @return void
     */
    protected function configureRouteMiddlewareGroups()
    {
        //
    }

    /**
     * Configure the application's route middleware aliases.
     *
     * @return void
     */
    protected function configureRouteMiddlewareAliases()
    {
        //
    }

    /**
     * Configure the application's route middleware.
     *
     * @return void
     */
    protected function configureMiddleware()
    {
        //
    }

    /**
     * Configure the application's route middleware groups.
     *
     * @return void
     */
    protected function configureMiddlewareGroups()
    {
        //
    }

    /**
     * Configure the application's route middleware aliases.
     *
     * @return void
     */
    protected function configureMiddlewareAliases()
    {
        //
    }

    /**
     * Configure the application's route middleware.
     *
     * @return void
     */
    protected function configureRouteMiddleware()
    {
        //
    }

    /**
     * Configure the application's route middleware groups.
     *
     * @return void
     */
    protected function configureRouteMiddlewareGroups()
    {
        //
    }

    /**
     * Configure the application's route middleware aliases.
     *
     * @return void
     */
    protected function configureRouteMiddlewareAliases()
    {
        //
    }

    /**
     * Configure the application's route middleware.
     *
     * @return void
     */
    protected function configureMiddleware()
    {
        //
    }

    /**
     * Configure the application's route middleware groups.
     *
     * @return void
     */
    protected function configureMiddlewareGroups()
    {
        //
    }

    /**
     * Configure the application's route middleware aliases.
     *
     * @return void
     */
    protected function configureMiddlewareAliases()
    {
        //
    }

    /**
     * Configure the application's route middleware.
     *
     * @return void
     */
    protected function configureRouteMiddleware()
    {
        //
    }

    /**
     * Configure the application's route middleware groups.
     *
     * @return void
     */
    protected function configureRouteMiddlewareGroups()
    {
        //
    }

    /**
     * Configure the application's route middleware aliases.
     *
     * @return void
     */
    protected function configureRouteMiddlewareAliases()
    {
        //
    }

    /**
     * Configure the application's route middleware.
     *
     * @return void
     */
    protected function configureMiddleware()
    {
        //
    }

    /**
     * Configure the application's route middleware groups.
     *
     * @return void
     */
    protected function configureMiddlewareGroups()
    {
        //
    }

    /**
     * Configure the application's route middleware aliases.
     *
     * @return void
     */
    protected function configureMiddlewareAliases()
    {
        //
    }

    /**
     * Configure the application's route middleware.
     *
     * @return void
     */
    protected function configureRouteMiddleware()
    {
        //
    }

    /**
     * Configure the application's route middleware groups.
     *
     * @return void
     */
    protected function configureRouteMiddlewareGroups()
    {
        //
    }

    /**
     * Configure the application's route middleware aliases.
     *
     * @return void
     */
    protected function configureRouteMiddlewareAliases()
    {
        //
    }

    /**
     * Configure the application's route middleware.
     *
     * @return void
     */
    protected function configureMiddleware()
    {
        //
    }

    /**
     * Configure the application's route middleware groups.
     *
     * @return void
     */
    protected function configureMiddlewareGroups()
    {
        //
    }

    /**
     * Configure the application's route middleware aliases.
     *
     * @return void
     */
    protected function configureMiddlewareAliases()
    {
        //
    }

    /**
     * Configure the application's route middleware.
     *
     * @return void
     */
    protected function configureRouteMiddleware()
    {
        //
    }

    /**
     * Configure the application's route middleware groups.
     *
     * @return void
     */
    protected function configureRouteMiddlewareGroups()
    {
        //
    }

    /**
     * Configure the application's route middleware aliases.
     *
     * @return void
     */
    protected function configureRouteMiddlewareAliases()
    {
        //
    }

    /**
     * Configure the application's route middleware.
     *
     * @return void
     */
    protected function configureMiddleware()
    {
        //
    }

    /**
     * Configure the application's route middleware groups.
     *
     * @return void
     */
    protected function configureMiddlewareGroups()
    {
        //
    }

    /**
     * Configure the application's route middleware aliases.
     *
     * @return void
     */
    protected function configureMiddlewareAliases()
    {
        //
    }

    /**
     * Configure the application's route middleware.
     *
     * @return void
     */
    protected function configureRouteMiddleware()
    {
        //
    }

    /**
     * Configure the application's route middleware groups.
     *
     * @return void
     */
    protected function configureRouteMiddlewareGroups()
    {
        //
    }

    /**
     * Configure the application's route middleware aliases.
     *
     * @return void
     */
    protected function configureRouteMiddlewareAliases()
    {
        //
    }

    /**
     * Configure the application's route middleware.
     *
     * @return void
     */
    protected function configureMiddleware()
    {
        //
    }

    /**
     * Configure the application's route middleware groups.
     *
     * @return void
     */
    protected function configureMiddlewareGroups()
    {
        //
    }

    /**
     * Configure the application's route middleware aliases.
     *
     * @return void
     */
    protected function configureMiddlewareAliases()
    {
        //
    }

    /**
     * Configure the application's route middleware.
     *
     * @return void
     */
    protected function configureRouteMiddleware()
    {
        //
    }

    /**
     * Configure the application's route middleware groups.
     *
     * @return void
     */
    protected function configureRouteMiddlewareGroups()
    {
        //
    }

    /**
 