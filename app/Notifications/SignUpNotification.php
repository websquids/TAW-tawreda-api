<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SignUpNotification extends Notification {
  use Queueable;

  /**
   * Create a new notification instance.
   */
  public function __construct() {
        //
  }

  /**
   * Get the notification's delivery channels.
   *
   * @return array<int, string>
   */
  public function via(object $notifiable): array {
    return ['mail'];
  }

  /**
   * Get the mail representation of the notification.
   */
  public function toMail(object $notifiable): MailMessage {
    return (new MailMessage)
        ->subject('Welcome to ' . env('APP_NAME'))
        ->line('Hi' . ' ' . $notifiable->name . ' ,')
        ->line('Welcome to ' . env('APP_NAME') . ". We're thrilled to see you here!")
        ->action('Start', config('app.frontend_url'));
  }

  /**
   * Get the array representation of the notification.
   *
   * @return array<string, mixed>
   */
  public function toArray(object $notifiable): array {
    return [
      //
    ];
  }
}
