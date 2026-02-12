<?php echo $__env->make('auth.otp', [
    'title' => 'Verifikasi Login',
    'subtitle' => 'Masukkan kode OTP untuk melanjutkan login',
    'formAction' => route('login.otp'),
    'backRoute' => route('login'),
    'backLabel' => 'Kembali ke Login',
    'submitLabel' => 'Verifikasi & Login',
    'resendRoute' => 'resend.otp',
    'icon' => 'sign-in-alt'
], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\ngoding\sistem-cinta\resources\views/auth/login-otp.blade.php ENDPATH**/ ?>