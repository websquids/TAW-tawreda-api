<?php

namespace App\Broadcasting;

use App\Notifications\FirebasePushNotification;

class FirebaseChannel
{
    protected $messaging;

    public function __construct()
    {
        $this->messaging = (new Factory())
            ->withServiceAccount(config('firebase.credentials.file'))
            ->createMessaging();
    }

    public function send($notifiable, FirebasePushNotification $notification)
    {
        $message = $notification->toFirebase($notifiable);
        $this->messaging->send($message);
    }
}
