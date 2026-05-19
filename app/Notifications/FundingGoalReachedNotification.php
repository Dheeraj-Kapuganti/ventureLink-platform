<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class FundingGoalReachedNotification extends Notification
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
            'title' => 'Funding Goal Reached!',
            'message' => "Congratulations! '{$this->startup->title}' has reached its funding goal of $" . number_format($this->startup->funding_goal) . ".",
            'icon' => 'award',
            'link' => '#'
        ];
    }
}
