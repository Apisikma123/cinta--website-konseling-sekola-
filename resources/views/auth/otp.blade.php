@php
/*
 * UNIFIED OTP VIEW - STANDARDIZED
 * Digunakan untuk: Login OTP, Register OTP, Reset Password OTP, Ganti Email OTP
 *
 * Parameters yang bisa di-pass:
 * - $title: Judul halaman (default: "Verifikasi OTP")
 * - $subtitle: Subtitle (default: "Masukkan kode OTP yang dikirim ke email Anda")
 * - $formAction: URL action form (wajib)
 * - $backRoute: Route untuk tombol kembali (opsional)
 * - $backLabel: Label tombol kembali (default: "Kembali")
 * - $submitLabel: Label tombol submit (default: "Verifikasi")
 * - $resendRoute: Route untuk resend OTP (default: "resend.otp")
 */

$title = $title ?? 'Verifikasi OTP';
$subtitle = $subtitle ?? 'Masukkan kode OTP yang dikirim ke email Anda';
$backLabel = $backLabel ?? 'Kembali';
$submitLabel = $submitLabel ?? 'Verifikasi';
$resendRoute = $resendRoute ?? 'resend.otp';
@endphp

@extends('layouts.auth', ['title' => $title])

@section('content')

<!-- Header -->
<div class="text-center mb-6">
    <!-- Icon -->
    <div class="flex justify-center mb-4">
        <div class="w-14 h-14 rounded-full bg-purple-100 flex items-center justify-center">
            <i class="fas fa-shield-alt text-2xl text-purple-600"></i>
        </div>
    </div>
    
    <!-- Title & Subtitle -->
    <h1 class="text-2xl md:text-3xl font-bold text-gray-800 mb-1">{{ $title }}</h1>
    <p class="text-gray-600 text-sm md:text-base mb-3">{{ $subtitle }}</p>
</div>

<!-- Email Display Box -->
<div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
    <div class="flex items-center justify-center gap-3">
        <i class="fas fa-envelope text-blue-600 text-lg"></i>
        <div class="text-left">
            <p class="text-xs text-blue-600 font-medium mb-1">Email Tujuan OTP</p>
            <p class="text-sm md:text-base font-semibold text-gray-800 break-all" id="emailDisplay">{{ $email ?? session('pending_email') }}</p>
        </div>
    </div>
</div>

<!-- Error Messages -->
@if($errors->any())
    <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-4">
        <ul class="list-disc pl-5 text-sm space-y-1">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<!-- Success Messages -->
@if(session('success'))
    <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg mb-4 text-sm">
        {{ session('success') }}
    </div>
@endif

