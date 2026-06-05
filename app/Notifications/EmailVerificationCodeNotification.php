<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class EmailVerificationCodeNotification extends Notification
{
    use Queueable;

    public function __construct(
        private readonly string $code,
        private readonly int $minutes = 10
    ) {
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Your Verification Code')
            ->greeting('Hello ' . ($notifiable->name ?: 'there') . ',')
            ->line('Use the verification code below to finish your signup.')
            ->line('Verification code: ' . $this->code)
            ->line('This code expires in ' . $this->minutes . ' minutes.')
            ->line('If you did not create an account, you can ignore this email.');
    }
}
