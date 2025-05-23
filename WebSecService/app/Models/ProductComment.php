<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductComment extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'user_id',
        'rating',
        'comment',
        'is_verified_purchase',
        'is_approved',
        'approved_at',
        'approved_by',
    ];

    protected $casts = [
        'is_verified_purchase' => 'boolean',
        'is_approved' => 'boolean',
        'approved_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the product that this comment belongs to
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Get the user who made this comment
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the user who approved this comment
     */
    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    /**
     * Get the customer service case if this comment generated one
     */
    public function customerServiceCase()
    {
        return $this->hasOne(CustomerServiceCase::class);
    }

    /**
     * Check if this comment is low rated (3 stars or below)
     */
    public function isLowRated(): bool
    {
        return $this->rating <= 3;
    }

    /**
     * Get the star rating as filled/empty stars for display
     */
    public function getStarDisplay(): array
    {
        $stars = [];
        for ($i = 1; $i <= 5; $i++) {
            $stars[] = $i <= $this->rating ? 'filled' : 'empty';
        }
        return $stars;
    }

    /**
     * Boot the model to handle automatic case creation
     */
    protected static function booted()
    {
        static::created(function ($comment) {
            // Auto-create customer service case for low ratings
            if ($comment->isLowRated() && $comment->is_approved) {
                $comment->createCustomerServiceCase();
            }
        });

        static::updated(function ($comment) {
            // If comment was just approved and is low rated, create case
            if ($comment->wasChanged('is_approved') && $comment->is_approved && $comment->isLowRated()) {
                $comment->createCustomerServiceCase();
            }
        });
    }

    /**
     * Create a customer service case for this comment
     */
    public function createCustomerServiceCase()
    {
        // Don't create duplicate cases
        if ($this->customerServiceCase()->exists()) {
            return $this->customerServiceCase;
        }

        $caseNumber = $this->generateCaseNumber();
        
        $case = CustomerServiceCase::create([
            'case_number' => $caseNumber,
            'product_comment_id' => $this->id,
            'customer_id' => $this->user_id,
            'product_id' => $this->product_id,
            'status' => 'open',
            'priority' => $this->determinePriority(),
            'category' => 'product_quality',
            'subject' => "Low rating ({$this->rating} stars) for {$this->product->name}",
            'description' => "Customer left a {$this->rating}-star review: \"{$this->comment}\"",
        ]);

        // Create initial activity
        CaseActivity::create([
            'case_id' => $case->id,
            'user_id' => $this->user_id,
            'activity_type' => 'created',
            'title' => 'Case automatically created from low rating',
            'description' => "Case created from {$this->rating}-star product review",
            'is_customer_visible' => false,
            'is_system_generated' => true,
        ]);

        return $case;
    }

    /**
     * Generate a unique case number
     */
    private function generateCaseNumber(): string
    {
        $year = date('Y');
        $count = CustomerServiceCase::whereYear('created_at', $year)->count() + 1;
        return sprintf('CS-%s-%03d', $year, $count);
    }

    /**
     * Determine priority based on rating
     */
    private function determinePriority(): string
    {
        return match($this->rating) {
            1 => 'urgent',
            2 => 'high',
            3 => 'medium',
            default => 'low'
        };
    }

    /**
     * Scope for approved comments
     */
    public function scopeApproved($query)
    {
        return $query->where('is_approved', true);
    }

    /**
     * Scope for low rated comments
     */
    public function scopeLowRated($query)
    {
        return $query->where('rating', '<=', 3);
    }

    /**
     * Scope for verified purchases
     */
    public function scopeVerifiedPurchase($query)
    {
        return $query->where('is_verified_purchase', true);
    }
} 