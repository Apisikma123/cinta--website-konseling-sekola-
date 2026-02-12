<?php

namespace App\Listeners;

use App\Events\ReportCreated;
use App\Notifications\NewReportNotification;
use App\Notifications\StudentTrackingCodeNotification;
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

        // select approved teachers only, filtered by school name
        $teachers = User::where('role', 'teacher')
            ->where('is_approved', true)
            ->where('school', $report->nama_sekolah)
            ->get();

        if ($teachers->isEmpty()) {
            // still attempt to notify the student if present
        }

        try {
            if (! $teachers->isEmpty()) {
                Notification::send($teachers, new NewReportNotification($report));
            }

            // also notify the student (if email provided) about the created report
            if (! empty($report->email_murid)) {
                Notification::route('mail', $report->email_murid)
                    ->notify(new StudentTrackingCodeNotification($report));
            }
        } catch (\Throwable $e) {
            // don't block report creation if notification fails
            logger()->warning('Failed to send report notifications', [
                'report_id' => $report->id,
                'error' => $e->getMessage(),
            ]);
        }
    }
}