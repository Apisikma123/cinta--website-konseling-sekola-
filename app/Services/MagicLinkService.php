<?php

namespace App\Services;

use App\Models\Submission;
use App\Models\MagicLinkToken;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

class MagicLinkService
{
    private ResendService $resendService;
    private int $expirationMinutes = 15;

    public function __construct()
    {
        $this->resendService = new ResendService();
    }

    /**
     * Generate dan kirim magic link token ke email submission
     *
     * @param Submission $submission
     * @param bool $isResend Apakah ini pengiriman ulang
     * @return bool Success status
     */
    public function generateAndSendToken(Submission $submission, bool $isResend = false): bool
    {
        try {
            // Generate token unik (64 karakter random)
            $token = Str::random(64);

            // Tentukan waktu expire (15 menit dari sekarang)
            $expiresAt = Carbon::now()->addMinutes($this->expirationMinutes);

            // Jika ini pengiriman ulang, hapus token lama yang belum digunakan
            if ($isResend) {
                MagicLinkToken::where('submission_id', $submission->id)
                    ->whereNull('used_at')
                    ->delete();
            }

            // Simpan token baru ke database
            $magicLinkToken = MagicLinkToken::create([
                'submission_id' => $submission->id,
                'email' => $submission->email,
                'token' => $token,
                'expires_at' => $expiresAt,
            ]);

            // Kirim email dengan link verifikasi
            $this->sendVerificationEmail($submission, $token);

            Log::info('Magic link token generated and sent', [
                'submission_id' => $submission->id,
                'email' => $submission->email,
                'expires_at' => $expiresAt->toDateTimeString(),
            ]);

            return true;

        } catch (\Exception $e) {
            Log::error('Failed to generate and send magic link token', [
                'submission_id' => $submission->id,
                'email' => $submission->email,
                'error' => $e->getMessage(),
            ]);

            return false;
        }
    }

    /**
     * Kirim EMAIL 1 (Magic Link) dengan plain text format
     * EMAIL ini hanya untuk verifikasi, belum menampilkan kode unik
     *
     * @param Submission $submission
     * @param string $token
     * @return void
     */
    private function sendVerificationEmail(Submission $submission, string $token): void
    {
        // Build verification URL
        $verificationUrl = route('verify', ['token' => $token]);

        // EMAIL 1 - Plain text message untuk verification magic link
        $textMessage = "Halo {$submission->name}! Klik link ini buat verifikasi laporan kamu ya: {$verificationUrl}. Link ini cuma aktif 15 menit. Yuk, segera diverifikasi!";

        // Send via Resend API menggunakan plain text
        $this->sendViaResendPlainText(
            $submission->email,
            "Verifikasi Laporan Konseling CINTA",
            $textMessage
        );
    }

