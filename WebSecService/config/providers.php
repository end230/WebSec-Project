<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Service Providers
    |--------------------------------------------------------------------------
    |
    | The service providers listed here will be automatically loaded on the
    | request to your application. Add your own service providers as needed.
    |
    */

    Spatie\Permission\PermissionServiceProvider::class,
    Illuminate\Database\DatabaseServiceProvider::class,
    App\Providers\RoleMiddlewareServiceProvider::class,
];
