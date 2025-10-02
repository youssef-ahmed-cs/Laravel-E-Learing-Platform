<?php

namespace App\Notifications;

use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OtpNotification extends Notification
{
    public function __construct()
    {
    }

    public function via($notifiable): array
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->from(config('mail.from.address'), config('mail.from.name'))
            ->to($notifiable->email)
            ->subject('OTP Verification')
            ->greeting('Hello!')
            ->line("Your OTP verification code is: {$this->code}")
            ->line("Code expires at {$this->expiresAt}")
            ->line('Thank you for using our application!')
            ->salutation('Regards, E-Learning');
    }

    public function toArray($notifiable): array
    {
        return [];
    }
}