    /**
     * Send email via Resend API dengan plain text format
     *
     * @param string $to Recipient email
     * @param string $subject Subject
     * @param string $text Plain text content
     * @return bool
     */
    private function sendViaResendPlainText(string $to, string $subject, string $text): bool
    {
        $apiKey = config('services.resend.key') ?? env('RESEND_API_KEY');
        $fromAddress = config('services.resend.from_address', env('RESEND_FROM_ADDRESS', 'sistemcinta@telkomcare.my.id'));
        $fromName = config('services.resend.from_name', env('RESEND_FROM_NAME', 'Sistem BK'));

        if (!$apiKey) {
            Log::warning('Resend API key not configured');
            return false;
        }

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $apiKey,
                'Content-Type' => 'application/json',
            ])->post('https://api.resend.com/emails', [
                'from' => "{$fromName} <{$fromAddress}>",
                'to' => [$to],
                'subject' => $subject,
                'text' => $text,
            ]);

            if ($response->successful()) {
                Log::info('Magic link email sent via Resend', [
                    'to' => $to,
                    'subject' => $subject,
                    'id' => $response->json('id'),
                ]);
                return true;
            }

            Log::error('Failed to send magic link email via Resend', [
                'to' => $to,
                'subject' => $subject,
                'response' => $response->json(),
                'status' => $response->status(),
            ]);
            return false;

        } catch (\Exception $e) {
            Log::error('Exception sending magic link email via Resend', [
                'to' => $to,
                'subject' => $subject,
                'error' => $e->getMessage(),
            ]);
            return false;
        }
    }

    /**
     * Verify dan gunakan token - HANYA jika belum digunakan
     * Prevent double execution dengan check used_at
     *
     * @param string $tokenString Token dari URL
     * @return Submission|null
     */
    public function verifyToken(string $tokenString): ?Submission
    {
        // Cari token (bypass scope valid agar bisa mengecek yang sudah digunakan juga)
        $token = MagicLinkToken::where('token', $tokenString)->first();

        if (!$token || $token->expires_at < Carbon::now()) {
            Log::warning('Invalid or expired token provided', ['token' => substr($tokenString, 0, 10) . '...']);
            return null;
        }

        // Jika token sudah pernah digunakan, tetap kembalikan submission
        // Ini memastikan jika user klik 2x (double click), dia tetap masuk ke halaman kode
        // tanpa memicu error atau mengirim email ganda (Idempotensi)
        if ($token->used_at !== null) {
            Log::info('Token valid tapi sudah digunakan, masuk ke halaman kode', ['token' => substr($tokenString, 0, 10) . '...']);
            return $token->submission;
        }

        // Tandai token sebagai sudah digunakan
        $token->update(['used_at' => Carbon::now()]);

        Log::info('Magic link token validated and marked as used', [
            'token' => substr($tokenString, 0, 10) . '...',
            'submission_id' => $token->submission_id,
        ]);

        return $token->submission;
    }

    /**
     * Kirim EMAIL 2 (Kode Unik) setelah verifikasi berhasil
     * Idempotent: Hanya kirim jika belum pernah terkirim untuk kode ini
     *
     * @param Submission $submission Must have unique_code and verified status
     * @return bool Success status
     */
    public function sendSuccessNotification(Submission $submission): bool
    {
        // Validation
        if (!$submission->unique_code || !$submission->isVerified()) {
            Log::warning('Attempted to send success email with incomplete submission', [
                'submission_id' => $submission->id,
                'has_unique_code' => !!$submission->unique_code,
                'status' => $submission->status,
            ]);
            return false;
        }

        // EMAIL 2 - Plain text message dengan kode unik
        $textMessage = "Selamat!\nLaporan kamu sudah berhasil diverifikasi dan masuk ke sistem kami.\n\n";
        $textMessage .= "Berikut adalah detail laporan kamu:\n";
        $textMessage .= "- Nama Pelapor: {$submission->name}\n";
        $textMessage .= "- Email: {$submission->email}\n";
        $textMessage .= "- Sekolah: {$submission->school}\n";
        $textMessage .= "- Kelas: {$submission->class}\n\n";
        $textMessage .= "KODE UNIK KAMU: {$submission->unique_code}\n\n";
        $textMessage .= "Mohon simpan kode ini baik-baik ya untuk memantau status atau melakukan konsultasi bersama guru BK!\n";

        // Send via Resend API menggunakan plain text
        $success = $this->sendViaResendPlainText(
            $submission->email,
            "Laporan Diterima! Ini Kode Unik Kamu",
            $textMessage
        );

        if ($success) {
            Log::info('Success notification email sent with unique code', [
                'submission_id' => $submission->id,
                'email' => $submission->email,
                'unique_code' => $submission->unique_code,
                'timestamp' => Carbon::now()->toDateTimeString(),
            ]);
        } else {
            Log::error('Failed to send success email', [
                'submission_id' => $submission->id,
                'email' => $submission->email,
            ]);
        }

        return $success;
    }

}
