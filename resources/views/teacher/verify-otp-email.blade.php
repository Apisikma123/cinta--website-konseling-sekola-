@include('auth.otp', [
    'title' => 'Verifikasi Perubahan Email',
    'subtitle' => 'Kode OTP dikirim ke email lama Anda',
    'formAction' => route('teacher.verify-otp-email-store'),
    'backRoute' => route('teacher.settings'),
    'backLabel' => 'Kembali ke Pengaturan',
    'submitLabel' => 'Verifikasi & Ubah Email',
    'resendRoute' => 'teacher.resend-otp-email',
    'icon' => 'envelope-open'
])