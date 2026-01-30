<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AdminActivityNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public $activityData;

    /**
     * Create a new notification instance.
     */
    public function __construct($activityData)
    {
        $this->activityData = $activityData;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $mailMessage = (new MailMessage)
            ->subject('AskDev Admin - New Activity')
            ->greeting('Hello Admin!')
            ->line($this->activityData['message'])
            ->line('Activity by: ' . $this->activityData['user_name']);

        if (!empty($this->activityData['link'])) {
            $mailMessage->action('View Details', url($this->activityData['link']));
        }

        return $mailMessage->line('Thank you for managing AskDev!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'type' => $this->activityData['type'],
            'message' => $this->activityData['message'],
            'user_id' => $this->activityData['user_id'],
            'user_name' => $this->activityData['user_name'],
            'link' => $this->activityData['link'] ?? null,
            'created_at' => now(),
        ];
    }
}
