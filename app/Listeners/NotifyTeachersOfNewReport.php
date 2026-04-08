<?php

namespace App\Listeners;

use App\Events\ReportCreated;
use App\Notifications\NewReportNotification;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Notification;

class NotifyTeachersOfNewReport implements ShouldQueue
{
    use InteractsWithQueue;

    public function handle(ReportCreated $event)
    {
        $report = $event->report;

        // select approved teachers only (approval_status must be 'approved'), filtered by school name
        $teachers = User::where('role', 'teacher')
            ->where('approval_status', 'approved')
            ->where('school', $report->nama_sekolah)
            ->where('is_active', true)  // Also ensure teachers are active
            ->get();

        if ($teachers->isEmpty()) {
            // still attempt to notify the student if present
        }

        try {
            if (! $teachers->isEmpty()) {
                Notification::send($teachers, new NewReportNotification($report));
            }

            // CATATAN: Email ke murid (email_murid) sudah dikirim oleh
            // ReportService::sendSuccessNotification() setelah laporan dibuat.
            // Jangan kirim ulang di sini untuk mencegah duplikasi.

        } catch (\Throwable $e) {
            // don't block report creation if notification fails
            logger()->warning('Failed to send report notifications', [
                'report_id' => $report->id,
                'error' => $e->getMessage(),
            ]);
        }
    }
}