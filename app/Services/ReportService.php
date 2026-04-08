<?php

namespace App\Services;

use App\Events\ReportCreated;
use App\Events\ReportStatusChanged;
use App\Models\Report;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;

class ReportService
{
    /**
     * Create verified report directly (Called ONLY after Magic Link is clicked).
     */
    public function createVerified(array $data): Report
    {
        return DB::transaction(function () use ($data) {
            // Generate unique tracking code BARU ketik link diklik (karena dimasukkan ke DB baru sekarang)
            $code = $this->generateTrackingCode();

            $reportData = array_merge($data, ['tracking_code' => $code, 'status' => Report::STATUS_BARU]);
            
            // Auto-assign a teacher from the same school if available
            if (!empty($data['nama_sekolah'])) {
                $teacher = User::where('role', 'teacher')
                    ->where('school', $data['nama_sekolah'])
                    ->where('is_active', true)
                    ->where('approval_status', 'approved')
                    ->inRandomOrder()
                    ->first();
                
                if ($teacher) {
                    $reportData['guru_id'] = $teacher->id;
                }
            }

            $report = Report::create($reportData);

            event(new ReportCreated($report));

            // Langsung kirim notif success ke murid & guru karena sudah terverifikasi!
            $this->sendSuccessNotification($report);

            return $report;
        });
    }

    /**
     * Old create method kept for backward compatibility if needed elsewhere
     */
    public function create(array $data): Report
    {
        return $this->createVerified($data);
    }

    /**
     * Send tracking code email to student (Now behaves as a MAGIC LINK, no Report dependency)
     */
    public function sendVerificationEmailOnly(array $data, string $token): void
    {
        try {
            // Generate signed URL valid for 15 minutes
            $verificationUrl = URL::signedRoute('report.verify', ['token' => $token], now()->addMinutes(15));

            $emailBody = "Halo {$data['nama_murid']}! Klik link ini buat verifikasi laporan kamu ya: {$verificationUrl}. Link ini cuma aktif 15 menit. Yuk, segera diverifikasi!";

            Mail::raw($emailBody, function ($message) use ($data) {
                $message->to($data['email_murid'])
                    ->subject('Verifikasi Laporan BK Anda');
            });
        } catch (\Exception $e) {
            Log::error('Failed to send verification email: ' . $e->getMessage());
        }
    }

    /**
     * Send SUCCESS notification with Tracking Code after verification
     */
    public function sendSuccessNotification(Report $report): void
    {
        try {
            $emailBody = "Selamat!\nLaporan kamu sudah berhasil diverifikasi dan masuk ke sistem kami.\n\n";
            $emailBody .= "Berikut adalah detail laporan kamu:\n";
            $emailBody .= "- Nama Pelapor: {$report->nama_murid}\n";
            $emailBody .= "- Email: {$report->email_murid}\n";
            $emailBody .= "- Sekolah: {$report->nama_sekolah}\n";
            $emailBody .= "- Kelas: {$report->kelas}\n";
            $emailBody .= "- Jenis Laporan: {$report->jenis_laporan}\n\n";
            $emailBody .= "KODE TRACKING / UNIK KAMU: {$report->tracking_code}\n\n";
            $emailBody .= "Mohon simpan kode ini baik-baik ya untuk memantau status atau melakukan konsultasi bersama guru BK!\n";

            Mail::raw($emailBody, function ($message) use ($report) {
                $message->to($report->email_murid)
                    ->subject('Laporan Diterima! Ini Kode Unik Kamu');
            });

            // CATATAN: Notifikasi ke guru sudah ditangani oleh Event Listener
            // NotifyTeachersOfNewReport yang dipanggil via event(new ReportCreated($report))
            // di createVerified(). Jangan panggil sendReportNotificationToTeachers() di sini
            // karena akan mengakibatkan guru menerima 2 email untuk 1 laporan yang sama.

        } catch (\Exception $e) {
            Log::error('Failed to send success email: ' . $e->getMessage());
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
            $admins = User::where('role', 'admin')->where('is_active', true)->get();

            if ($admins->isEmpty()) {
                Log::warning('No admin found to send report notification');
                return;
            }

            // Get teacher name if report is handled by a teacher
            $teacherName = 'Sistem';
            if ($report->guru_id) {
                $teacher = User::find($report->guru_id);
                if ($teacher) {
                    $teacherName = $teacher->name;
                }
            } elseif (Auth::check()) {
                /** @var \App\Models\User|null $authUser */
                $authUser = Auth::user();
                $teacherName = $authUser->name;
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
                    Log::info("Report notification sent to admin: {$admin->email}, report: {$report->id}");
                } else {
                    Log::error("Failed to send report notification to admin: {$admin->email}, report: {$report->id}");
                }
            }
        } catch (\Exception $e) {
            Log::error('Error sending report notification: ' . $e->getMessage());
        }
    }

    /**
     * Send report notification email to teachers in the same school
     */
    protected function sendReportNotificationToTeachers(Report $report): void
    {
        try {
            // Get all active teachers from the exact SAME SCHOOL
            $teachers = User::where('role', 'teacher')
                ->where('school', $report->nama_sekolah)
                ->where('is_active', true)
                ->get();

            if ($teachers->isEmpty()) {
                Log::warning("No teachers found in school: {$report->nama_sekolah}, report: {$report->id}");
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

                    Log::info("Report notification sent to teacher: {$teacher->email}, report: {$report->id}");
                } catch (\Exception $e) {
                    Log::error("Failed to send email to teacher {$teacher->email}: " . $e->getMessage());
                }
            }
        } catch (\Exception $e) {
            Log::error('Error sending report notification to teachers: ' . $e->getMessage());
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

            Log::info("Status change email sent to student: {$report->email_murid}, report: {$report->id}");
        } catch (\Exception $e) {
            Log::error('Failed to send status change email: ' . $e->getMessage());
        }
    }
}
