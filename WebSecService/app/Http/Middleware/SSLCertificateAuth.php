<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Models\User;

class SSLCertificateAuth
{    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        // Always check for SSL certificate on every request (except if already authenticated)
        if ($this->hasValidClientCertificate($request)) {
            $this->authenticateWithCertificate($request);
        }
        
        return $next($request);
    }

    /**
     * Check if a valid SSL client certificate is present
     */
    private function hasValidClientCertificate(Request $request): bool
    {
        return $request->server('SSL_CLIENT_VERIFY') === 'SUCCESS' && 
               !empty($request->server('SSL_CLIENT_CERT'));
    }    /**
     * Authenticate user with SSL certificate
     */
    private function authenticateWithCertificate(Request $request): void
    {
        try {
            // Extract certificate information
            $clientEmail = $request->server('SSL_CLIENT_S_DN_Email');
            $clientCN = $request->server('SSL_CLIENT_S_DN_CN');
            $clientDN = $request->server('SSL_CLIENT_S_DN');
            $clientCert = $request->server('SSL_CLIENT_CERT');
            $clientSerial = $request->server('SSL_CLIENT_M_SERIAL');

            Log::info('SSL Certificate detected', [
                'email' => $clientEmail,
                'cn' => $clientCN,
                'dn' => $clientDN,
                'serial' => $clientSerial,
                'already_authenticated' => Auth::check(),
                'current_user' => Auth::check() ? Auth::user()->email : null
            ]);

            // Try to find user by email first (most reliable)
            $user = null;
            if ($clientEmail) {
                $user = User::where('email', $clientEmail)->first();
                if ($user) {
                    Log::info('User found by certificate email', ['user_id' => $user->id, 'email' => $user->email]);
                }
            }

            // If not found by email, try by certificate common name
            if (!$user && $clientCN) {
                $user = User::where('certificate_cn', $clientCN)->first();
                if ($user) {
                    Log::info('User found by certificate CN', ['user_id' => $user->id, 'cn' => $clientCN]);
                }
            }

            // If not found by CN, try by certificate serial number
            if (!$user && $clientSerial) {
                $user = User::where('certificate_serial', $clientSerial)->first();
                if ($user) {
                    Log::info('User found by certificate serial', ['user_id' => $user->id, 'serial' => $clientSerial]);
                }
            }

            if ($user) {
                // Update certificate information in user record
                $this->updateUserCertificateInfo($user, $clientCN, $clientSerial, $clientDN);
                
                // Only log in if not already authenticated or if different user
                if (!Auth::check() || Auth::id() !== $user->id) {
                    Auth::login($user);
                    
                    Log::info('User automatically logged in via SSL certificate', [
                        'user_id' => $user->id,
                        'user_email' => $user->email,
                        'certificate_email' => $clientEmail,
                        'certificate_cn' => $clientCN,
                        'certificate_serial' => $clientSerial,
                        'user_agent' => $request->userAgent(),
                        'ip' => $request->ip()
                    ]);
                }
            } else {
                Log::warning('SSL certificate presented but no matching user found', [
                    'certificate_email' => $clientEmail,
                    'certificate_cn' => $clientCN,
                    'certificate_serial' => $clientSerial,
                    'ip' => $request->ip(),
                    'user_agent' => $request->userAgent()
                ]);
            }
        } catch (\Exception $e) {
            Log::error('Error during SSL certificate authentication', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'ip' => $request->ip()
            ]);
        }
    }

    /**
     * Update user's certificate information
     */
    private function updateUserCertificateInfo(User $user, ?string $cn, ?string $serial, ?string $dn): void
    {
        try {
            $updated = false;

            if ($cn && $user->certificate_cn !== $cn) {
                $user->certificate_cn = $cn;
                $updated = true;
            }

            if ($serial && $user->certificate_serial !== $serial) {
                $user->certificate_serial = $serial;
                $updated = true;
            }

            if ($dn && $user->certificate_dn !== $dn) {
                $user->certificate_dn = $dn;
                $updated = true;
            }

            // Update last certificate login timestamp
            $user->last_certificate_login = now();
            $updated = true;

            if ($updated) {
                $user->save();
            }
        } catch (\Exception $e) {
            Log::error('Failed to update user certificate information', [
                'user_id' => $user->id,
                'error' => $e->getMessage()
            ]);
        }
    }
}
