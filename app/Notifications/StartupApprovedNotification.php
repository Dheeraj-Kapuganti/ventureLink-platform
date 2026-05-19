<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class StartupApprovedNotification extends Notification
{
    use Queueable;

    public $startup;

    public function __construct($startup)
    {
        $this->startup = $startup;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toDatabase($notifiable)
    {
        return [
            'title' => 'Startup Approved',
            'message' => "Your startup '{$this->startup->title}' has been approved and is now live for investors!",
            'icon' => 'check-circle',
            'link' => '#'
        ];
    }
}
