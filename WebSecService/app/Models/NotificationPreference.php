<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class NotificationPreference extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'email_notifications',
        'push_notifications',
        'sms_notifications',
        'marketing_emails',
        'security_alerts',
        'order_updates',
        'product_updates',
        'newsletter',
        'price_alerts',
        'low_balance_alerts',
        'login_alerts',
        'notification_frequency',
        'quiet_hours_start',
        'quiet_hours_end',
        'preferred_language',
        'timezone'
    ];

    protected $casts = [
        'email_notifications' => 'boolean',
        'push_notifications' => 'boolean',
        'sms_notifications' => 'boolean',
        'marketing_emails' => 'boolean',
        'security_alerts' => 'boolean',
        'order_updates' => 'boolean',
        'product_updates' => 'boolean',
        'newsletter' => 'boolean',
        'price_alerts' => 'boolean',
        'low_balance_alerts' => 'boolean',
        'login_alerts' => 'boolean',
        'quiet_hours_start' => 'datetime',
        'quiet_hours_end' => 'datetime'
    ];

    const FREQUENCY_IMMEDIATE = 'immediate';
    const FREQUENCY_DAILY = 'daily';
    const FREQUENCY_WEEKLY = 'weekly';
    const FREQUENCY_MONTHLY = 'monthly';

    /**
     * Get the user that owns the notification preferences.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Check if quiet hours are active
     */
    public function isInQuietHours(): bool
    {
        if (!$this->quiet_hours_start || !$this->quiet_hours_end) {
            return false;
        }

        $now = now()->setTimezone($this->timezone);
        $start = $this->quiet_hours_start->setTimezone($this->timezone);
        $end = $this->quiet_hours_end->setTimezone($this->timezone);

        return $now->between($start, $end);
    }

    /**
     * Get available notification channels
     */
    public function getActiveChannels(): array
    {
        $channels = [];
        
        if ($this->email_notifications) $channels[] = 'email';
        if ($this->push_notifications) $channels[] = 'push';
        if ($this->sms_notifications) $channels[] = 'sms';
        
        return $channels;
    }

    /**
     * Check if a specific notification type is enabled
     */
    public function isNotificationEnabled(string $type): bool
    {
        return $this->$type === true;
    }
} 