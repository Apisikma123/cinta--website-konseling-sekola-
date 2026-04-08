<?php

namespace App\Services;

class ChatEncryptionService
{
    protected string $cipher = 'AES-256-CBC';
    protected string $key;

    public function __construct()
    {
        // Extract the raw key from APP_KEY config (which might start with 'base64:')
        $key = config('app.key');
        if (str_starts_with($key, 'base64:')) {
            $this->key = base64_decode(substr($key, 7));
        } else {
            $this->key = $key;
        }
    }

    /**
     * Encrypt a plaintext message.
     *
     * @param string $plaintext
     * @return array ['encrypted' => base64_encoded_string, 'iv' => base64_encoded_iv]
     */
    public function encrypt(string $plaintext): array
    {
        if (empty($plaintext)) {
            return [
                'encrypted' => '',
                'iv' => ''
            ];
        }

        $ivLength = openssl_cipher_iv_length($this->cipher);
        $iv = openssl_random_pseudo_bytes($ivLength);

        // Encrypt data
        $encrypted = openssl_encrypt($plaintext, $this->cipher, $this->key, OPENSSL_RAW_DATA, $iv);

        return [
            'encrypted' => base64_encode($encrypted),
            'iv' => base64_encode($iv),
        ];
    }

    /**
     * Decrypt an encrypted message.
     *
     * @param string $encrypted Base64 encoded string
     * @param string $iv Base64 encoded IV
     * @return string
     */
    public function decrypt(string $encrypted, string $iv): string
    {
        if (empty($encrypted) || empty($iv)) {
            return '';
        }

        $decrypted = openssl_decrypt(
            base64_decode($encrypted),
            $this->cipher,
            $this->key,
            OPENSSL_RAW_DATA,
            base64_decode($iv)
        );

        return $decrypted !== false ? $decrypted : '[DECRYPTION FAILED]';
    }
}
