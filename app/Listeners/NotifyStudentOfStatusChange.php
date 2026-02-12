<?php

namespace App\Listeners;

use App\Events\ReportStatusChanged;
use App\Notifications\ReportStatusNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Notification;

class NotifyStudentOfStatusChange implements ShouldQueue
{
    use InteractsWithQueue;

    public function handle(ReportStatusChanged $event)
    {
        $report = $event->report;

        if (empty($report->email_murid)) {
            return;
        }

        Notification::route('mail', $report->email_murid)
            ->notify(new ReportStatusNotification($report, $event->oldStatus, $event->newStatus));
    }
}