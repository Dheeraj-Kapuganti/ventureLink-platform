<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class NewCommentNotification extends Notification
{
    use Queueable;

    public $startup;
    public $commenterName;
    public $commentBody;
    public $isReply;

    /**
     * Create a new notification instance.
     */
    public function __construct($startup, $commenterName, $commentBody = '', $isReply = false)
    {
        $this->startup = $startup;
        $this->commenterName = $commenterName;
        $this->commentBody = $commentBody;
        $this->isReply = $isReply;
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
        $title = $this->isReply ? 'New Reply Received' : 'New Comment Received';
        
        $truncatedBody = strlen($this->commentBody) > 60 ? substr($this->commentBody, 0, 57) . '...' : $this->commentBody;
        $message = "{$this->commenterName} wrote: \"{$truncatedBody}\" on '{$this->startup->title}'";

        // Determine link dynamically based on the receiving user's role
        $link = '#';
        if ($notifiable->role === 'founder') {
            $link = route('startups.show', $this->startup->id);
        } elseif ($notifiable->role === 'admin') {
            $link = route('admin.startups.show', $this->startup->id);
        } else {
            $link = route('investor.startups.show', $this->startup->id);
        }

        return [
            'title' => $title,
            'message' => $message,
            'comment_body' => $this->commentBody,
            'icon' => 'message-square',
            'link' => $link
        ];
    }
}
