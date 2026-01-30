<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class UserNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public $data;

    /**
     * Create a new notification instance.
     */
    public function __construct($data)
    {
        $this->data = $data;
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
            ->subject('AskDev - ' . ucfirst(str_replace('_', ' ', $this->data['type'])))
            ->greeting('Hello ' . $notifiable->name . '!')
            ->line($this->data['message']);

        if (!empty($this->data['link'])) {
            $mailMessage->action('View Details', url($this->data['link']));
        }

        return $mailMessage->line('Thank you for using AskDev!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'type' => $this->data['type'],
            'message' => $this->data['message'],
            'link' => $this->data['link'] ?? null,
            'user_name' => $this->data['user_name'] ?? 'System', // Sender name
            'created_at' => now(),
        ];
    }
}
