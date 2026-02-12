<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class ResendService
{
    private string $apiKey;
    private string $fromAddress;
    private string $fromName;

    public function __construct()
    {
        $this->apiKey = config('services.resend.api_key', env('RESEND_API_KEY'));
        $this->fromAddress = config('services.resend.from_address', env('RESEND_FROM_ADDRESS', env('MAIL_FROM_ADDRESS')));
        $this->fromName = config('services.resend.from_name', env('RESEND_FROM_NAME', env('MAIL_FROM_NAME', 'Sistem Cinta')));
    }

    /**
     * Send email via Resend API
     *
     * @param string $to Recipient email
     * @param string $subject Email subject
     * @param string $html HTML content
     * @return bool Success status
     */
    public function send(string $to, string $subject, string $html): bool
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Content-Type' => 'application/json',
            ])->post('https://api.resend.com/emails', [
                'from' => $this->fromName . ' <' . $this->fromAddress . '>',
                'to' => [$to],
                'subject' => $subject,
                'html' => $html,
            ]);

            if ($response->successful()) {
                Log::info('Email sent via Resend', [
                    'to' => $to,
                    'subject' => $subject,
                    'id' => $response->json('id'),
                ]);
                return true;
            }

            Log::error('Failed to send email via Resend', [
                'to' => $to,
                'subject' => $subject,
                'response' => $response->json(),
                'status' => $response->status(),
            ]);
            return false;

        } catch (\Exception $e) {
            Log::error('Exception sending email via Resend', [
                'to' => $to,
                'subject' => $subject,
                'error' => $e->getMessage(),
            ]);
            return false;
        }
    }

    /**
     * Send OTP email
     *
     * @param string $to Recipient email
     * @param string $code OTP code
     * @param int $expiresInMinutes Expiration time in minutes
     * @return bool Success status
     */
    public function sendOtp(string $to, string $code, int $expiresInMinutes = 5): bool
    {
        $subject = 'Kode OTP';
        $text = "Kode OTP Anda: {$code}\nBerlaku selama {$expiresInMinutes} menit.\nJika Anda tidak meminta kode ini, abaikan email ini.";

        return $this->sendPlainText($to, $subject, $text);
    }

    /**
     * Send plain text email via Resend API
     *
     * @param string $to Recipient email
     * @param string $subject Email subject
     * @param string $text Plain text content
     * @return bool Success status
     */
    public function sendPlainText(string $to, string $subject, string $text): bool
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Content-Type' => 'application/json',
            ])->post('https://api.resend.com/emails', [
                'from' => $this->fromName . ' <' . $this->fromAddress . '>',
                'to' => [$to],
                'subject' => $subject,
                'text' => $text,
            ]);

            if ($response->successful()) {
                Log::info('Plain text email sent via Resend', [
                    'to' => $to,
                    'subject' => $subject,
                    'id' => $response->json('id'),
                ]);
                return true;
            }

            Log::error('Failed to send plain text email via Resend', [
                'to' => $to,
                'subject' => $subject,
                'response' => $response->json(),
                'status' => $response->status(),
            ]);
            return false;

        } catch (\Exception $e) {
            Log::error('Exception sending plain text email via Resend', [
                'to' => $to,
                'subject' => $subject,
                'error' => $e->getMessage(),
            ]);
            return false;
        }
    }

    /**
     * Send report notification email
     *
     * @param string $to Admin email
     * @param string $teacherName Guru name
     * @param string $reportTitle Report title
     * @param string $action Action (created/updated)
     * @return bool Success status
     */
    public function sendReportNotification(string $to, string $teacherName, string $reportTitle, string $action = 'created'): bool
    {
        $subject = 'Laporan ' . ($action === 'created' ? 'Baru' : 'Diperbarui') . ' - Sistem Cinta';
        $html = $this->getReportNotificationTemplate($teacherName, $reportTitle, $action);

        return $this->send($to, $subject, $html);
    }

    /**
     * Get OTP email template
     */
    private function getOtpTemplate(string $code, int $expiresInMinutes): string
    {
        return <<<HTML
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kode OTP</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f5f5f5; margin: 0; padding: 20px; }
        .container { max-width: 600px; margin: 0 auto; background: white; border-radius: 8px; padding: 30px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        .header { text-align: center; margin-bottom: 30px; }
        .header h1 { color: #7c3aed; margin: 0; font-size: 24px; }
        .otp-code { background: #f3f4f6; border: 2px dashed #7c3aed; border-radius: 8px; padding: 20px; text-align: center; margin: 30px 0; }
        .otp-code .code { font-size: 32px; font-weight: bold; color: #7c3aed; letter-spacing: 8px; }
        .footer { text-align: center; color: #6b7280; font-size: 12px; margin-top: 30px; }
        .warning { background: #fef3c7; border-left: 4px solid #f59e0b; padding: 12px; margin: 20px 0; color: #92400e; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🔐 Verifikasi OTP</h1>
        </div>
        <p>Halo,</p>
        <p>Berikut adalah kode OTP Anda untuk verifikasi:</p>
        <div class="otp-code">
            <div class="code">{$code}</div>
        </div>
        <div class="warning">
            ⏰ Kode ini akan kadaluarsa dalam <strong>{$expiresInMinutes} menit</strong>.
        </div>
        <p>Jika Anda tidak meminta kode ini, silakan abaikan email ini.</p>
        <div class="footer">
            <p>Email ini dikirim oleh Sistem Cinta</p>
            <p>© 2024 Sistem Cinta. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
HTML;
    }

    /**
     * Get report notification email template
     */
    private function getReportNotificationTemplate(string $teacherName, string $reportTitle, string $action): string
    {
        $actionText = $action === 'created' ? 'membuat' : 'memperbarui';
        $actionLabel = $action === 'created' ? 'Baru' : 'Diperbarui';

        return <<<HTML
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notifikasi Laporan</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f5f5f5; margin: 0; padding: 20px; }
        .container { max-width: 600px; margin: 0 auto; background: white; border-radius: 8px; padding: 30px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        .header { text-align: center; margin-bottom: 30px; }
        .header h1 { color: #7c3aed; margin: 0; font-size: 24px; }
        .content { background: #f9fafb; border-radius: 8px; padding: 20px; margin: 20px 0; }
        .content p { margin: 10px 0; }
        .label { color: #6b7280; font-size: 12px; text-transform: uppercase; }
        .value { color: #111827; font-weight: 600; }
        .footer { text-align: center; color: #6b7280; font-size: 12px; margin-top: 30px; }
        .badge { display: inline-block; background: #7c3aed; color: white; padding: 4px 12px; border-radius: 4px; font-size: 12px; font-weight: 600; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>📋 Laporan {$actionLabel}</h1>
            <span class="badge">{$actionLabel}</span>
        </div>
        <p>Halo Admin,</p>
        <p>Seorang guru telah {$actionText} laporan:</p>
        <div class="content">
            <p>
                <span class="label">Nama Guru</span><br>
                <span class="value">{$teacherName}</span>
            </p>
            <p>
                <span class="label">Judul Laporan</span><br>
                <span class="value">{$reportTitle}</span>
            </p>
            <p>
                <span class="label">Tanggal</span><br>
                <span class="value">" . date('d F Y H:i') . "</span>
            </p>
        </div>
        <p>Silakan login ke sistem untuk melihat detail laporan.</p>
        <div class="footer">
            <p>Email ini dikirim oleh Sistem Cinta</p>
            <p>© 2024 Sistem Cinta. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
HTML;
    }
}
