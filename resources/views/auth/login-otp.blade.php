@include('auth.otp', [
    'title' => 'Verifikasi Login',
    'subtitle' => 'Masukkan kode OTP untuk melanjutkan login',
    'formAction' => route('login.otp.verify'),
    'backRoute' => route('login'),
    'backLabel' => 'Kembali ke Login',
    'submitLabel' => 'Verifikasi & Login',
    'resendRoute' => 'resend.otp',
    'icon' => 'sign-in-alt'
])