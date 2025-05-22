<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'total_amount',
        'status',
        'shipping_address',
        'billing_address',
    ];

    /**
     * Get the user that owns the order.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the items for the order.
     */
    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }
    
    /**
     * Get the feedback for the order.
     */
    public function feedback(): HasMany
    {
        return $this->hasMany(Feedback::class);
    }
    
    /**
     * Get the latest feedback for the order.
     */
    public function latestFeedback()
    {
        return $this->hasOne(Feedback::class)->latest();
    }
    
    /**
     * Check if order has any unresolved feedback.
     */
    public function hasUnresolvedFeedback(): bool
    {
        return $this->feedback()->where('resolved', false)->exists();
    }
}
