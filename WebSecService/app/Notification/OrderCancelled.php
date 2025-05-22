<?php

namespace App\Notifications;

use App\Models\Order;
use App\Models\Feedback;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class OrderCancelled extends Notification implements ShouldQueue
{
    use Queueable;
    
    protected $order;
    protected $reason;
    protected $isEmployeeCancellation;

    /**
     * Create a new notification instance.
     */
    public function __construct(Order $order, string $reason, bool $isEmployeeCancellation = false)
    {
        $this->order = $order;
        $this->reason = $reason;
        $this->isEmployeeCancellation = $isEmployeeCancellation;
    }

    /**
     * Get the notification's delivery channels.
     */
    public function via($notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray($notifiable): array
    {
        $reasonText = $this->isEmployeeCancellation 
            ? Feedback::getEmployeeReasons()[$this->reason] ?? $this->reason
            : Feedback::getReasons()[$this->reason] ?? $this->reason;
            
        return [
            'order_id' => $this->order->id,
            'customer_name' => $this->order->user->name,
            'reason' => $reasonText,
            'cancelled_at' => now()->toDateTimeString(),
            'amount' => $this->order->total_amount,
            'url' => route('orders.show', $this->order->id),
            'is_employee_cancellation' => $this->isEmployeeCancellation,
        ];
    }
}
