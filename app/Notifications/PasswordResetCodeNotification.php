<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PasswordResetCodeNotification extends Notification
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
            ->subject('Password Reset Verification Code')
            ->greeting('Hello ' . ($notifiable->name ?: 'there') . ',')
            ->line('You are receiving this email because we received a password reset request for your account.')
            ->line('Use the 6-digit verification code below to reset your password:')
            ->line('Verification code: ' . $this->code)
            ->line('This code expires in ' . $this->minutes . ' minutes.')
            ->line('If you did not request a password reset, no further action is required.');
    }
}
