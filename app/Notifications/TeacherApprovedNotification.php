<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TeacherApprovedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(protected User $user)
    {
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Akun Guru Anda Disetujui')
            ->greeting('Halo '.$this->user->name)
            ->line('Akun guru Anda telah disetujui oleh admin. Anda sekarang dapat login dan mengakses dashboard guru.')
            ->action('Login', url('/login'));
    }
}