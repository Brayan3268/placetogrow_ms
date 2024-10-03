<?php

namespace App\Notifications;

use App\Models\Payment;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class LoseSessionNotification extends Notification
{
    use Queueable;

    public function __construct(private Payment $payment) {}

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)->view('notifications.losesession', [
            'user' => $notifiable,
            'payment' => $this->payment,
        ]);
    }

    public function toArray(object $notifiable): array
    {
        return [
            'user_id' => $notifiable->id,
            'user_email' => $notifiable->email,
            'payment_reference' => $this->payment->reference,
        ];
    }
}
