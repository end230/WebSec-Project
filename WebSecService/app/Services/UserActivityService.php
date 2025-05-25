<?php

namespace App\Services;

use App\Models\User;
use App\Models\LoginAttempt;
use App\Models\SecurityAudit;
use App\Models\NotificationPreference;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;
use Jenssegers\Agent\Agent;

class UserActivityService
{
    /**
     * Activity types for tracking
     */
    const ACTIVITY_LOGIN = 'login';
    const ACTIVITY_LOGOUT = 'logout';
    const ACTIVITY_PROFILE_UPDATE = 'profile_update';
    const ACTIVITY_PASSWORD_CHANGE = 'password_change';
    const ACTIVITY_CREDIT_TRANSACTION = 'credit_transaction';
    const ACTIVITY_ROLE_CHANGE = 'role_change';
    const ACTIVITY_PERMISSION_CHANGE = 'permission_change';
    const ACTIVITY_2FA_CHANGE = 'two_factor_change';
    const ACTIVITY_EMAIL_CHANGE = 'email_change';
    const ACTIVITY_ACCOUNT_LOCK = 'account_lock';
    const ACTIVITY_SECURITY_ALERT = 'security_alert';

    /**
     * Risk score thresholds
     */
    const RISK_SCORE_LOW = 25;
    const RISK_SCORE_MEDIUM = 50;
    const RISK_SCORE_HIGH = 75;
    const RISK_SCORE_CRITICAL = 90;

    /**
     * Cache durations (in minutes)
     */
    const CACHE_DURATION_SHORT = 15;
    const CACHE_DURATION_MEDIUM = 60;
    const CACHE_DURATION_LONG = 1440; // 24 hours

    /**
     * Track user login activity
     */
    public function trackLogin(User $user, bool $success, ?string $failureReason = null): void
    {
        $agent = new Agent();
        $location = $this->getLocationFromIP(request()->ip());

        // Create login attempt record
        LoginAttempt::create([
            'user_id' => $user->id,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'location' => $location,
            'status' => $success ? LoginAttempt::STATUS_SUCCESS : LoginAttempt::STATUS_FAILED,
            'failure_reason' => $failureReason,
            'attempted_at' => now(),
        ]);

        // Update user's last login timestamp if successful
        if ($success) {
            $user->update(['last_login_at' => now()]);
            $this->analyzeLoginPattern($user);
        }

        // Check for suspicious activity
        if ($this->isSuspiciousLogin($user)) {
            $this->triggerSecurityAlert($user, 'Suspicious login activity detected');
        }
    }

    /**
     * Analyze user's login pattern for anomalies
     */
    protected function analyzeLoginPattern(User $user): void
    {
        $recentLogins = LoginAttempt::where('user_id', $user->id)
            ->where('status', LoginAttempt::STATUS_SUCCESS)
            ->where('created_at', '>=', now()->subDays(30))
            ->get();

        $riskScore = 0;
        $unusualFactors = [];

        // Check login time pattern
        $typicalLoginHours = $this->getTypicalLoginHours($recentLogins);
        if (!in_array(now()->hour, $typicalLoginHours)) {
            $riskScore += 20;
            $unusualFactors[] = 'Unusual login time';
        }

        // Check location pattern
        $typicalLocations = $this->getTypicalLocations($recentLogins);
        $currentLocation = $this->getLocationFromIP(request()->ip());
        if (!in_array($currentLocation['country'] ?? null, $typicalLocations)) {
            $riskScore += 30;
            $unusualFactors[] = 'Unusual location';
        }

        // Check device pattern
        $agent = new Agent();
        $typicalDevices = $this->getTypicalDevices($recentLogins);
        if (!in_array($agent->device(), $typicalDevices)) {
            $riskScore += 15;
            $unusualFactors[] = 'New device';
        }

        // Log high-risk patterns
        if ($riskScore >= self::RISK_SCORE_HIGH) {
            $this->logSecurityEvent($user, 'High-risk login pattern detected', SecurityAudit::RISK_HIGH);
        }
    }

    /**
     * Get typical login hours for a user
     */
    protected function getTypicalLoginHours(Collection $logins): array
    {
        return $logins->groupBy(function ($login) {
            return Carbon::parse($login->attempted_at)->hour;
        })
        ->filter(function ($group) use ($logins) {
            // Consider hours with at least 10% of total logins as typical
            return $group->count() >= ($logins->count() * 0.1);
        })
        ->keys()
        ->toArray();
    }

    /**
     * Get typical locations for a user
     */
    protected function getTypicalLocations(Collection $logins): array
    {
        return $logins->pluck('location.country')
            ->filter()
            ->unique()
            ->values()
            ->toArray();
    }

    /**
     * Get typical devices for a user
     */
    protected function getTypicalDevices(Collection $logins): array
    {
        $agent = new Agent();
        return $logins->map(function ($login) use ($agent) {
            $agent->setUserAgent($login->user_agent);
            return $agent->device();
        })
        ->unique()
        ->values()
        ->toArray();
    }

