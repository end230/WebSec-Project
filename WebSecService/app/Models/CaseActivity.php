<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CaseActivity extends Model
{
    use HasFactory;

    protected $fillable = [
        'case_id',
        'user_id',
        'activity_type',
        'title',
        'description',
        'metadata',
        'is_customer_visible',
        'is_system_generated',
    ];

    protected $casts = [
        'metadata' => 'array',
        'is_customer_visible' => 'boolean',
        'is_system_generated' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the case this activity belongs to
     */
    public function case()
    {
        return $this->belongsTo(CustomerServiceCase::class);
    }

    /**
     * Get the user who performed this activity
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the icon for this activity type
     */
    public function getActivityIcon(): string
    {
        return match($this->activity_type) {
            'created' => 'bi-plus-circle',
            'assigned' => 'bi-person-check',
            'status_changed' => 'bi-arrow-repeat',
            'priority_changed' => 'bi-exclamation-triangle',
            'comment_added' => 'bi-chat-left-text',
            'internal_note_added' => 'bi-journal-text',
            'resolved' => 'bi-check-circle',
            'closed' => 'bi-x-circle',
            'reopened' => 'bi-arrow-clockwise',
            default => 'bi-info-circle'
        };
    }

    /**
     * Get the color class for this activity type
     */
    public function getActivityColorClass(): string
    {
        return match($this->activity_type) {
            'created' => 'text-success',
            'assigned' => 'text-info',
            'status_changed' => 'text-warning',
            'priority_changed' => 'text-danger',
            'comment_added' => 'text-primary',
            'internal_note_added' => 'text-muted',
            'resolved' => 'text-success',
            'closed' => 'text-secondary',
            'reopened' => 'text-warning',
            default => 'text-info'
        };
    }

    /**
     * Create an activity for a case
     */
    public static function createActivity(
        int $caseId,
        int $userId,
        string $activityType,
        string $title,
        string $description = null,
        array $metadata = null,
        bool $isCustomerVisible = false,
        bool $isSystemGenerated = false
    ): self {
        return self::create([
            'case_id' => $caseId,
            'user_id' => $userId,
            'activity_type' => $activityType,
            'title' => $title,
            'description' => $description,
            'metadata' => $metadata,
            'is_customer_visible' => $isCustomerVisible,
            'is_system_generated' => $isSystemGenerated,
        ]);
    }

    /**
     * Scope for customer visible activities
     */
    public function scopeCustomerVisible($query)
    {
        return $query->where('is_customer_visible', true);
    }

    /**
     * Scope for system generated activities
     */
    public function scopeSystemGenerated($query)
    {
        return $query->where('is_system_generated', true);
    }

    /**
     * Scope for activities by type
     */
    public function scopeByType($query, string $type)
    {
        return $query->where('activity_type', $type);
    }
}
