<?php

namespace App\Notifications;

use App\Models\Invoice;
use App\Models\Payment;
use App\Models\Usersuscription;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PayNotification extends Notification
{
    use Queueable;

    public function __construct(
        private Payment $payment,
        private string $status,
        private string $suscription_status,
        private Invoice|string $invoice,
        private Usersuscription|string $user_suscription
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)->view('notifications.pays', [
            'user' => $notifiable,
            'payment' => $this->payment,
            'invoice_status' => $this->status,
            'suscription_status' => $this->suscription_status,
            'invoice' => $this->invoice,
            'user_suscription' => $this->user_suscription,
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