    /**
     * Check if current login attempt is suspicious
     */
    protected function isSuspiciousLogin(User $user): bool
    {
        $recentFailedAttempts = LoginAttempt::where('user_id', $user->id)
            ->where('status', LoginAttempt::STATUS_FAILED)
            ->where('created_at', '>=', now()->subHours(24))
            ->count();

        if ($recentFailedAttempts >= 5) {
            return true;
        }

        // Check for rapid location changes
        $lastSuccessfulLogin = LoginAttempt::where('user_id', $user->id)
            ->where('status', LoginAttempt::STATUS_SUCCESS)
            ->latest()
            ->first();

        if ($lastSuccessfulLogin) {
            $lastLocation = $lastSuccessfulLogin->location['country'] ?? null;
            $currentLocation = $this->getLocationFromIP(request()->ip())['country'] ?? null;

            if ($lastLocation && $currentLocation && $lastLocation !== $currentLocation) {
                $timeDiff = Carbon::parse($lastSuccessfulLogin->attempted_at)->diffInHours(now());
                if ($timeDiff < 12) {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * Get location information from IP address
     */
    protected function getLocationFromIP(string $ip): array
    {
        // This is a placeholder. In a real application, you would:
        // 1. Use a geolocation service (e.g., MaxMind GeoIP2)
        // 2. Cache the results
        // 3. Handle rate limiting
        return [
            'country' => 'Unknown',
            'city' => 'Unknown',
            'latitude' => 0,
            'longitude' => 0
        ];
    }

    /**
     * Trigger a security alert
     */
    protected function triggerSecurityAlert(User $user, string $reason): void
    {
        // Log the security event
        $this->logSecurityEvent($user, $reason, SecurityAudit::RISK_HIGH);

        // Send notification if enabled
        $notificationPreference = $user->notificationPreferences;
        if ($notificationPreference && $notificationPreference->security_alerts) {
            // Implementation would depend on your notification system
            // event(new SecurityAlertEvent($user, $reason));
        }

        // Cache the alert for quick access
        $cacheKey = "user:{$user->id}:security_alerts";
        $alerts = Cache::get($cacheKey, []);
        $alerts[] = [
            'reason' => $reason,
            'timestamp' => now()->toIso8601String(),
            'ip_address' => request()->ip()
        ];
        Cache::put($cacheKey, $alerts, now()->addDay());
    }

    /**
     * Log a security event
     */
    protected function logSecurityEvent(User $user, string $description, string $riskLevel): void
    {
        SecurityAudit::logEvent(
            $user->id,
            self::ACTIVITY_SECURITY_ALERT,
            SecurityAudit::CATEGORY_AUTHENTICATION,
            $riskLevel,
            $description
        );
    }

    /**
     * Get user activity summary
     */
    public function getActivitySummary(User $user, int $days = 30): array
    {
        $cacheKey = "user:{$user->id}:activity_summary:{$days}";
        
        return Cache::remember($cacheKey, self::CACHE_DURATION_MEDIUM, function () use ($user, $days) {
            $loginAttempts = LoginAttempt::where('user_id', $user->id)
                ->where('created_at', '>=', now()->subDays($days))
                ->get();

            $securityAudits = SecurityAudit::where('user_id', $user->id)
                ->where('created_at', '>=', now()->subDays($days))
                ->get();

            return [
                'total_logins' => $loginAttempts->where('status', LoginAttempt::STATUS_SUCCESS)->count(),
                'failed_logins' => $loginAttempts->where('status', LoginAttempt::STATUS_FAILED)->count(),
                'suspicious_activities' => $securityAudits->where('risk_level', SecurityAudit::RISK_HIGH)->count(),
                'last_login' => $user->last_login_at?->diffForHumans(),
                'security_score' => $user->getSecurityScore(),
                'profile_completion' => $user->getProfileCompletionPercentage(),
                'recent_locations' => $this->getRecentLocations($loginAttempts),
                'risk_level' => $this->calculateUserRiskLevel($user)
            ];
        });
    }

    /**
     * Get recent login locations
     */
    protected function getRecentLocations(Collection $loginAttempts): array
    {
        return $loginAttempts
            ->where('status', LoginAttempt::STATUS_SUCCESS)
            ->pluck('location')
            ->filter()
            ->unique('country')
            ->values()
            ->toArray();
    }

    /**
     * Calculate user's current risk level
     */
    protected function calculateUserRiskLevel(User $user): string
    {
        $score = 0;

        // Check security measures
        if (!$user->two_factor_enabled) $score += 30;
        if ($user->passwordNeedsChange()) $score += 20;
        if (!$user->recovery_email) $score += 15;
        if (!$user->security_questions_set) $score += 15;

        // Check recent suspicious activities
        if (SecurityAudit::hasCriticalEvents($user->id)) $score += 40;
        
        // Check failed login attempts
        $recentFailedAttempts = LoginAttempt::where('user_id', $user->id)
            ->where('status', LoginAttempt::STATUS_FAILED)
            ->where('created_at', '>=', now()->subHours(24))
            ->count();
        $score += min(30, $recentFailedAttempts * 6);

        // Determine risk level based on score
        if ($score >= self::RISK_SCORE_CRITICAL) return SecurityAudit::RISK_CRITICAL;
        if ($score >= self::RISK_SCORE_HIGH) return SecurityAudit::RISK_HIGH;
        if ($score >= self::RISK_SCORE_MEDIUM) return SecurityAudit::RISK_MEDIUM;
        return SecurityAudit::RISK_LOW;
    }
} 