<?php

namespace App\Notifications;

use App\Models\Feedback;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class NewFeedback extends Notification implements ShouldQueue
{
    use Queueable;
    
    protected $feedback;

    /**
     * Create a new notification instance.
     */
    public function __construct(Feedback $feedback)
    {
        $this->feedback = $feedback;
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
        return [
            'feedback_id' => $this->feedback->id,
            'order_id' => $this->feedback->order_id,
            'customer_name' => $this->feedback->user->name,
            'reason' => $this->feedback->reason,
            'submitted_at' => $this->feedback->created_at->toDateTimeString(),
            'url' => route('feedback.show', $this->feedback->id)
        ];
    }
}
