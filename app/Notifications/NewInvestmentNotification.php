<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class NewInvestmentNotification extends Notification
{
    use Queueable;

    public $investment;
    public $startup;

    public function __construct($investment, $startup)
    {
        $this->investment = $investment;
        $this->startup = $startup;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toDatabase($notifiable)
    {
        return [
            'title' => 'New Investment Received!',
            'message' => "You have received an investment of $" . number_format($this->investment->amount) . " in '{$this->startup->title}'.",
            'icon' => 'trending-up',
            'link' => '#' // Usually points to the founder's dashboard
        ];
    }
}
