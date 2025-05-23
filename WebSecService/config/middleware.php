<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Application Middleware Aliases
    |--------------------------------------------------------------------------
    |
    | Here you may define all of your middleware aliases that will be registered
    | with the application. This allows you to use more readable names in your
    | middleware routes.
    |
    */

    // Role and permission middleware
    'role' => \App\Http\Middleware\CheckRole::class,
    'permission' => \Spatie\Permission\Middleware\PermissionMiddleware::class, // Fix: changed Middlewares to Middleware
    'role_or_permission' => \Spatie\Permission\Middleware\RoleOrPermissionMiddleware::class, // Fix: also updated this one
    
    // Rate limiting middleware
    'rate.login' => \App\Http\Middleware\RateLimitLogin::class,
];
