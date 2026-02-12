# Konfigurasi Email untuk OTP

## Overview

Sistem OTP menggunakan **dual-mailer** approach:

1. **Primary**: Resend API (via ResendService)
2. **Fallback**: Laravel Mail (SMTP)

## Konfigurasi .env

### Option 1: Resend API (Recommended)

```env
MAIL_MAILER=resend
RESEND_API_KEY=re_Wv9Y7EDb_7KJJ3uX357NdCtSvwz48byFY
RESEND_FROM_ADDRESS=sistemcinta@telkomcare.my.id
RESEND_FROM_NAME="Sistem Cinta"
MAIL_FROM_ADDRESS=sistemcinta@telkomcare.my.id
MAIL_FROM_NAME="Sistem Cinta"
```

### Option 2: Gmail SMTP

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=your-app-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=your-email@gmail.com
MAIL_FROM_NAME="Sistem Cinta"
```

**IMPORTANT**: Untuk Gmail, gunakan **App Password** bukan password biasa!
Cara membuat App Password:

1. Buka https://myaccount.google.com/security
2. Aktifkan 2-Step Verification
3. Buat App Password di "App passwords"
4. Copy password 16 karakter ke .env

### Option 3: Custom SMTP (Telkomcare)

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp.telkomcare.my.id
MAIL_PORT=587
MAIL_USERNAME=sistemcinta@telkomcare.my.id
MAIL_PASSWORD=your-actual-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=sistemcinta@telkomcare.my.id
MAIL_FROM_NAME="Sistem Cinta"
```

## Setelah Update .env

Jalankan command berikut:

```bash
php artisan config:clear
php artisan cache:clear
```

## Testing Email

Untuk test pengiriman email:

```bash
php artisan tinker
>>> Mail::raw('Test email from Sistem Cinta', function($m) { $m->to('your-email@example.com')->subject('Test'); });
```

## Troubleshooting

### Email tidak terkirim

1. Check log: `storage/logs/laravel.log`
2. Verifikasi MAIL_MAILER di .env
3. Test koneksi SMTP: `telnet smtp.gmail.com 587`
4. Cek firewall/antivirus

### Resend API Error

1. Verifikasi API key valid
2. Check domain verification di Resend dashboard
3. Pastikan from address sudah verified

### OTP tidak masuk

1. Check spam/junk folder
2. Verifikasi email address benar
3. Check log untuk error pengiriman
4. Coba gunakan fallback SMTP

## File Terkait OTP

- `app/Services/OtpService.php` - Logic OTP & pengiriman email
- `app/Services/ResendService.php` - Resend API wrapper
- `resources/views/auth/otp.blade.php` - Unified OTP view
- `resources/views/auth/login-otp.blade.php` - Login OTP (menggunakan otp.blade.php)
- `resources/views/auth/verify-otp.blade.php` - Register OTP (menggunakan otp.blade.php)
- `resources/views/teacher/verify-otp-email.blade.php` - Change Email OTP
- `resources/views/teacher/verify-otp-password.blade.php` - Change Password OTP

## Flow OTP

1. User request OTP → `OtpService::generateForEmail()`
2. Generate 6 digit code → Simpan ke database
3. Kirim email via Resend API → Jika gagal, fallback ke SMTP
4. User input OTP → `OtpService::verify()`
5. Validasi code & expiry → Mark as used
6. Proses selesai (login/register/change password/email)

## Security

- OTP berlaku 5 menit (300 detik)
- Cooldown resend 60 detik
- Auto-delete/set used setelah verifikasi
- Plain text email (tidak ada HTML yang bisa di-spam filter)
