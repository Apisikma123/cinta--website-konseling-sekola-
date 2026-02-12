@props([
    'otpExpiry' => 300,
    'resendCooldown' => 60,
    'resendRoute' => 'resend.otp'
])

{{-- OTP Timer --}}
<p id="otp-timer" class="text-sm text-gray-500 mt-3 text-center"></p>

{{-- Resend Button --}}
<div class="mt-3 text-center">
    <p id="resend-timer" class="text-sm text-gray-500"></p>
    <button type="button" id="resendBtn" onclick="resendOtp()" disabled
            class="text-purple-600 font-medium hover:text-purple-700 disabled:text-gray-400 disabled:cursor-not-allowed text-sm mt-1">
        Kirim Ulang
    </button>
</div>

@push('scripts')
<script>
    // OTP Timer 5 menit
    let timeLeft = {{ $otpExpiry }};
    const timerElement = document.getElementById("otp-timer");
    const submitBtn = document.getElementById("verify-btn");

    // Get remaining time from session
    const sentAt = {{ session('otp_sent_at', 0) }} * 1000;
    if (sentAt) {
        const elapsed = Math.floor((Date.now() - sentAt) / 1000);
        timeLeft = Math.max(0, {{ $otpExpiry }} - elapsed);
    }

    const countdown = setInterval(() => {
        let minutes = Math.floor(timeLeft / 60);
        let seconds = timeLeft % 60;

        timerElement.innerText =
            `Berlaku selama ${minutes}:${seconds < 10 ? '0' : ''}${seconds}`;

        timeLeft--;

        if (timeLeft < 0) {
            clearInterval(countdown);
            timerElement.innerText = "OTP sudah kadaluarsa";
            timerElement.classList.add("text-red-600");
            if (submitBtn) submitBtn.disabled = true;
        }
    }, 1000);

    // Resend Countdown 60 detik
    let resendTime = {{ $resendCooldown }};
    const resendBtn = document.getElementById("resendBtn");
    const resendText = document.getElementById("resend-timer");

    // Calculate remaining resend time
    if (sentAt) {
        const elapsed = Math.floor((Date.now() - sentAt) / 1000);
        resendTime = Math.max(0, {{ $resendCooldown }} - elapsed);
    }

    const resendCountdown = setInterval(() => {
        if (resendTime > 0) {
            resendText.innerText = `Kirim ulang dalam ${resendTime} detik`;
            resendTime--;
        } else {
            clearInterval(resendCountdown);
            resendBtn.disabled = false;
            resendText.innerText = "";
        }
    }, 1000);

    // Resend OTP function
    function resendOtp() {
        resendBtn.disabled = true;
        resendBtn.innerText = 'Mengirim...';

        fetch('{{ route($resendRoute) }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        })
        .then(response => {
            if (response.status === 429) {
                return response.json().then(data => ({ error: true, retry_after: data.retry_after || 60 }));
            }
            return response.json();
        })
        .then(result => {
            if (result.error) {
                const retry = result.retry_after || 60;
                alert('Tunggu ' + retry + ' detik sebelum mengirim ulang.');
                resendTime = retry;
                resendBtn.innerText = 'Kirim Ulang';
                // Restart countdown
                const newResendCountdown = setInterval(() => {
                    if (resendTime > 0) {
                        resendText.innerText = `Kirim ulang dalam ${resendTime} detik`;
                        resendTime--;
                    } else {
                        clearInterval(newResendCountdown);
                        resendBtn.disabled = false;
                        resendText.innerText = "";
                    }
                }, 1000);
            } else {
                alert('OTP baru telah dikirim ke email Anda.');
                // Reload page to reset timers
                window.location.reload();
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Gagal mengirim OTP. Silakan coba lagi.');
            resendBtn.disabled = false;
            resendBtn.innerText = 'Kirim Ulang';
        });
    }
</script>
@endpush
