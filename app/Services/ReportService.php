<?php

namespace App\Services;

use App\Events\ReportCreated;
use App\Events\ReportStatusChanged;
use App\Models\Report;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class ReportService
{
    /**
     * Create a report and fire ReportCreated event.
     */
    public function create(array $data): Report
    {
        return DB::transaction(function () use ($data) {
            // generate unique tracking code
            $code = $this->generateTrackingCode();

            $report = Report::create(array_merge($data, ['tracking_code' => $code, 'status' => Report::STATUS_BARU]));

            event(new ReportCreated($report));

            // Send email to student if email provided
            if (!empty($data['email_murid'])) {
                $this->sendTrackingCodeEmail($report, $data);
            }

            // Send email notification to teachers in the same school
            $this->sendReportNotificationToTeachers($report);

            // Send email notification to admin
            $this->sendReportNotificationToAdmin($report, 'created');

            return $report;
        });
    }

    /**
     * Send tracking code email to student
     */
    protected function sendTrackingCodeEmail(Report $report, array $data): void
    {
        try {
            $emailBody = "Halo {$data['nama_murid']},\n\n";
            $emailBody .= "Laporan Anda telah berhasil diterima oleh sistem.\n\n";
            $emailBody .= "Kode Tracking: {$report->tracking_code}\n";
            $emailBody .= "Sekolah: {$data['nama_sekolah']}\n";
            $emailBody .= "Tanggal: " . now()->format('d M Y H:i') . "\n\n";
            $emailBody .= "Gunakan kode tracking ini untuk memantau status laporan Anda.\n";
            $emailBody .= "Kunjungi: " . route('result', $report->tracking_code) . "\n\n";
            $emailBody .= "Terima kasih,\nSistem Laporan BK";

            Mail::raw($emailBody, function ($message) use ($data, $report) {
                $message->to($data['email_murid'])
                    ->subject('Kode Tracking Laporan BK - ' . $report->tracking_code);
            });
        } catch (\Exception $e) {
            \Log::error('Failed to send tracking code email: ' . $e->getMessage());
        }
    }

    protected function generateTrackingCode(): string
    {
        do {
            $code = strtoupper(Str::random(8));
        } while (Report::where('tracking_code', $code)->exists());

        return $code;
    }

    public function changeStatus(Report $report, string $newStatus, ?int $guruId = null): Report
    {
        return DB::transaction(function () use ($report, $newStatus, $guruId) {
            $old = $report->status;

            $report->status = $newStatus;
            if ($guruId) {
                $report->guru_id = $guruId;
            }
            $report->save();

            event(new ReportStatusChanged($report, $old, $newStatus));

            // Send email notification to student about status change
            if (!empty($report->email_murid)) {
                $this->sendStatusChangeEmail($report, $old, $newStatus);
            }

            // Send email notification to admin
            $this->sendReportNotificationToAdmin($report, 'updated');

            return $report;
        });
    }

    /**
     * Send report notification email to admin using Resend
     */
    protected function sendReportNotificationToAdmin(Report $report, string $action): void
    {
        try {
            $resendService = new ResendService();

            // Get admin emails (users with role 'admin')
            $admins = \App\Models\User::where('role', 'admin')->where('is_active', true)->get();

            if ($admins->isEmpty()) {
                \Log::warning('No admin found to send report notification');
                return;
            }

            // Get teacher name if report is handled by a teacher
            $teacherName = 'Sistem';
            if ($report->guru_id) {
                $teacher = \App\Models\User::find($report->guru_id);
                if ($teacher) {
                    $teacherName = $teacher->name;
                }
            } elseif (auth()->check()) {
                $teacherName = auth()->user()->name;
            }

            // Send email to each admin
            foreach ($admins as $admin) {
                $sent = $resendService->sendReportNotification(
                    $admin->email,
                    $teacherName,
                    $report->title,
                    $action
                );

                if ($sent) {
                    \Log::info("Report notification sent to admin: {$admin->email}, report: {$report->id}");
                } else {
                    \Log::error("Failed to send report notification to admin: {$admin->email}, report: {$report->id}");
                }
            }
        } catch (\Exception $e) {
            \Log::error('Error sending report notification: ' . $e->getMessage());
        }
    }

    /**
     * Send report notification email to teachers in the same school
     */
    protected function sendReportNotificationToTeachers(Report $report): void
    {
        try {
            // Get all active teachers from the same school
            $teachers = \App\Models\User::where('role', 'teacher')
                ->where('school', $report->school->name ?? '')
                ->where('is_active', true)
                ->get();

            if ($teachers->isEmpty()) {
                \Log::warning("No teachers found in school: {$report->nama_sekolah}, report: {$report->id}");
                return;
            }

            // Send email to each teacher
            foreach ($teachers as $teacher) {
                try {
                    $emailBody = "Halo {$teacher->name},\n\n";
                    $emailBody .= "Ada laporan baru dari siswa di sekolah {$report->nama_sekolah}.\n\n";
                    $emailBody .= "Detail Laporan:\n";
                    $emailBody .= "Kode Tracking: {$report->tracking_code}\n";
                    $emailBody .= "Nama Siswa: {$report->nama_murid}\n";
                    $emailBody .= "Kelas: {$report->kelas}\n";
                    $emailBody .= "Judul: {$report->title}\n";
                    $emailBody .= "Jenis Masalah: {$report->jenis_laporan}\n";
                    $emailBody .= "Status: {$report->status}\n";
                    $emailBody .= "Tanggal: " . $report->created_at->format('d M Y H:i') . "\n\n";
                    $emailBody .= "Silakan login ke sistem untuk melihat detail lengkap laporan.\n";
                    $emailBody .= "Link: " . route('teacher.reports.show', $report->id) . "\n\n";
                    $emailBody .= "Terima kasih,\nSistem Laporan BK";

                    Mail::raw($emailBody, function ($message) use ($teacher, $report) {
                        $message->to($teacher->email)
                            ->subject("Laporan Baru dari Siswa - {$report->tracking_code}");
                    });

                    \Log::info("Report notification sent to teacher: {$teacher->email}, report: {$report->id}");
                } catch (\Exception $e) {
                    \Log::error("Failed to send email to teacher {$teacher->email}: " . $e->getMessage());
                }
            }
        } catch (\Exception $e) {
            \Log::error('Error sending report notification to teachers: ' . $e->getMessage());
        }
    }

    /**
     * Send status change email to student
     */
    protected function sendStatusChangeEmail(Report $report, string $oldStatus, string $newStatus): void
    {
        try {
            $statusLabels = [
                'baru' => 'Baru Diterima',
                'diproses' => 'Sedang Diproses',
                'selesai' => 'Selesai'
            ];

            $oldStatusLabel = $statusLabels[$oldStatus] ?? $oldStatus;
            $newStatusLabel = $statusLabels[$newStatus] ?? $newStatus;

            $emailBody = "Halo {$report->nama_murid},\n\n";
            $emailBody .= "Status laporan Anda telah berubah!\n\n";
            $emailBody .= "Kode Tracking: {$report->tracking_code}\n";
            $emailBody .= "Status Sebelumnya: {$oldStatusLabel}\n";
            $emailBody .= "Status Baru: {$newStatusLabel}\n";
            $emailBody .= "Waktu Perubahan: " . now()->format('d M Y H:i') . "\n\n";
            
            if ($newStatus === 'diproses') {
                $emailBody .= "Laporan Anda sedang ditangani oleh guru BK Anda.\n";
            } elseif ($newStatus === 'selesai') {
                $emailBody .= "Laporan Anda telah selesai ditangani. Silakan login untuk melihat hasil penanganan.\n";
            }
            
            $emailBody .= "\nKunjungi: " . route('result', $report->tracking_code) . "\n\n";
            $emailBody .= "Terima kasih,\nSistem Laporan BK";

            Mail::raw($emailBody, function ($message) use ($report) {
                $message->to($report->email_murid)
                    ->subject("Update Status Laporan BK - {$report->tracking_code}");
            });

            \Log::info("Status change email sent to student: {$report->email_murid}, report: {$report->id}");
        } catch (\Exception $e) {
            \Log::error('Failed to send status change email: ' . $e->getMessage());
        }
    }
}
