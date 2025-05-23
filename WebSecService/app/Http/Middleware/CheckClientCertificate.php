<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class CheckClientCertificate
{
    public function handle(Request $request, Closure $next)
    {
        // If user is already authenticated, proceed
        if (Auth::check()) {
            return $next($request);
        }

        // Check if we have a valid client certificate
        $clientVerify = $request->server('SSL_CLIENT_VERIFY');
        $clientDN = $request->server('SSL_CLIENT_S_DN');
        $clientEmail = $request->server('SSL_CLIENT_S_DN_Email');
        $clientSerial = $request->server('SSL_CLIENT_M_SERIAL');

        if ($clientVerify === 'SUCCESS' && ($clientEmail || $clientDN)) {
            // Try to find user by email first
            if ($clientEmail) {
                $user = User::where('email', $clientEmail)->first();
            }

            // If no user found by email, try to find by certificate serial
            if (!isset($user) && $clientSerial) {
                $user = User::where('certificate_serial', $clientSerial)->first();
            }

            // If we found a user, log them in
            if (isset($user)) {
                Auth::login($user);
                
                // Update certificate info if needed
                if ($user->certificate_serial !== $clientSerial) {
                    $user->update([
                        'certificate_serial' => $clientSerial,
                        'certificate_dn' => $clientDN,
                        'last_certificate_login' => now()
                    ]);
                }
            }
        }

        return $next($request);
    }
} 