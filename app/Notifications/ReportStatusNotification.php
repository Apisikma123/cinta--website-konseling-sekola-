<?php

namespace App\Notifications;

use App\Models\Report;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ReportStatusNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(protected Report $report, protected string $oldStatus, protected string $newStatus)
    {
    }

    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Status Laporan Anda: '.$this->newStatus)
            ->line('Status laporan dengan kode '.$this->report->tracking_code.' telah berubah dari '.$this->oldStatus.' menjadi '.$this->newStatus)
            ->action('Lihat Tracking', url('/tracking/'.$this->report->tracking_code))
            ->line('Terima kasih.');
    }

    public function toArray($notifiable)
    {
        return [
            'report_id' => $this->report->id,
            'tracking_code' => $this->report->tracking_code,
            'old' => $this->oldStatus,
            'new' => $this->newStatus,
        ];
    }
}