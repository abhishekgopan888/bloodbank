<?php

namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    /**
     * The application's global HTTP middleware stack.
     *
     * @var array<int, class-string>
     */
    protected $middleware = [
        // keep minimal for API
    ];

    /**
     * The application's route middleware groups.
     *
     * @var array<string, array<int, class-string>>
     */
    protected $middlewareGroups = [
        'web' => [],
        'api' => [
            'throttle:api',
            '\\Illuminate\\Routing\\Middleware\\SubstituteBindings',
        ],
    ];

    /**
     * The application's route middleware.
     *
     * @var array<string, class-string>
     */
    protected $routeMiddleware = [
        'auth' => '\\App\\Http\\Middleware\\Authenticate',
        'auth.basic' => '\\Illuminate\\Auth\\Middleware\\AuthenticateWithBasicAuth',
        'role' => '\\App\\Http\\Middleware\\EnsureRole',
    ];
}
