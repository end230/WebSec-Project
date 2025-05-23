<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use App\Services\DatabaseStatusService;

class CheckDatabaseConnection
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Skip check for assets and certain routes
        if ($this->shouldSkipCheck($request)) {
            return $next($request);
        }

        // Check if database is available
        if (!DatabaseStatusService::isAvailable()) {
            // If it's an AJAX request, return JSON error
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'error' => 'Database connection error. Please try again later.'
                ], 503);
            }
            
            // Show maintenance view for regular requests
            return response()->view('errors.database-unavailable');
        }

        return $next($request);
    }

    /**
     * Determine if the middleware should skip checking for this request
     *
     * @param  \Illuminate\Http\Request  $request
     * @return bool
     */
    protected function shouldSkipCheck(Request $request): bool
    {
        // Skip for asset files
        if ($this->isAssetRequest($request)) {
            return true;
        }

        // Skip for certain paths that don't need database access
        $skipPaths = [
            'health', 'ping', 'favicon.ico'
        ];

        return in_array($request->path(), $skipPaths);
    }

    /**
     * Check if the request is for an asset file
     *
     * @param  \Illuminate\Http\Request  $request
     * @return bool
     */
    protected function isAssetRequest(Request $request): bool
    {
        $path = $request->path();
        $assetExtensions = ['js', 'css', 'png', 'jpg', 'jpeg', 'gif', 'svg', 'ico', 'woff', 'woff2', 'ttf', 'eot'];
        
        foreach ($assetExtensions as $extension) {
            if (str_ends_with($path, '.' . $extension)) {
                return true;
            }
        }
        
        return false;
    }
}
