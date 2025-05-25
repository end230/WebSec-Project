<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LoginAttempt extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'ip_address',
        'user_agent',
        'location',
        'status',
        'failure_reason',
        'attempted_at',
    ];

    protected $casts = [
        'attempted_at' => 'datetime',
        'location' => 'array',
    ];

    const STATUS_SUCCESS = 'success';
    const STATUS_FAILED = 'failed';
    const STATUS_BLOCKED = 'blocked';

    const FAILURE_REASON_INVALID_CREDENTIALS = 'invalid_credentials';
    const FAILURE_REASON_ACCOUNT_LOCKED = 'account_locked';
    const FAILURE_REASON_IP_BLOCKED = 'ip_blocked';
    const FAILURE_REASON_2FA_FAILED = 'two_factor_failed';
    const FAILURE_REASON_SUSPICIOUS_ACTIVITY = 'suspicious_activity';

    /**
     * Get the user that owns the login attempt.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Check if the attempt was successful
     */
    public function isSuccessful(): bool
    {
        return $this->status === self::STATUS_SUCCESS;
    }

    /**
     * Check if the attempt was blocked
     */
    public function wasBlocked(): bool
    {
        return $this->status === self::STATUS_BLOCKED;
    }

    /**
     * Get the time elapsed since the attempt
     */
    public function getTimeElapsedAttribute(): string
    {
        return $this->attempted_at->diffForHumans();
    }

    /**
     * Get suspicious login attempts for a user
     */
    public static function getSuspiciousAttempts($userId, $hours = 24)
    {
        return static::where('user_id', $userId)
            ->where('attempted_at', '>=', now()->subHours($hours))
            ->where(function ($query) {
                $query->where('status', self::STATUS_FAILED)
                    ->orWhere('status', self::STATUS_BLOCKED);
            })
            ->get();
    }

    /**
     * Check if there are too many failed attempts
     */
    public static function hasTooManyFailedAttempts($userId, $maxAttempts = 5, $minutes = 30): bool
    {
        $failedAttempts = static::where('user_id', $userId)
            ->where('status', self::STATUS_FAILED)
            ->where('attempted_at', '>=', now()->subMinutes($minutes))
            ->count();

        return $failedAttempts >= $maxAttempts;
    }
} 