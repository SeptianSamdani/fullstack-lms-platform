<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\ResetPassword as ResetPasswordBase;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class ResetPasswordNotification extends ResetPasswordBase implements ShouldQueue
{
    use Queueable;

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Reset Password - Laravel LMS Platform')
            ->greeting("Halo, {$notifiable->name}!")
            ->line('Kami menerima permintaan untuk mereset password akun Anda.')
            ->action('Reset Password', $this->resetUrl($notifiable))
            ->line('Link ini akan kadaluwarsa dalam 60 menit.')
            ->line('Kalau Anda tidak meminta reset password, abaikan email ini — password Anda tidak akan berubah.');
    }
}