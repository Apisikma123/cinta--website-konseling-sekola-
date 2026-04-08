<?php echo $__env->make('auth.otp', [
    'title' => 'Verifikasi Login',
    'subtitle' => 'Kami telah mengirim kode OTP ke email Anda, 
        jika kode OTP tidak masuk silahkan buka tab spam.',
    'formAction' => route('login.otp'),
    'backRoute' => route('login'),
    'backLabel' => 'Kembali ke Login',
    'submitLabel' => 'Verifikasi & Login',
    'resendRoute' => 'resend.otp',
    'icon' => 'sign-in-alt',
    'email' => $email ?? null
], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\ngoding\sistem-cinta\resources\views/auth/login-otp.blade.php ENDPATH**/ ?>