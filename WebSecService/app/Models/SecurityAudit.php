<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SecurityAudit extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'action',
        'category',
        'ip_address',
        'user_agent',
        'old_values',
        'new_values',
        'status',
        'risk_level',
        'description',
        'performed_by'
    ];

    protected $casts = [
        'old_values' => 'array',
        'new_values' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    // Security audit categories
    const CATEGORY_AUTHENTICATION = 'authentication';
    const CATEGORY_AUTHORIZATION = 'authorization';
    const CATEGORY_DATA_ACCESS = 'data_access';
    const CATEGORY_PROFILE_CHANGE = 'profile_change';
    const CATEGORY_SECURITY_SETTINGS = 'security_settings';
    const CATEGORY_ROLE_PERMISSION = 'role_permission';
    const CATEGORY_CREDIT_TRANSACTION = 'credit_transaction';

    // Risk levels
    const RISK_LOW = 'low';
    const RISK_MEDIUM = 'medium';
    const RISK_HIGH = 'high';
    const RISK_CRITICAL = 'critical';

    /**
     * Get the user that the audit is for
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the user who performed the action
     */
    public function performer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'performed_by');
    }

    /**
     * Log a security audit event
     */
    public static function logEvent(
        int $userId,
        string $action,
        string $category,
        string $riskLevel,
        string $description,
        ?array $oldValues = null,
        ?array $newValues = null,
        ?int $performedBy = null
    ): self {
        return self::create([
            'user_id' => $userId,
            'action' => $action,
            'category' => $category,
            'risk_level' => $riskLevel,
            'description' => $description,
            'old_values' => $oldValues,
            'new_values' => $newValues,
            'performed_by' => $performedBy ?? $userId,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'status' => 'completed'
        ]);
    }

    /**
     * Get high-risk events for a user
     */
    public static function getHighRiskEvents($userId, $days = 30)
    {
        return static::where('user_id', $userId)
            ->whereIn('risk_level', [self::RISK_HIGH, self::RISK_CRITICAL])
            ->where('created_at', '>=', now()->subDays($days))
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * Check if there are any critical security events
     */
    public static function hasCriticalEvents($userId, $hours = 24): bool
    {
        return static::where('user_id', $userId)
            ->where('risk_level', self::RISK_CRITICAL)
            ->where('created_at', '>=', now()->subHours($hours))
            ->exists();
    }

    /**
     * Get security audit summary
     */
    public static function getAuditSummary($userId, $days = 30): array
    {
        $events = static::where('user_id', $userId)
            ->where('created_at', '>=', now()->subDays($days))
            ->get();

        return [
            'total_events' => $events->count(),
            'high_risk_events' => $events->where('risk_level', self::RISK_HIGH)->count(),
            'critical_events' => $events->where('risk_level', self::RISK_CRITICAL)->count(),
            'categories' => $events->groupBy('category')->map->count(),
            'risk_levels' => $events->groupBy('risk_level')->map->count(),
        ];
    }
} 