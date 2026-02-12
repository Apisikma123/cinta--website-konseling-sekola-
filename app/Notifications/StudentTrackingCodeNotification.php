<?php

namespace App\Notifications;

use App\Models\Report;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class StudentTrackingCodeNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(protected Report $report)
    {
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Kode Tracking Laporan BK - ' . $this->report->tracking_code)
            ->greeting('Halo ' . $this->report->nama_murid . ',')
            ->line('Laporan Anda telah berhasil diterima oleh sistem.')
            ->line('Kode Tracking: ' . $this->report->tracking_code)
            ->line('Sekolah: ' . $this->report->nama_sekolah)
            ->line('Tanggal: ' . now()->format('d M Y H:i'))
            ->action('Pantau Laporan', route('result', $this->report->tracking_code))
            ->line('Gunakan kode tracking ini untuk memantau status laporan Anda.')
            ->line('Terima kasih telah melapor.');
    }
}
