<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\Facade;

// Ensure files binding is available early
if (!defined('LARAVEL_START')) {
    define('LARAVEL_START', microtime(true));
}

// Register the Composer autoloader
require __DIR__.'/../vendor/autoload.php';

// Include our bootstrap helper
require_once __DIR__.'/../app/Helpers/bootstrap.php';

/*
|--------------------------------------------------------------------------
| Create The Application
|--------------------------------------------------------------------------
*/

$app = Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        channels: __DIR__.'/../routes/channels.php',
    )
    ->withMiddleware(function ($middleware) {
        // Directly register the RateLimitLogin middleware
        $middleware->alias([
            'rate.login' => \App\Http\Middleware\RateLimitLogin::class,
            'role' => \App\Http\Middleware\CheckRole::class,
            'permission' => \Spatie\Permission\Middleware\PermissionMiddleware::class, // Fix: changed Middlewares to Middleware
            'role_or_permission' => \Spatie\Permission\Middleware\RoleOrPermissionMiddleware::class, // Fix: also updated this one
            'employee.feedback' => \App\Http\Middleware\EmployeeFeedbackNotifier::class,
            'throttle' => \Illuminate\Routing\Middleware\ThrottleRequests::class,
            'auth' => \App\Http\Middleware\Authenticate::class,
            'guest' => \App\Http\Middleware\RedirectIfAuthenticated::class,
        ]);
        
        // Register middleware aliases from config
        if (file_exists($path = config_path('middleware.php'))) {
            $aliases = require $path;
            if (isset($aliases['aliases']) && is_array($aliases['aliases'])) {
                foreach ($aliases['aliases'] as $name => $class) {
                    $middleware->alias($name, $class);
                }
            }
        }
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })
    ->create();

// Set Facade application instance
Facade::setFacadeApplication($app);

return $app;
