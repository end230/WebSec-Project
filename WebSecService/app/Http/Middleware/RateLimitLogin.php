<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Lang;
use Symfony\Component\HttpFoundation\Response;

class RateLimitLogin
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $key = $this->throttleKey($request);
        
        $maxAttempts = config('security.rate_limiting.login.max_attempts', 5);
        $decayMinutes = config('security.rate_limiting.login.decay_minutes', 1);
        
        if (RateLimiter::tooManyAttempts($key, $maxAttempts)) {
            event(new Lockout($request));
            
            $seconds = RateLimiter::availableIn($key);
            
            return response()->json([
                'error' => Lang::get('auth.throttle', [
                    'seconds' => $seconds,
                    'minutes' => ceil($seconds / 60),
                ]),
            ], 429);
        }
        
        // If this is a login attempt (POST request)
        if ($request->isMethod('post')) {
            RateLimiter::hit($key, $decayMinutes * 60);
        }
        
        $response = $next($request);
        
        // If this was a successful login, clear the rate limiter
        if ($request->isMethod('post') && $response->isSuccessful() && auth()->check()) {
            RateLimiter::clear($key);
        }
        
        return $response;
    }
    
    /**
     * Get the rate limiting throttle key for the request.
     */
    protected function throttleKey(Request $request): string
    {
        return Str::transliterate(Str::lower($request->input('email')).'|'.$request->ip());
    }
}
