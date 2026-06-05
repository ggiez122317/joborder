<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class LoginCodeNotification extends Notification
{
    use Queueable;

    public function __construct(
        private readonly string $code,
        private readonly int $minutes = 5
    ) {
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Your Login Verification Code')
            ->greeting('Hello ' . ($notifiable->name ?: 'there') . ',')
            ->line('Use the 6-digit verification code below to complete your login.')
            ->line('Verification code: ' . $this->code)
            ->line('This code expires in ' . $this->minutes . ' minutes.')
            ->line('If you did not attempt to log in, please ignore this email.');
    }
}
