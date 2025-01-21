<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Firebase\Messaging\Notification as FirebaseNotification;

class FirebasePushNotification extends Notification
{
    use Queueable;
    protected $title;
    protected $body;
    protected $data;
    protected $token;
    /**
     * Create a new notification instance.
     */
    public function __construct(string $title, string $body, array $data, string $token)
    {
        $this->title = $title;
        $this->body = $body;
        $this->data = $data;
        $this->token = $token;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['firebase'];
    }

    public function toFirebase($notifiable)
    {
        return CloudMessage::withTarget('token', $this->token)
            ->withNotification(FirebaseNotification::create($this->title, $this->body))
            ->withData($this->data);
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
