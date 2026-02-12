<?php

return [
    'enabled' => env('RECAPTCHA_ENABLED', true),
    'site_key' => env('RECAPTCHA_SITE_KEY'),
    'secret_key' => env('RECAPTCHA_SECRET_KEY'),
    'minimum_score' => 0.5, // reCAPTCHA v3 score threshold (0.0 - 1.0)
];
