<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register()
    {
        // Register the Filesystem binding if not already bound
        if (!$this->app->bound('files')) {
            $this->app->singleton('files', function ($app) {
                return new Filesystem;
            });
        }

        // Ensure core providers are registered
        $providers = [
            \Illuminate\Foundation\Providers\ArtisanServiceProvider::class,
            \Illuminate\Cache\CacheServiceProvider::class,
            \Illuminate\Foundation\Providers\ConsoleSupportServiceProvider::class,
            \Illuminate\Filesystem\FilesystemServiceProvider::class,
            \Illuminate\View\ViewServiceProvider::class,
        ];

        foreach ($providers as $provider) {
            $this->app->register($provider);
        }
    }

    public function boot()
    {
        // initialize config fallbacks once config service is available
        \App\Helpers\ConfigFallbacks::setup();
    }
}
