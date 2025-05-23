<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Feedback extends Model
{
    use HasFactory;

    // Define cancellation types
    const CANCELLATION_TYPE_CUSTOMER = 'customer';
    const CANCELLATION_TYPE_EMPLOYEE = 'employee';

    // Explicitly define the table name to match the migration
    protected $table = 'feedbacks';

    protected $fillable = [
        'order_id', 
        'user_id', 
        'reason', 
        'comments', 
        'resolved', 
        'admin_response',
        'resolved_by',
        'resolved_at',
        'cancellation_type', // New field to track who cancelled (customer or employee)
        'staff_notes', // Employee-specific notes for cancellation
    ];

    protected $casts = [
        'resolved' => 'boolean',
        'resolved_at' => 'datetime',
    ];

    // Define available customer feedback reasons
    public static function getReasons(): array
    {
        return [
            'changed_mind' => 'Changed my mind',
            'found_better_price' => 'Found a better price elsewhere',
            'delivery_time' => 'Delivery time is too long',
            'payment_issue' => 'Issue with payment method',
            'ordered_by_mistake' => 'Ordered by mistake',
            'other' => 'Other reason'
        ];
    }

    // Define available employee cancellation reasons
    public static function getEmployeeReasons(): array
    {
        return [
            'customer_request' => 'Cancelled at customer\'s request',
            'stock_unavailable' => 'Item out of stock',
            'payment_processing_failed' => 'Payment processing failed',
            'fraudulent_order' => 'Suspected fraudulent order',
            'shipping_issues' => 'Shipping issues or restrictions',
            'price_error' => 'Pricing error on listed product',
            'policy_violation' => 'Policy violation',
            'other_admin' => 'Other administrative reason'
        ];
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function resolvedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'resolved_by');
    }
    
    /**
     * Check if this feedback is from a customer cancellation
     */
    public function isCustomerCancellation(): bool
    {
        return $this->cancellation_type === self::CANCELLATION_TYPE_CUSTOMER;
    }
    
    /**
     * Check if this feedback is from an employee cancellation
     */
    public function isEmployeeCancellation(): bool
    {
        return $this->cancellation_type === self::CANCELLATION_TYPE_EMPLOYEE;
    }
}
