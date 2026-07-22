<?php

namespace App\Notifications;

use App\Models\Payment;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PaymentSuccessNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(public Payment $payment) {}

    public function via($notifiable): array
    {
        return ['mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        $subscription = $this->payment->payable;
        $plan = $subscription->plan;

        return (new MailMessage)
            ->subject('Pembayaran Berhasil - Laravel LMS Platform')
            ->greeting("Halo, {$notifiable->name}!")
            ->line('Pembayaran Anda telah berhasil dikonfirmasi. Berikut rinciannya:')
            ->line("Paket: {$plan->name}")
            ->line('Jumlah: Rp ' . number_format((float) $this->payment->amount, 0, ',', '.'))
            ->line('Metode: ' . strtoupper($this->payment->payment_method ?? '-'))
            ->line('Berlaku hingga: ' . Carbon::parse($subscription->end_date)->format('d F Y'))
            ->action('Buka Dashboard', rtrim(env('FRONTEND_URL', 'http://localhost:5173'), '/') . '/dashboard')
            ->line('Selamat belajar!');
    }
}