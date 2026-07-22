<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class WelcomeNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function via($notifiable): array
    {
        return ['mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Selamat Datang di Laravel LMS Platform!')
            ->greeting("Halo, {$notifiable->name}!")
            ->line('Terima kasih sudah mendaftar. Akun Anda sudah aktif dan siap dipakai.')
            ->line('Yuk mulai jelajahi course yang tersedia dan tingkatkan skill Anda.')
            ->action('Jelajahi Course', rtrim(env('FRONTEND_URL', 'http://localhost:5173'), '/') . '/courses')
            ->line('Selamat belajar!');
    }
}