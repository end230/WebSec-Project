<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckManagementLevel
{
    /**
     * Handle an incoming request based on management level.
     * Users with specific permissions can bypass management level requirements.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $level): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login')
                ->with('error', 'Please login to access this page.');
        }

        $user = Auth::user();
        
        // Allow users with the assign_management_level permission to bypass management level checks
        if ($user->hasPermissionTo('assign_management_level')) {
            return $next($request);
        }

        switch ($level) {
            case 'high':
                if (!$user->hasManagementLevel(User::MANAGEMENT_LEVEL_HIGH)) {
                    abort(403, 'Access denied. High-level management privileges required.');
                }
                break;

            case 'middle':
                if (!$user->hasManagementLevel(User::MANAGEMENT_LEVEL_MIDDLE) && 
                    !$user->hasManagementLevel(User::MANAGEMENT_LEVEL_HIGH)) {
                    abort(403, 'Access denied. Middle-level management privileges required.');
                }
                break;

            case 'low':
                if (!$user->hasManagementLevel(User::MANAGEMENT_LEVEL_LOW) && 
                    !$user->hasManagementLevel(User::MANAGEMENT_LEVEL_MIDDLE) && 
                    !$user->hasManagementLevel(User::MANAGEMENT_LEVEL_HIGH)) {
                    abort(403, 'Access denied. Low-level management privileges required.');
                }
                break;

            default:
                abort(500, 'Invalid management level specified.');
        }

        return $next($request);
    }
}
