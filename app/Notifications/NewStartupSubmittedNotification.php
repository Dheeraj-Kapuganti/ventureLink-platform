<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class NewStartupSubmittedNotification extends Notification
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
            'title' => 'New Startup Submitted',
            'message' => "A new startup '{$this->startup->title}' has been submitted for approval by " . ($this->startup->founder->name ?? 'a founder') . ".",
            'icon' => 'rocket',
            'link' => '/admin-panel'
        ];
    }
}
