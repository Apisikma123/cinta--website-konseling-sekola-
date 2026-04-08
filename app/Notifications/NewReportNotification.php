<?php

namespace App\Notifications;

use App\Models\Report;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewReportNotification extends Notification implements ShouldQueue
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
            ->subject('Laporan Baru Masuk')
            ->line('Terdapat laporan baru dengan kode: '.$this->report->tracking_code)
            ->action('Lihat Laporan', url('/admin/reports/'.$this->report->id))
            ->line('Silakan masuk untuk mengambil tindakan.');
    }

    public function toArray($notifiable)
    {
        return [
            'report_id' => $this->report->id,
            'tracking_code' => $this->report->tracking_code,
            'title' => $this->report->title ?? null,
        ];
    }
}