<!-- Form -->
<form method="POST" action="{{ $formAction }}" id="otpForm" autocomplete="off">
    @csrf

    <!-- OTP Input Section -->
    <div class="my-4">
        <label class="block text-center font-semibold text-gray-700 mb-2 text-base">Masukkan Kode OTP</label>
        <div class="w-full flex justify-center">
            <div class="flex items-center justify-center w-full max-w-[20rem] gap-2 sm:gap-2.5 md:gap-3 lg:gap-3 xl:gap-4 px-2 sm:px-1 md:px-0 lg:px-0">
                <input type="text" class="otp-input w-10 h-11 text-sm sm:w-11 sm:h-12 md:w-12 md:h-14 md:text-base lg:w-12 lg:h-14 lg:text-base xl:w-[52px] xl:h-14 xl:text-lg 2xl:w-14 2xl:h-14 2xl:text-xl text-center font-bold border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 outline-none transition" maxlength="1" inputmode="numeric" pattern="[0-9]*">
                <input type="text" class="otp-input w-10 h-11 text-sm sm:w-11 sm:h-12 md:w-12 md:h-14 md:text-base lg:w-12 lg:h-14 lg:text-base xl:w-[52px] xl:h-14 xl:text-lg 2xl:w-14 2xl:h-14 2xl:text-xl text-center font-bold border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 outline-none transition" maxlength="1" inputmode="numeric" pattern="[0-9]*">
                <input type="text" class="otp-input w-10 h-11 text-sm sm:w-11 sm:h-12 md:w-12 md:h-14 md:text-base lg:w-12 lg:h-14 lg:text-base xl:w-[52px] xl:h-14 xl:text-lg 2xl:w-14 2xl:h-14 2xl:text-xl text-center font-bold border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 outline-none transition" maxlength="1" inputmode="numeric" pattern="[0-9]*">
                
                <span class="text-sm sm:text-sm md:text-base lg:text-base xl:text-lg 2xl:text-xl font-bold text-gray-400 px-1">-</span>
                
                <input type="text" class="otp-input w-10 h-11 text-sm sm:w-11 sm:h-12 md:w-12 md:h-14 md:text-base lg:w-12 lg:h-14 lg:text-base xl:w-[52px] xl:h-14 xl:text-lg 2xl:w-14 2xl:h-14 2xl:text-xl text-center font-bold border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 outline-none transition" maxlength="1" inputmode="numeric" pattern="[0-9]*">
                <input type="text" class="otp-input w-10 h-11 text-sm sm:w-11 sm:h-12 md:w-12 md:h-14 md:text-base lg:w-12 lg:h-14 lg:text-base xl:w-[52px] xl:h-14 xl:text-lg 2xl:w-14 2xl:h-14 2xl:text-xl text-center font-bold border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 outline-none transition" maxlength="1" inputmode="numeric" pattern="[0-9]*">
                <input type="text" class="otp-input w-10 h-11 text-sm sm:w-11 sm:h-12 md:w-12 md:h-14 md:text-base lg:w-12 lg:h-14 lg:text-base xl:w-[52px] xl:h-14 xl:text-lg 2xl:w-14 2xl:h-14 2xl:text-xl text-center font-bold border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 outline-none transition" maxlength="1" inputmode="numeric" pattern="[0-9]*">
            </div>
        </div>
        <input type="hidden" name="otp" id="otp_hidden">
    </div>

    <!-- OTP Status Messages -->
    <div class="text-center space-y-2 mt-4 mb-4">
        <p id="expiredTimer" class="text-sm text-gray-600 font-medium"></p>
        <p class="text-sm text-gray-600">
            Kirim ulang dalam: <span id="resendTimer" class="font-semibold text-purple-600">00:30</span>
        </p>
        <button type="button" id="resendBtn" disabled class="text-sm font-semibold text-purple-600 hover:text-purple-700 disabled:opacity-50 disabled:cursor-not-allowed transition">
            Kirim Ulang OTP
        </button>
    </div>

    <!-- Submit Button -->
    <button type="submit" id="submitBtn" class="w-full bg-purple-600 hover:bg-purple-700 text-white font-semibold py-3 rounded-xl shadow-md transition">
        {{ $submitLabel }}
    </button>

    <!-- Back Link -->
    @if(isset($backRoute))
        <div class="mt-4 text-center">
            <a href="{{ $backRoute }}" class="inline-flex items-center gap-2 text-sm text-gray-600 hover:text-purple-600 transition">
                <i class="fas fa-arrow-left text-xs"></i>
                {{ $backLabel }}
            </a>
        </div>
    @endif
</form>

