<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ForgotPasswordEmail extends Notification
{
    use Queueable;

    public function __construct(
        protected string $token
    )
    {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $url = url("/auth/reset-password/{$this->token}?email={$notifiable->email}");

        return (new MailMessage)
            ->subject('Restablece tu contraseña.')
            ->greeting('Hola')
            ->line('Recibimos una solicitud para restablecer tu contraseña')
            ->action('Reestablecer Contraseña', $url)
            ->line('Si no solicitaste esto, puedes ignorar este mensaje.')
            ->salutation('Saludos, CashTrackr');
    }
}
