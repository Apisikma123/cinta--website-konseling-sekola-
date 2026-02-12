<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class RecaptchaService
{
    private string $secretKey;

    public function __construct()
    {
        $this->secretKey = config('recaptcha.secret_key') ?? env('RECAPTCHA_SECRET_KEY');
    }

    /**
     * Verify reCAPTCHA v3 token
     * 
     * @param string $token The reCAPTCHA token from client
     * @param float $minimumScore The minimum score threshold (0.0 - 1.0)
     * @return bool Whether the token is valid
     */
    public function verify(string $token, float $minimumScore = 0.5): bool
    {
        // Bypass verification if disabled (for development/testing)
        if (!config('recaptcha.enabled', true)) {
            return true;
        }

        if (empty($token)) {
            return false;
        }

        try {
            $response = Http::post('https://www.google.com/recaptcha/api/siteverify', [
                'secret' => $this->secretKey,
                'response' => $token,
            ]);

            if (!$response->successful()) {
                return false;
            }

            $data = $response->json();

            // Check if verification was successful
            if (!isset($data['success']) || !$data['success']) {
                return false;
            }

            // Check the score (reCAPTCHA v3)
            $score = $data['score'] ?? 0;
            if ($score < $minimumScore) {
                return false;
            }

            return true;
        } catch (\Exception $e) {
            \Log::error('reCAPTCHA verification failed: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Get the site key for rendering the widget
     */
    public function getSiteKey(): string
    {
        return config('recaptcha.site_key') ?? env('RECAPTCHA_SITE_KEY');
    }
}