<script>
(function() {
    const otpInputs = document.querySelectorAll('.otp-input');
    const otpHidden = document.getElementById('otp_hidden');
    const submitBtn = document.getElementById('submitBtn');
    const resendBtn = document.getElementById('resendBtn');
    const resendTimer = document.getElementById('resendTimer');
    const expiredTimer = document.getElementById('expiredTimer');
    const otpForm = document.getElementById('otpForm');

    let expiredInterval = null;
    let resendInterval = null;
    let isOtpExpired = false;

    // OTP Input Behavior: Auto move to next field
    otpInputs.forEach((input, index) => {
        input.addEventListener('input', (e) => {
            const value = e.target.value.replace(/[^0-9]/g, '');
            e.target.value = value;

            if (value && index < otpInputs.length - 1) {
                otpInputs[index + 1].focus();
            }
            updateHiddenInput();
        });

        // Backspace: move to previous field
        input.addEventListener('keydown', (e) => {
            if (e.key === 'Backspace' && !e.target.value && index > 0) {
                otpInputs[index - 1].focus();
            }
        });

        // Paste OTP: Handle paste event
        input.addEventListener('paste', (e) => {
            e.preventDefault();
            const pastedText = (e.clipboardData || window.clipboardData).getData('text').replace(/\D/g, '');
            
            if (pastedText.length === 6) {
                pastedText.split('').forEach((char, idx) => {
                    if (otpInputs[idx]) {
                        otpInputs[idx].value = char;
                    }
                });
                updateHiddenInput();
                otpInputs[otpInputs.length - 1].focus();
            }
        });
    });

    // Update hidden input with OTP code
    function updateHiddenInput() {
        const otp = Array.from(otpInputs).map(input => input.value).join('');
        otpHidden.value = otp;
    }

    // Timer Expired (5 minutes = 300 seconds)
    function startExpiredTimer(isFreshStart = false) {
        let remainingSeconds = 300;
        expiredTimer.textContent = 'Kode akan kadaluarsa dalam 05:00';

        // Jika fresh start (resend), langsung gunakan 300 detik
        // Jika bukan (page load), calculate berdasarkan otp_sent_at
        if (!isFreshStart) {
            const sentAt = {{ session('otp_sent_at', 0) }} * 1000;
            if (sentAt) {
                const elapsed = Math.floor((Date.now() - sentAt) / 1000);
                remainingSeconds = Math.max(0, 300 - elapsed);
            }
        }

        expiredInterval = setInterval(() => {
            const minutes = Math.floor(remainingSeconds / 60);
            const seconds = remainingSeconds % 60;
            expiredTimer.textContent = `Kode akan kadaluarsa dalam ${String(minutes).padStart(2, '0')}:${String(seconds).padStart(2, '0')}`;

            if (remainingSeconds <= 0) {
                clearInterval(expiredInterval);
                isOtpExpired = true;
                expiredTimer.textContent = 'OTP sudah kadaluarsa';
                expiredTimer.classList.add('text-red-600');
                submitBtn.disabled = true;
            }

            remainingSeconds--;
        }, 1000);
    }

    // Timer Resend (30 seconds - sesuai backend cooldown)
    function startResendTimer(isFreshStart = false) {
        let remainingSeconds = 30;
        resendBtn.disabled = true;

        // Jika fresh start (resend), langsung gunakan 30 detik
        // Jika bukan (page load), calculate berdasarkan otp_sent_at
        if (!isFreshStart) {
            const sentAt = {{ session('otp_sent_at', 0) }} * 1000;
            if (sentAt) {
                const elapsed = Math.floor((Date.now() - sentAt) / 1000);
                remainingSeconds = Math.max(0, 30 - elapsed);
            }
        }

        if (remainingSeconds <= 0) {
            enableResendButton();
            return;
        }

        resendInterval = setInterval(() => {
            const minutes = Math.floor(remainingSeconds / 60);
            const seconds = remainingSeconds % 60;
            resendTimer.textContent = `${String(minutes).padStart(2, '0')}:${String(seconds).padStart(2, '0')}`;

            if (remainingSeconds <= 0) {
                clearInterval(resendInterval);
                enableResendButton();
            }

            remainingSeconds--;
        }, 1000);
    }

    function enableResendButton() {
        resendBtn.disabled = false;
        resendTimer.textContent = '00:00';
    }

    // Resend OTP
    resendBtn.addEventListener('click', async () => {
        resendBtn.disabled = true;

        try {
            const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            const emailDisplay = document.getElementById('emailDisplay');
            // Get email from display text (trim whitespace)
            const email = emailDisplay ? emailDisplay.textContent.trim() : '';
            
            if (!email || email === 'email@example.com') {
                throw new Error('Email tidak ditemukan');
            }
            
            console.log('Resending OTP to:', email);
            
            const response = await fetch('{{ route($resendRoute) }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': token
                },
                body: JSON.stringify({
                    email: email
                })
            });

            const data = await response.json();

            if (data.success) {
                // Reset timers dengan fresh start (300 detik penuh)
                clearInterval(expiredInterval);
                clearInterval(resendInterval);
                isOtpExpired = false;
                expiredTimer.classList.remove('text-red-600');
                submitBtn.disabled = false;
                
                // Clear OTP input fields
                otpInputs.forEach(input => input.value = '');
                otpHidden.value = '';
                
                startExpiredTimer(true); // Fresh start = 300 detik penuh
                startResendTimer(true); // Fresh start = 30 detik

                // Show success message
                Swal.fire({
                    icon: 'success',
                    title: 'OTP Terkirim!',
                    text: 'Kode OTP baru telah dikirim ke ' + email + '. Silakan periksa inbox atau folder spam.',
                    confirmButtonColor: '#9333ea',
                    timer: 3000,
                    timerProgressBar: true
                });
            } else {
                resendBtn.disabled = false;
                const errorMsg = data.message || 'Gagal mengirim ulang OTP';
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal!',
                    text: errorMsg,
                    confirmButtonColor: '#9333ea'
                });
                console.error('Resend OTP error:', data);
            }
        } catch (error) {
            resendBtn.disabled = false;
            console.error('Resend OTP fetch error:', error);
            Swal.fire({
                icon: 'error',
                title: 'Kesalahan!',
                text: error.message || 'Terjadi kesalahan saat mengirim ulang OTP',
                confirmButtonColor: '#9333ea'
            });
        }
    });

    // Form Submit
    otpForm.addEventListener('submit', (e) => {
        if (isOtpExpired) {
            e.preventDefault();
            Swal.fire({
                icon: 'error',
                title: 'OTP Kadaluarsa!',
                text: 'Kode OTP Anda telah expired. Silakan minta kode baru.',
                confirmButtonColor: '#9333ea',
                confirmButtonText: 'Buat Ulang OTP'
            });
            return;
        }

        const otp = Array.from(otpInputs).map(input => input.value).join('');
        if (otp.length !== 6) {
            e.preventDefault();
            Swal.fire({
                icon: 'warning',
                title: 'OTP Belum Lengkap!',
                text: 'Silakan masukkan kode OTP yang terdiri dari 6 digit.',
                confirmButtonColor: '#9333ea',
                confirmButtonText: 'Isi Kembali'
            });
            return;
        }
    });

    // Display validation errors with SweetAlert
    document.addEventListener('DOMContentLoaded', function() {
        @if ($errors->any())
            const errors = {!! json_encode($errors->all()) !!};
            const errorMessage = errors.length > 0 ? errors[0] : 'Terjadi kesalahan saat verifikasi OTP';
            
            Swal.fire({
                icon: 'error',
                title: 'Verifikasi Gagal!',
                text: errorMessage,
                confirmButtonColor: '#9333ea',
                confirmButtonText: 'Coba Lagi'
            });
        @endif

        @if (session('error'))
            Swal.fire({
                icon: 'error',
                title: 'Kesalahan!',
                text: '{{ session('error') }}',
                confirmButtonColor: '#9333ea'
            });
        @endif

        @if (session('warning'))
            Swal.fire({
                icon: 'warning',
                title: 'Peringatan!',
                text: '{{ session('warning') }}',
                confirmButtonColor: '#9333ea'
            });
        @endif
    });

    // Toast Notification
    function showToast(message, type = 'info') {
        const toast = document.createElement('div');
        toast.className = 'fixed top-4 right-4 px-4 py-3 rounded-md text-white text-sm font-medium z-50';
        
        if (type === 'success') {
            toast.classList.add('bg-green-600');
        } else if (type === 'error') {
            toast.classList.add('bg-red-600');
        } else {
            toast.classList.add('bg-blue-600');
        }

        toast.textContent = message;
        document.body.appendChild(toast);

        setTimeout(() => {
            toast.style.transition = 'opacity 0.3s ease';
            toast.style.opacity = '0';
            setTimeout(() => toast.remove(), 300);
        }, 3000);
    }

    // Initialize timers on page load
    startExpiredTimer();
    startResendTimer();
})();
</script>
@endsection
