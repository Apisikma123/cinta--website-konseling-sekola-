@extends('layouts.auth')

@section('title', 'Verifikasi Berhasil')

@section('content')
<div class="max-w-md mx-auto text-center bg-white p-8 rounded-lg shadow">
    <div class="mx-auto mb-4 w-16 h-16 flex items-center justify-center rounded-full bg-green-100">
        <svg xmlns="http://www.w3.org/2000/svg" class="w-10 h-10 text-green-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M20 6 9 17l-5-5" />
        </svg>
    </div>

    <h1 class="text-2xl font-bold text-gray-900">Selamat, {{ $name }}!</h1>
    <p class="mt-3 text-gray-600">Email Anda berhasil diverifikasi.</p>

    <div class="mt-4 text-gray-700">
        <p>Akun Anda saat ini <strong>menunggu persetujuan admin</strong>. Tim admin biasanya akan memproses dalam beberapa hari.</p>
        <p class="mt-2">Anda akan diberi tahu jika akun telah disetujui. Jika perlu, silakan hubungi admin sekolah Anda.</p>
    </div>

    <div class="mt-6 space-y-2 text-sm text-gray-600">
        @php $contact = env('ADMIN_CONTACT_EMAIL') ?: config('mail.from.address'); @endphp
        @if($contact)
            <p>Butuh bantuan? Hubungi admin di <span class="font-semibold text-purple-700">{{ $contact }}</span>.</p>
        @endif
        <p>Anda dapat login setelah akun disetujui oleh admin.</p>
    </div>

    <p class="text-xs text-gray-400 mt-4">Terima kasih telah mendaftar.</p>
</div>

<script>
    (function () {
        // show a small toast confirming verification
        const msg = 'Email berhasil diverifikasi. Menunggu persetujuan admin.';
        if (typeof window.showToast === 'function') {
            window.showToast(msg, 'success');
        } else {
            // fallback small inline toast
            const c = document.createElement('div');
            c.className = 'px-4 py-2 rounded shadow fixed top-4 right-4 bg-green-600 text-white';
            c.textContent = msg;
            document.body.appendChild(c);
            setTimeout(() => c.remove(), 4000);
        }
    })();
</script>

@endsection