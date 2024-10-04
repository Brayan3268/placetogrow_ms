<?php

namespace App\Notifications;

use App\Models\Site;
use App\Models\Usersuscription;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TestNotification extends Notification
{
    use Queueable;

    public function __construct(
        private Usersuscription $user_suscription,
        private Site $site,
        private string $type_notice
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        dump($this->user_suscription);
        dump($this->site);
        dump($this->type_notice);

        return (new MailMessage)->view('notifications.testnoti', [
            'user' => $notifiable,
            'user_suscription' => $this->user_suscription,
            'site' => $this->site,
            'notice' => $this->type_notice,
        ]);
    }

    public function toArray(object $notifiable): array
    {
        return [
            'user_id' => $notifiable->id,
            'user_email' => $notifiable->email,
            'user_suscription_reference' => $this->user_suscription->reference,
        ];
    }
}
