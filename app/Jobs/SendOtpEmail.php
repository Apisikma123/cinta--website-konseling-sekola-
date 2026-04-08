<?php

namespace App\Jobs;

use App\Models\OtpVerification;
use App\Services\ResendService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class SendOtpEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected string $email;
    protected string $code;
    protected int $expiresInMinutes;

    public function __construct(string $email, string $code, int $expiresInMinutes = 5)
    {
        $this->email = $email;
        $this->code = $code;
        $this->expiresInMinutes = $expiresInMinutes;

        // Set queue to 'default' and delay to 0 (immediate)
        $this->onQueue('default');
    }

    public function handle(): void
    {
        $subject = 'Kode OTP - Sistem Cinta';

        // Try Resend API first
        try {
            $resendService = new ResendService();
            if ($resendService->sendOtp($this->email, $this->code, $this->expiresInMinutes)) {
                Log::info("OTP email sent via Resend for {$this->email}");
                return;
            }
        } catch (\Exception $e) {
            Log::warning("Resend API failed for {$this->email}: " . $e->getMessage());
        }

        // Fallback to Laravel Mail (SMTP)
        try {
            $text = "Kode OTP Anda: {$this->code}\n\nKode ini berlaku selama {$this->expiresInMinutes} menit.\nJika Anda tidak meminta kode ini, abaikan email ini.\n\nSistem Cinta";

            Mail::raw($text, function ($message) use ($subject) {
                $message->to($this->email)->subject($subject);
            });

            Log::info("OTP email sent via SMTP for {$this->email}");
        } catch (\Exception $e) {
            Log::error("Failed to send OTP email for {$this->email}: " . $e->getMessage());
            // Don't fail the job - OTP already created in DB
        }
    }
}
