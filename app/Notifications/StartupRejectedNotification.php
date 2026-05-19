<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class StartupRejectedNotification extends Notification
{
    use Queueable;

    public $startup;
    public $reason;

    /**
     * Create a new notification instance.
     */
    public function __construct($startup, $reason = null)
    {
        $this->startup = $startup;
        $this->reason = $reason ?? 'It does not meet our current listing criteria.';
    }

    /**
     * Get the notification's delivery channels.
     */
    public function via($notifiable)
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     */
    public function toDatabase($notifiable)
    {
        return [
            'title' => 'Startup Application Rejected',
            'message' => "Your startup '{$this->startup->title}' was rejected. Reason: {$this->reason}",
            'icon' => 'x-circle',
            'link' => '#'
        ];
    }
}
