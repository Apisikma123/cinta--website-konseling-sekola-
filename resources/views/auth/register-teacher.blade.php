@extends('layouts.auth')

@section('title', 'Daftar Guru Baru')

@section('content')
<form method="POST" action="{{ route('register.teacher') }}" id="registerForm">
    @csrf
    <div class="space-y-5">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">
                <i class="fas fa-user mr-2 text-purple-600"></i>Nama Lengkap
            </label>
            <input type="text" name="name" required value="{{ old('name') }}"
                   class="w-full px-4 py-3 rounded-lg border border-purple-200 focus:ring-2 focus:ring-purple-300 focus:border-transparent outline-none transition"
                   placeholder="Nama Anda">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">
                <i class="fas fa-envelope mr-2 text-purple-600"></i>Email
            </label>
            <input type="email" name="email" required value="{{ old('email') }}"
                   class="w-full px-4 py-3 rounded-lg border border-purple-200 focus:ring-2 focus:ring-purple-300 focus:border-transparent outline-none transition"
                   placeholder="guru@sekolah.sch.id">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">
                <i class="fas fa-school mr-2 text-purple-600"></i>Sekolah
            </label>
            <select name="school_id" required
                    class="w-full px-4 py-3 rounded-lg border border-purple-200 focus:ring-2 focus:ring-purple-300 focus:border-transparent outline-none transition">
                <option value="">Pilih sekolah</option>
                @foreach($schools as $school)
                    <option value="{{ $school->id }}" {{ old('school_id') == $school->id ? 'selected' : '' }}>
                        {{ $school->name }}@if($school->city) - {{ $school->city }}@endif
                    </option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">
                <i class="fab fa-whatsapp mr-2 text-green-600"></i>Nomor WhatsApp
            </label>
            <input type="text" name="whatsapp" required value="{{ old('whatsapp') }}"
                   class="w-full px-4 py-3 rounded-lg border border-purple-200 focus:ring-2 focus:ring-purple-300 focus:border-transparent outline-none transition"
                   placeholder="628123456789">
            <p class="text-xs text-gray-500 mt-1">Gunakan format 628xxxx (tanpa spasi).</p>
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">
                <i class="fas fa-key mr-2 text-purple-600"></i>Jawaban Rahasia
            </label>
            <input type="password" name="verification_answer" required
                   class="w-full px-4 py-3 rounded-lg border border-purple-200 focus:ring-2 focus:ring-purple-300 focus:border-transparent outline-none transition"
                   placeholder="Hanya guru yang tahu">
            <p class="text-xs text-gray-500 mt-1">Kami akan verifikasi identitas Anda sebagai guru</p>
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">
                <i class="fas fa-lock mr-2 text-purple-600"></i>Password
            </label>
            <input type="password" name="password" required minlength="6"
                   class="w-full px-4 py-3 rounded-lg border border-purple-200 focus:ring-2 focus:ring-purple-300 focus:border-transparent outline-none transition"
                   placeholder="Minimal 6 karakter">
        </div>

        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">
                <i class="fas fa-lock mr-2 text-purple-600"></i>Konfirmasi Password
            </label>
            <input id="password_confirmation" type="password" name="password_confirmation" required
                   class="w-full px-4 py-3 rounded-lg border border-purple-200 focus:ring-2 focus:ring-purple-300 focus:border-transparent outline-none transition"
                   placeholder="Ulangi password">
        </div>

        <div class="flex items-center space-x-2 text-sm">
            <input id="show-password-register" type="checkbox" class="h-4 w-4 text-purple-600 border-gray-300 rounded" />
            <label for="show-password-register" class="text-gray-700">Tampilkan password</label>
        </div>

        <!-- Hidden reCAPTCHA token field -->
        @if(config('recaptcha.enabled', true))
        <input type="hidden" name="recaptcha_token" id="recaptcha_token_register">
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
                 id="recaptcha_register_widget">
            </div>
        </div>
        @endif

        <button type="submit" id="register-btn" class="w-full justify-center inline-flex items-center bg-purple-600 hover:bg-purple-700 active:scale-95 text-white font-bold py-3 px-4 rounded-lg transition duration-200">
            <i class="fas fa-user-check mr-2"></i> <span id="registerBtnText">Daftar Sekarang</span>
        </button>

        <!-- Loading Modal -->
        <div id="registerLoadingModal" class="hidden fixed inset-0 bg-white z-50" style="display: none;">
            <div class="flex items-center justify-center h-full">
                <x-loading message="Memproses pendaftaran..." />
            </div>
        </div>
    </div>
</form>

<div class="text-center mt-6">
    <a href="{{ route('login') }}" class="text-purple-600 hover:text-purple-800 text-sm">
        <i class="fas fa-arrow-left mr-1"></i> Sudah punya akun? Login
    </a>
</div>

<script>
    (function () {
        const toggle = document.getElementById('show-password-register');
        const password = document.querySelector('input[name="password"]');
        const confirm = document.getElementById('password_confirmation');

        toggle?.addEventListener('change', function () {
            const t = this.checked ? 'text' : 'password';
            if (password) password.type = t;
            if (confirm) confirm.type = t;
        });
    })();

    (function () {
        const form = document.getElementById('registerForm');
        const registerBtn = document.getElementById('register-btn');
        const loadingModal = document.getElementById('registerLoadingModal');

        form?.addEventListener('submit', function() {
            loadingModal.classList.remove('hidden');
            registerBtn.disabled = true;
        });
    })();

    // Display validation errors with SweetAlert
    document.addEventListener('DOMContentLoaded', function() {
        @if ($errors->any())
            const errors = {!! json_encode($errors->all()) !!};
            const errorMessage = errors.length > 0 ? errors[0] : 'Terjadi kesalahan saat mendaftar';
            
            Swal.fire({
                icon: 'error',
                title: 'Validasi Gagal!',
                text: errorMessage,
                confirmButtonColor: '#9333ea',
                confirmButtonText: 'Coba Lagi'
            }).then(() => {
                document.getElementById('registerLoadingModal').classList.add('hidden');
                document.getElementById('register-btn').disabled = false;
            });
        @endif

        @if (session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Pendaftaran Berhasil!',
                text: '{{ session('success') }}',
                confirmButtonColor: '#9333ea',
                didClose: () => {
                    window.location.href = '{{ route('login') }}';
                }
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
    let recaptchaWidgetRegister;
    
    const recaptchaCallbackRegister = function(token) {
        const hiddenInput = document.getElementById('recaptcha_token_register');
        if (hiddenInput) {
            hiddenInput.value = token;
        }
    };
    
    const onloadCallback = function() {
        const recaptchaElement = document.getElementById('recaptcha_register_widget');
        if (recaptchaElement && typeof grecaptcha !== 'undefined') {
            recaptchaWidgetRegister = grecaptcha.render(recaptchaElement, {
                'sitekey': '{{ config('recaptcha.site_key') }}',
                'callback': recaptchaCallbackRegister,
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