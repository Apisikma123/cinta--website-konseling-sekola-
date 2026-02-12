<?php

namespace App\Services;

use App\Models\OtpVerification;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;

class OtpService
{
    private ResendService $resendService;

    public function __construct()
    {
        $this->resendService = new ResendService();
    }

    /**
     * Generate OTP untuk email
     *
     * @param string $email Email tujuan
     * @param int|null $userId ID user (opsional)
     * @param bool $force Force generate meskipun masih dalam cooldown
     * @return OtpVerification
     * @throws \Exception
     */
    public function generateForEmail(string $email, ?int $userId = null, bool $force = false): OtpVerification
    {
        $now = Carbon::now();

        // Check for existing valid OTP
        $existing = OtpVerification::where('email', $email)
            ->where('is_used', false)
            ->where('expires_at', '>', $now)
            ->latest()
            ->first();

        if ($existing) {
            // Hitung selisih waktu sejak OTP dibuat
            $secondsSince = $now->getTimestamp() - $existing->created_at->getTimestamp();
            if ($secondsSince < 0) {
                // Jika created_at di masa depan (anomali), log dan clamp ke 0
                Log::warning("OTP created_at is in the future for {$email}. created_at={$existing->created_at} now={$now}");
                $secondsSince = 0;
            }

            // Enforce 1 minute cooldown antara pengiriman
            if (!$force && $secondsSince < 60) {
                $retry = max(1, 60 - $secondsSince);
                throw new \Exception('Tunggu beberapa saat sebelum meminta kode lagi.', $retry);
            }

            // Mark previous OTP as used untuk menghindari multiple valid codes
            $existing->is_used = true;
            $existing->save();
        }

        // Generate 6 digit OTP
        $code = random_int(100000, 999999);

        // Create OTP record
        $otp = OtpVerification::create([
            'user_id' => $userId,
            'email' => $email,
            'otp_code' => (string) $code,
            'expires_at' => $now->addMinutes(5), // OTP berlaku 5 menit
        ]);

        // Kirim OTP via email
        $sent = $this->sendOtpEmail($email, $code);

        if ($sent) {
            Log::info("OTP sent successfully for {$email}, otp_id={$otp->id}");
        } else {
            Log::error("Failed to send OTP email for {$email}, otp_id={$otp->id}");
            // Tetap return OTP meskipun email gagal, agar bisa retry
        }

        return $otp;
    }

    /**
     * Kirim OTP via email
     * Menggunakan Resend API sebagai primary, Laravel Mail sebagai fallback
     *
     * @param string $to Email tujuan
     * @param string $code Kode OTP
     * @return bool
     */
    private function sendOtpEmail(string $to, string $code): bool
    {
        $subject = 'Kode OTP - Sistem Cinta';
        $expiresInMinutes = 5;

        // Coba kirim via Resend API dulu
        try {
            $sent = $this->resendService->sendOtp($to, $code, $expiresInMinutes);
            if ($sent) {
                return true;
            }
        } catch (\Exception $e) {
            Log::warning("Resend API failed for {$to}: " . $e->getMessage());
        }

        // Fallback ke Laravel Mail (SMTP)
        try {
            $text = "Kode OTP Anda: {$code}\n\nKode ini berlaku selama {$expiresInMinutes} menit.\nJika Anda tidak meminta kode ini, abaikan email ini.\n\nSistem Cinta";

            Mail::raw($text, function ($message) use ($to, $subject) {
                $message->to($to)
                    ->subject($subject);
            });

            Log::info("OTP sent via Laravel Mail for {$to}");
            return true;
        } catch (\Exception $e) {
            Log::error("Laravel Mail failed for {$to}: " . $e->getMessage());
        }

        return false;
    }

    /**
     * Verifikasi OTP
     *
     * @param string $email Email yang diverifikasi
     * @param string $code Kode OTP
     * @return bool True jika valid, false jika tidak
     */
    public function verify(string $email, string $code): bool
    {
        $otp = OtpVerification::where('email', $email)
            ->where('otp_code', $code)
            ->where('is_used', false)
            ->first();

        if (!$otp) {
            Log::warning("OTP verification failed: code not found for {$email}");
            return false;
        }

        // Check if OTP has expired
        if (Carbon::now()->greaterThan($otp->expires_at)) {
            Log::warning("OTP verification failed: code expired for {$email}");
            return false;
        }

        // Mark as used
        $otp->is_used = true;
        $otp->save();

        Log::info("OTP verified successfully for {$email}");
        return true;
    }

    /**
     * Check if OTP is expired
     *
     * @param OtpVerification $otp
     * @return bool
     */
    public function isExpired(OtpVerification $otp): bool
    {
        return Carbon::now()->greaterThan($otp->expires_at);
    }

    /**
     * Get remaining time for OTP in seconds
     *
     * @param string $email
     * @return int|null Seconds remaining, null if no valid OTP
     */
    public function getRemainingTime(string $email): ?int
    {
        $otp = OtpVerification::where('email', $email)
            ->where('is_used', false)
            ->where('expires_at', '>', Carbon::now())
            ->latest()
            ->first();

        if (!$otp) {
            return null;
        }

        return max(0, Carbon::now()->diffInSeconds($otp->expires_at, false));
    }

    /**
     * Get retry after time in seconds
     *
     * @param string $email
     * @return int|null Seconds until can resend, null if can resend now
     */
    public function getRetryAfter(string $email): ?int
    {
        $existing = OtpVerification::where('email', $email)
            ->where('is_used', false)
            ->latest()
            ->first();

        if (!$existing) {
            return null;
        }

        $secondsSince = Carbon::now()->getTimestamp() - $existing->created_at->getTimestamp();
        if ($secondsSince >= 60) {
            return null;
        }

        return 60 - $secondsSince;
    }

    /**
     * Send report notification to admin
     *
     * @param string $adminEmail
     * @param string $teacherName
     * @param string $reportTitle
     * @param string $action
     * @return bool
     */
    public function sendReportNotification(string $adminEmail, string $teacherName, string $reportTitle, string $action = 'created'): bool
    {
        return $this->resendService->sendReportNotification($adminEmail, $teacherName, $reportTitle, $action);
    }
}
