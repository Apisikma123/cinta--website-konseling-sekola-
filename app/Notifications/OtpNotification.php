<?php

namespace App\Notifications;

use App\Models\OtpVerification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OtpNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(protected OtpVerification $otp)
    {
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        $code = $this->otp->otp_code;

        return (new MailMessage)
            ->subject('Kode OTP Login')
            ->line("Kode OTP Anda: {$code}")
            ->line('Kode ini berlaku selama 5 menit.')
            ->line('Jika Anda tidak meminta kode ini, abaikan email ini.');
    }
}
