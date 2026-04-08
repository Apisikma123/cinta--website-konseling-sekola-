@extends('layouts.auth')

@section('title', 'Login Guru/Admin')

@section('content')
<div class="mb-4">
    <a href="{{ url('/') }}" class="inline-flex items-center text-purple-600 hover:text-purple-800 text-sm font-medium">
        <i class="fas fa-arrow-left mr-2"></i> Kembali ke Beranda
    </a>
</div>



<form method="POST" action="{{ route('login.store') }}" id="loginForm">
    @csrf
    <div class="space-y-5">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">
                <i class="fas fa-envelope mr-2 text-purple-600"></i>Email
            </label>
            <input type="email" name="email" required
                   class="w-full px-4 py-3 rounded-lg border border-purple-200 focus:ring-2 focus:ring-purple-300 focus:border-transparent outline-none transition"
                   placeholder="guru@sekolah.sch.id" value="{{ old('email') }}">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">
                <i class="fas fa-lock mr-2 text-purple-600"></i>Password
            </label>
            <input id="login-password" type="password" name="password" required
                   class="w-full px-4 py-3 rounded-lg border border-purple-200 focus:ring-2 focus:ring-purple-300 focus:border-transparent outline-none transition"
                   placeholder="••••••••">
        </div>

        <div class="flex items-center space-x-2 text-sm">
            <input id="show-password-login" type="checkbox" class="h-4 w-4 text-purple-600 border-gray-300 rounded" />
            <label for="show-password-login" class="text-gray-700">Tampilkan password</label>
        </div>

        <!-- Hidden reCAPTCHA token field -->
        @if(config('recaptcha.enabled', true))
        <input type="hidden" name="recaptcha_token" id="recaptcha_token_login">
        @endif

        <!-- reCAPTCHA Notice -->
        <div class="text-center text-xs text-gray-500 py-2">
            <p>Halaman ini dilindungi oleh reCAPTCHA dan Google</p>
            <p>
                <a href="https://policies.google.com/privacy" target="_blank" class="text-purple-600 hover:text-purple-700">Kebijakan Privasi</a>
                <span class="mx-1">-</span>
                <a href="https://policies.google.com/terms" target="_blank" class="text-purple-600 hover:text-purple-700">Ketentuan Layanan</a>
            </p>
        </div>

        @if(config('recaptcha.enabled', true))
        <div class="flex justify-center mb-4">
            <div class="g-recaptcha" 
                 data-sitekey="{{ config('recaptcha.site_key') }}"
                 id="recaptcha_login_widget">
            </div>
        </div>
        @endif

        <button type="submit" id="login-btn" class="w-full justify-center inline-flex items-center bg-purple-600 hover:bg-purple-700 active:scale-95 text-white font-bold py-3 px-4 rounded-lg transition duration-200">
            <i class="fas fa-sign-in-alt mr-2"></i> <span id="loginBtnText">Login</span>
        </button>

        <!-- Loading Modal -->
        @push('modals')
        <div id="loginLoadingModal" class="hidden fixed inset-0 bg-white z-[999999]" style="display: none;">
            <div class="flex items-center justify-center h-full">
                <x-loading message="Memverifikasi login..." />
            </div>
        </div>
        @endpush
    </div>
</form>

<div class="text-center mt-6">
    <a href="{{ route('register.teacher.form') }}" class="text-purple-600 hover:text-purple-800 text-sm">
        <i class="fas fa-user-plus mr-1"></i> Daftar sebagai Guru Baru
    </a>
</div>

<script>
    (function () {
        const toggle = document.getElementById('show-password-login');
        const pw = document.getElementById('login-password');
        toggle?.addEventListener('change', function () {
            pw.type = this.checked ? 'text' : 'password';
        });
    })();

    (function () {
        const form = document.getElementById('loginForm');
        const loginBtn = document.getElementById('login-btn');
        const loadingModal = document.getElementById('loginLoadingModal');

        form?.addEventListener('submit', function(e) {
            loadingModal.classList.remove('hidden');
            loginBtn.disabled = true;
        });
    })();

    // Display validation errors with SweetAlert
    document.addEventListener('DOMContentLoaded', function() {
        @if ($errors->any())
            const errors = {!! json_encode($errors->all()) !!};
            const errorMessage = errors.length > 0 ? errors[0] : 'Terjadi kesalahan saat login';
            
            Swal.fire({
                icon: 'error',
                title: 'Oops!',
                text: errorMessage,
                confirmButtonColor: '#9333ea',
                confirmButtonText: 'Coba Lagi'
            }).then(() => {
                document.getElementById('loginLoadingModal').classList.add('hidden');
                document.getElementById('login-btn').disabled = false;
            });
        @endif

        @if (session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: '{{ session('success') }}',
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

        @if (session('info'))
            Swal.fire({
                icon: 'info',
                title: 'Informasi',
                text: '{{ session('info') }}',
                confirmButtonColor: '#9333ea'
            });
        @endif
    });
</script>

@if(config('recaptcha.enabled', true))
<script src="https://www.google.com/recaptcha/api.js?render=explicit" async defer></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    let recaptchaWidgetLogin;
    
    const recaptchaCallbackLogin = function(token) {
        const hiddenInput = document.getElementById('recaptcha_token_login');
        if (hiddenInput) {
            hiddenInput.value = token;
        }
    };
    
    const onloadCallback = function() {
        const recaptchaElement = document.getElementById('recaptcha_login_widget');
        if (recaptchaElement && typeof grecaptcha !== 'undefined') {
            recaptchaWidgetLogin = grecaptcha.render(recaptchaElement, {
                'sitekey': '{{ config('recaptcha.site_key') }}',
                'callback': recaptchaCallbackLogin,
                'size': 'normal'
            });
        }
    };
    
    if (typeof grecaptcha !== 'undefined') {
        onloadCallback();
    } else {
        window.recaptchaOnloadCallback = onloadCallback;
    }
});
</script>
@endif
@endsection