@extends('layouts.student')

@section('content')
<div class="max-w-md mx-auto px-4 py-8">
    <!-- Header -->
    <div class="text-center mb-8">
        <div class="bg-gradient-to-br from-purple-100 to-pink-100 rounded-full w-16 h-16 flex items-center justify-center mx-auto mb-4">
            <i class="fas fa-comments text-purple-600 text-2xl"></i>
        </div>
        <h1 class="text-2xl font-bold text-gray-800 mb-2">Daftar Konseling</h1>
        <p class="text-gray-600 text-sm">Silakan isi form di bawah untuk mendaftar konseling dengan guru BK</p>
    </div>

    <!-- Form -->
    <div class="bg-white rounded-lg border border-purple-100 p-6 shadow-sm">
        <form method="POST" action="{{ route('konseling.submit') }}" class="space-y-4">
            @csrf

            <!-- Name Field -->
            <div>
                <label for="name" class="block text-sm font-semibold text-gray-700 mb-2">
                    <i class="fas fa-user text-purple-600 mr-2"></i> Nama Lengkap
                </label>
                <input type="text" 
                       id="name" 
                       name="name" 
                       value="{{ old('name') }}"
                       placeholder="Masukkan nama lengkap Anda"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 transition @error('name') border-red-500 @enderror"
                       required>
                @error('name')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Email Field -->
            <div>
                <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">
                    <i class="fas fa-envelope text-purple-600 mr-2"></i> Email
                </label>
                <input type="email" 
                       id="email" 
                       name="email" 
                       value="{{ old('email') }}"
                       placeholder="Masukkan email Anda"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 transition @error('email') border-red-500 @enderror"
                       required>
                @error('email')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- School Field -->
            <div>
                <label for="school" class="block text-sm font-semibold text-gray-700 mb-2">
                    <i class="fas fa-school text-purple-600 mr-2"></i> Sekolah
                </label>
                <input type="text" 
                       id="school" 
                       name="school" 
                       value="{{ old('school') }}"
                       placeholder="Nama sekolah Anda"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 transition @error('school') border-red-500 @enderror"
                       required>
                @error('school')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Class Field -->
            <div>
                <label for="class" class="block text-sm font-semibold text-gray-700 mb-2">
                    <i class="fas fa-graduation-cap text-purple-600 mr-2"></i> Kelas
                </label>
                <input type="text" 
                       id="class" 
                       name="class" 
                       value="{{ old('class') }}"
                       placeholder="Contoh: 10 A atau XI IPA 1"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 transition @error('class') border-red-500 @enderror"
                       required>
                @error('class')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Message Field -->
            <div>
                <label for="message" class="block text-sm font-semibold text-gray-700 mb-2">
                    <i class="fas fa-pen-fancy text-purple-600 mr-2"></i> Isi Konsultasi
                </label>
                <textarea id="message" 
                          name="message" 
                          rows="4"
                          placeholder="Ceritakan masalah atau topik konsultasi Anda (minimal 10 karakter)"
                          class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-purple-500 transition @error('message') border-red-500 @enderror"
                          required></textarea>
                @error('message')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Info Box -->
            <div class="bg-blue-50 border border-blue-200 rounded-lg p-3 text-xs text-blue-700">
                <i class="fas fa-info-circle mr-2"></i>
                <span>Setelah mendaftar, kami akan mengirimkan link verifikasi ke email Anda. Silakan cek inbox Anda.</span>
            </div>

            <!-- Submit Button -->
            <button type="submit"
                    id="submitBtn"
                    class="w-full bg-gradient-to-r from-purple-600 to-pink-600 hover:bg-purple-700 text-white font-semibold py-3 px-4 rounded-lg transition duration-200 flex items-center justify-center">
                <i class="fas fa-paper-plane mr-2"></i> Daftar Konseling
            </button>
        </form>
    </div>

    <!-- Help Section -->
    <div class="mt-8 text-center space-y-3">
        <p class="text-sm text-gray-600">
            Sudah memiliki kode unik? <a href="{{ route('home') }}" class="text-purple-600 hover:text-purple-700 font-semibold">Masuk di sini</a>
        </p>
        <a href="{{ route('home') }}" class="inline-block text-purple-600 hover:text-purple-700 font-semibold text-sm">
            <i class="fas fa-arrow-left mr-1"></i> Kembali ke Beranda
        </a>
    </div>
</div>

<!-- Form Submission Handler -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('form[action="{{ route("konseling.submit") }}"]');
    const submitBtn = document.getElementById('submitBtn');

    if (form) {
        form.addEventListener('submit', function(e) {
            // Prevent double submission
            if (submitBtn.disabled) {
                e.preventDefault();
                return false;
            }

            // Disable button dan update UI
            submitBtn.disabled = true;
            submitBtn.style.opacity = '0.6';
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Mengirim...';

            // Submit form (native, 1x only)
            return true;
        });
    }
});
</script>
@endsection
