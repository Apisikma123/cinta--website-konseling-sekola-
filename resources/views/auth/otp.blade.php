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
<div class="text-center mb-8">
    <h1 class="text-3xl font-bold text-gray-900 mb-3">{{ $title }}</h1>
    <p class="text-gray-600 text-base">{{ $subtitle }}</p>
</div>

<!-- Error Messages -->
@if($errors->any())
    <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-6">
        <ul class="list-disc pl-5 text-sm space-y-1">
            @foreach($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<!-- Success Messages -->
@if(session('success'))
    <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg mb-6 text-sm">
        {{ session('success') }}
    </div>
@endif

<!-- Form -->
<form method="POST" action="{{ $formAction }}" id="otpForm" class="max-w-2xl mx-auto">
    @csrf

    <!-- OTP Input Section -->
    <div class="mb-8">
        <label class="block text-center font-medium text-gray-700 mb-6 text-lg">Masukkan Kode OTP</label>
        <div class="flex justify-center gap-1 sm:gap-2 lg:gap-4 flex-nowrap mb-4">
            <input type="text" class="otp-input w-8 h-10 sm:w-12 sm:h-12 lg:w-14 lg:h-14 text-center border border-gray-300 rounded-lg text-xs sm:text-sm lg:text-lg font-semibold focus:ring-2 focus:ring-purple-500 focus:border-transparent outline-none transition flex-shrink-0" maxlength="1" inputmode="numeric">
            <input type="text" class="otp-input w-8 h-10 sm:w-12 sm:h-12 lg:w-14 lg:h-14 text-center border border-gray-300 rounded-lg text-xs sm:text-sm lg:text-lg font-semibold focus:ring-2 focus:ring-purple-500 focus:border-transparent outline-none transition flex-shrink-0" maxlength="1" inputmode="numeric">
            <input type="text" class="otp-input w-8 h-10 sm:w-12 sm:h-12 lg:w-14 lg:h-14 text-center border border-gray-300 rounded-lg text-xs sm:text-sm lg:text-lg font-semibold focus:ring-2 focus:ring-purple-500 focus:border-transparent outline-none transition flex-shrink-0" maxlength="1" inputmode="numeric">
            <input type="text" class="otp-input w-8 h-10 sm:w-12 sm:h-12 lg:w-14 lg:h-14 text-center border border-gray-300 rounded-lg text-xs sm:text-sm lg:text-lg font-semibold focus:ring-2 focus:ring-purple-500 focus:border-transparent outline-none transition flex-shrink-0" maxlength="1" inputmode="numeric">
            <input type="text" class="otp-input w-8 h-10 sm:w-12 sm:h-12 lg:w-14 lg:h-14 text-center border border-gray-300 rounded-lg text-xs sm:text-sm lg:text-lg font-semibold focus:ring-2 focus:ring-purple-500 focus:border-transparent outline-none transition flex-shrink-0" maxlength="1" inputmode="numeric">
            <input type="text" class="otp-input w-8 h-10 sm:w-12 sm:h-12 lg:w-14 lg:h-14 text-center border border-gray-300 rounded-lg text-xs sm:text-sm lg:text-lg font-semibold focus:ring-2 focus:ring-purple-500 focus:border-transparent outline-none transition flex-shrink-0" maxlength="1" inputmode="numeric">
        </div>
        <input type="hidden" name="otp" id="otp_hidden">
    </div>

    <!-- Timer Expired -->
    <p id="expiredTimer" class="text-center text-sm text-gray-600 mb-6 font-medium"></p>

    <!-- Resend Timer Section -->
    <div class="text-center mb-8">
        <p class="text-sm text-gray-600 mb-2">Kirim ulang dalam: <span id="resendTimer" class="font-semibold text-purple-600">01:00</span></p>
        <button type="button" id="resendBtn" disabled class="text-purple-600 hover:text-purple-700 font-semibold text-sm disabled:opacity-50 disabled:cursor-not-allowed transition">
            Kirim Ulang OTP
        </button>
    </div>

    <!-- Submit Button -->
    <button type="submit" id="submitBtn" class="w-full bg-purple-600 text-white py-3 rounded-lg font-semibold text-base hover:bg-purple-700 disabled:opacity-50 disabled:cursor-not-allowed transition">
        {{ $submitLabel }}
    </button>

    <!-- Back Link -->
    @if(isset($backRoute))
        <div class="mt-6 text-center">
            <a href="{{ $backRoute }}" class="text-sm text-gray-600 hover:text-gray-900">
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
    function startExpiredTimer() {
        let remainingSeconds = 300;
        expiredTimer.textContent = 'Kode akan kadaluarsa dalam 05:00';

        const sentAt = {{ session('otp_sent_at', 0) }} * 1000;
        if (sentAt) {
            const elapsed = Math.floor((Date.now() - sentAt) / 1000);
            remainingSeconds = Math.max(0, 300 - elapsed);
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

    // Timer Resend (60 seconds)
    function startResendTimer() {
        let remainingSeconds = 60;
        resendBtn.disabled = true;

        const sentAt = {{ session('otp_sent_at', 0) }} * 1000;
        if (sentAt) {
            const elapsed = Math.floor((Date.now() - sentAt) / 1000);
            remainingSeconds = Math.max(0, 60 - elapsed);
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
            const res = await fetch('{{ route($resendRoute) }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': token
                }
            });

            if (res.ok) {
                // Reset timers
                clearInterval(expiredInterval);
                clearInterval(resendInterval);
                isOtpExpired = false;
                expiredTimer.classList.remove('text-red-600');
                submitBtn.disabled = false;
                
                startExpiredTimer();
                startResendTimer();

                // Show success message
                Swal.fire({
                    icon: 'success',
                    title: 'OTP Terkirim!',
                    text: 'Kode OTP baru telah dikirim ke email Anda. Silakan periksa inbox atau folder spam.',
                    confirmButtonColor: '#9333ea',
                    timer: 3000,
                    timerProgressBar: true
                });
            } else {
                resendBtn.disabled = false;
                const data = await res.json();
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal!',
                    text: data.message || 'Gagal mengirim ulang OTP',
                    confirmButtonColor: '#9333ea'
                });
            }
        } catch (error) {
            resendBtn.disabled = false;
            Swal.fire({
                icon: 'error',
                title: 'Kesalahan!',
                text: 'Terjadi kesalahan saat mengirim ulang OTP',
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

        @if (session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Verifikasi Berhasil!',
                text: '{{ session('success') }}',
                confirmButtonColor: '#9333ea',
                didClose: () => {
                    window.location.href = '{{ url('/') }}';
                }
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
