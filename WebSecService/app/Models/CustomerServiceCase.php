<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class CustomerServiceCase extends Model
{
    use HasFactory;

    protected $fillable = [
        'case_number',
        'product_comment_id',
        'customer_id',
        'product_id',
        'assigned_to',
        'status',
        'priority',
        'category',
        'subject',
        'description',
        'resolution',
        'internal_notes',
        'assigned_at',
        'first_response_at',
        'resolved_at',
        'closed_at',
        'response_time_hours',
        'resolution_time_hours',
    ];

    protected $casts = [
        'assigned_at' => 'datetime',
        'first_response_at' => 'datetime',
        'resolved_at' => 'datetime',
        'closed_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    const STATUS_OPEN = 'open';
    const STATUS_IN_PROGRESS = 'in_progress';
    const STATUS_WAITING_CUSTOMER = 'waiting_customer';
    const STATUS_RESOLVED = 'resolved';
    const STATUS_CLOSED = 'closed';

    const PRIORITY_LOW = 'low';
    const PRIORITY_MEDIUM = 'medium';
    const PRIORITY_HIGH = 'high';
    const PRIORITY_URGENT = 'urgent';

    /**
     * Get the product comment that generated this case
     */
    public function productComment()
    {
        return $this->belongsTo(ProductComment::class);
    }

    /**
     * Get the customer who this case belongs to
     */
    public function customer()
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    /**
     * Get the product this case is about
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Get the customer service representative assigned to this case
     */
    public function assignedTo()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    /**
     * Get all activities for this case
     */
    public function activities()
    {
        return $this->hasMany(CaseActivity::class, 'case_id')->orderBy('created_at', 'desc');
    }

    /**
     * Get customer visible activities for this case
     */
    public function customerActivities()
    {
        return $this->hasMany(CaseActivity::class, 'case_id')
            ->where('is_customer_visible', true)
            ->orderBy('created_at', 'desc');
    }

    /**
     * Get the priority badge class for UI
     */
    public function getPriorityBadgeClass(): string
    {
        return match($this->priority) {
            'urgent' => 'bg-danger',
            'high' => 'bg-warning',
            'medium' => 'bg-info',
            'low' => 'bg-secondary',
            default => 'bg-secondary'
        };
    }

    /**
     * Get the status badge class for UI
     */
    public function getStatusBadgeClass(): string
    {
        return match($this->status) {
            'open' => 'bg-danger',
            'in_progress' => 'bg-warning',
            'waiting_customer' => 'bg-info',
            'resolved' => 'bg-success',
            'closed' => 'bg-secondary',
            default => 'bg-secondary'
        };
    }

    /**
     * Check if case is overdue (open for more than X hours based on priority)
     */
    public function isOverdue(): bool
    {
        if (in_array($this->status, ['resolved', 'closed'])) {
            return false;
        }

        $hoursLimit = match($this->priority) {
            'urgent' => 2,
            'high' => 8,
            'medium' => 24,
            'low' => 72,
            default => 24
        };

        return $this->created_at->diffInHours(now()) > $hoursLimit;
    }

    /**
     * Get time since creation in human readable format
     */
    public function getTimeSinceCreation(): string
    {
        return $this->created_at->diffForHumans();
    }

    /**
     * Calculate and update response time
     */
    public function updateResponseTime()
    {
        if (!$this->first_response_at) {
            $this->first_response_at = now();
            $this->response_time_hours = $this->created_at->diffInHours($this->first_response_at);
            $this->save();
        }
    }

    /**
     * Calculate and update resolution time
     */
    public function updateResolutionTime()
    {
        if (!$this->resolved_at && in_array($this->status, ['resolved', 'closed'])) {
            $this->resolved_at = now();
            $this->resolution_time_hours = $this->created_at->diffInHours($this->resolved_at);
            $this->save();
        }
    }

    /**
     * Assign case to a customer service representative
     */
    public function assignTo(User $user, User $assignedBy = null)
    {
        $oldAssignedTo = $this->assigned_to;
        
        $this->assigned_to = $user->id;
        $this->assigned_at = now();
        
        if ($this->status === 'open') {
            $this->status = 'in_progress';
        }
        
        $this->save();

        // Create activity
        CaseActivity::create([
            'case_id' => $this->id,
            'user_id' => $assignedBy ? $assignedBy->id : $user->id,
            'activity_type' => 'assigned',
            'title' => 'Case assigned to ' . $user->name,
            'description' => $oldAssignedTo ? 
                "Case reassigned from {$this->assignedTo->name} to {$user->name}" : 
                "Case assigned to {$user->name}",
            'metadata' => json_encode([
                'old_assigned_to' => $oldAssignedTo,
                'new_assigned_to' => $user->id,
            ]),
            'is_customer_visible' => true,
            'is_system_generated' => false,
        ]);
    }

    /**
     * Update case status
     */
    public function updateStatus(string $newStatus, User $user, string $reason = null)
    {
        $oldStatus = $this->status;
        $this->status = $newStatus;

        // Update timestamps based on status
        if ($newStatus === 'resolved' && !$this->resolved_at) {
            $this->updateResolutionTime();
        }

        if ($newStatus === 'closed' && !$this->closed_at) {
            $this->closed_at = now();
            if (!$this->resolved_at) {
                $this->updateResolutionTime();
            }
        }

        $this->save();

        // Create activity
        CaseActivity::create([
            'case_id' => $this->id,
            'user_id' => $user->id,
            'activity_type' => 'status_changed',
            'title' => "Status changed from {$oldStatus} to {$newStatus}",
            'description' => $reason ? "Status changed: {$reason}" : "Status updated to {$newStatus}",
            'metadata' => json_encode([
                'old_status' => $oldStatus,
                'new_status' => $newStatus,
                'reason' => $reason,
            ]),
            'is_customer_visible' => true,
            'is_system_generated' => false,
        ]);
    }

    /**
     * Scope for cases assigned to a specific user
     */
    public function scopeAssignedTo($query, $userId)
    {
        return $query->where('assigned_to', $userId);
    }

    /**
     * Scope for cases with specific status
     */
    public function scopeWithStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope for cases with specific priority
     */
    public function scopeWithPriority($query, $priority)
    {
        return $query->where('priority', $priority);
    }

    /**
     * Scope for overdue cases
     */
    public function scopeOverdue($query)
    {
        return $query->whereNotIn('status', ['resolved', 'closed'])
            ->where(function($q) {
                $q->where('priority', 'urgent')->where('created_at', '<', now()->subHours(2))
                  ->orWhere('priority', 'high')->where('created_at', '<', now()->subHours(8))
                  ->orWhere('priority', 'medium')->where('created_at', '<', now()->subHours(24))
                  ->orWhere('priority', 'low')->where('created_at', '<', now()->subHours(72));
            });
    }

    /**
     * Scope for unassigned cases
     */
    public function scopeUnassigned($query)
    {
        return $query->whereNull('assigned_to');
    }
} 