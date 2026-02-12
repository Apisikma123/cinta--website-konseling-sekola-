@include('auth.otp', [
    'title' => 'Verifikasi Perubahan Password',
    'subtitle' => 'Kode OTP dikirim ke email Anda',
    'formAction' => route('teacher.verify-otp-password'),
    'backRoute' => route('teacher.settings'),
    'backLabel' => 'Kembali ke Pengaturan',
    'submitLabel' => 'Verifikasi & Ubah Password',
    'resendRoute' => 'teacher.resend-otp-password',
    'icon' => 'lock'
])