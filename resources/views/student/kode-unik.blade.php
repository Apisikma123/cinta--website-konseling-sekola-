@extends('layouts.auth')

@section('content')
<div class="max-w-2xl mx-auto px-4 py-2">

    {{-- ── Back ── --}}
    <div class="mb-5">
        <a href="{{ route('home') }}"
           class="inline-flex items-center gap-2 text-purple-600 hover:text-purple-800 font-medium text-sm transition">
            <i class="fas fa-arrow-left text-xs"></i> Beranda
        </a>
    </div>

    {{-- ── Hero banner ── --}}
    <div class="bg-gradient-to-br from-green-50 via-emerald-50 to-white rounded-2xl p-5 sm:p-7 mb-6 border border-green-100 shadow-sm flex items-center gap-4">
        <div class="w-12 h-12 sm:w-14 sm:h-14 rounded-2xl bg-green-100 flex items-center justify-center text-green-600 flex-shrink-0">
            <i class="fas fa-check-circle text-xl sm:text-2xl"></i>
        </div>
        <div class="min-w-0">
            <h1 class="text-xl sm:text-2xl font-bold text-green-700 leading-tight">Laporan Diverifikasi 🎉</h1>
            <p class="text-xs sm:text-sm text-gray-600 mt-1">Email dikonfirmasi. Simpan kode unik di bawah ini.</p>
        </div>
    </div>

    @if(session('success'))
    <div class="mb-6 bg-green-50 rounded-xl p-4 border border-green-200 flex items-start gap-3">
        <i class="fas fa-check-circle text-green-500 mt-0.5 flex-shrink-0"></i>
        <div class="min-w-0">
            <p class="font-semibold text-green-800 text-sm">Berhasil!</p>
            <p class="text-xs text-green-700 mt-0.5">{{ session('success') }}</p>
        </div>
    </div>
    @endif

    {{-- ── Kode Unik Card ── --}}
    <div class="bg-gradient-to-br from-purple-50 to-pink-50 border border-purple-200 rounded-2xl p-5 sm:p-7 shadow-sm mb-6 text-center">
        <p class="text-gray-500 text-xs font-bold uppercase tracking-widest mb-4">Kode Konsultasi Kamu</p>

        {{-- Kode box — responsive, no overflow --}}
        <div class="bg-white border border-purple-100 rounded-xl py-5 px-3 mb-5 shadow-inner overflow-hidden">
            <p class="font-mono font-bold bg-gradient-to-r from-purple-600 to-pink-600 bg-clip-text text-transparent
                       break-all text-center select-all leading-tight
                       text-2xl sm:text-3xl tracking-widest">
                {{ $report->tracking_code }}
            </p>
        </div>

        <button onclick="copyCode('{{ $report->tracking_code }}')" id="copyBtn"
                class="w-full bg-purple-600 hover:bg-purple-700 active:scale-95 text-white font-bold
                       py-3 rounded-xl transition duration-200 flex items-center justify-center gap-2 shadow-md text-sm sm:text-base">
            <i class="fas fa-copy" id="copyIcon"></i>
            <span id="copyText">Salin Kode</span>
        </button>
    </div>

    {{-- ── Info 3 kolom → mobile 1 kolom ── --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 mb-6">
        <div class="bg-white rounded-xl p-4 border border-purple-100 shadow-sm flex items-start gap-3">
            <div class="w-9 h-9 rounded-lg bg-purple-50 flex items-center justify-center text-purple-600 flex-shrink-0">
                <i class="fas fa-envelope-open-text text-sm"></i>
            </div>
            <div class="min-w-0">
                <p class="font-semibold text-gray-900 text-sm">Email Notifikasi</p>
                <p class="text-xs text-gray-500 mt-0.5 break-all">Salinan dikirim ke <strong>{{ $report->email_murid }}</strong>.</p>
            </div>
        </div>
        <div class="bg-white rounded-xl p-4 border border-purple-100 shadow-sm flex items-start gap-3">
            <div class="w-9 h-9 rounded-lg bg-purple-50 flex items-center justify-center text-purple-600 flex-shrink-0">
                <i class="fas fa-shield-halved text-sm"></i>
            </div>
            <div class="min-w-0">
                <p class="font-semibold text-gray-900 text-sm">Rahasia & Aman</p>
                <p class="text-xs text-gray-500 mt-0.5">Jangan bagikan kode ini kepada siapapun.</p>
            </div>
        </div>
        <div class="bg-white rounded-xl p-4 border border-purple-100 shadow-sm flex items-start gap-3">
            <div class="w-9 h-9 rounded-lg bg-purple-50 flex items-center justify-center text-purple-600 flex-shrink-0">
                <i class="fas fa-location-crosshairs text-sm"></i>
            </div>
            <div class="min-w-0">
                <p class="font-semibold text-gray-900 text-sm">Pantau Status</p>
                <p class="text-xs text-gray-500 mt-0.5">Gunakan kode untuk mengecek progres laporan.</p>
            </div>
        </div>
    </div>

    {{-- ── Data Pengirim ── --}}
    <div class="bg-blue-50 border border-blue-100 rounded-xl p-4 sm:p-5 mb-4 shadow-sm">
        <div class="flex items-center gap-3 mb-3">
            <div class="w-9 h-9 rounded-lg bg-blue-100 flex items-center justify-center text-blue-600 flex-shrink-0">
                <i class="fas fa-user text-sm"></i>
            </div>
            <h3 class="text-blue-900 font-bold text-sm">Data Pengirim</h3>
        </div>
        <div class="grid grid-cols-2 sm:grid-cols-3 gap-y-2 gap-x-4 text-sm text-blue-800">
            <div>
                <p class="text-xs text-blue-500 font-medium mb-0.5">Nama</p>
                <p class="font-semibold break-words">{{ $report->nama_murid }}</p>
            </div>
            <div>
                <p class="text-xs text-blue-500 font-medium mb-0.5">Kelas</p>
                <p class="font-semibold">{{ $report->kelas }}</p>
            </div>
            <div>
                <p class="text-xs text-blue-500 font-medium mb-0.5">Kategori</p>
                <span class="inline-block bg-blue-200 text-blue-800 text-xs font-bold px-2 py-0.5 rounded uppercase tracking-wide">
                    {{ $report->jenis_laporan }}
                </span>
            </div>
        </div>
    </div>

    {{-- ── Langkah Selanjutnya ── --}}
    <div class="bg-orange-50 border border-orange-100 rounded-xl p-4 sm:p-5 mb-6 shadow-sm">
        <div class="flex items-center gap-3 mb-3">
            <div class="w-9 h-9 rounded-lg bg-orange-100 flex items-center justify-center text-orange-600 flex-shrink-0">
                <i class="fas fa-lightbulb text-sm"></i>
            </div>
            <h3 class="text-orange-900 font-bold text-sm">Langkah Selanjutnya</h3>
        </div>
        <ul class="space-y-2 text-sm text-orange-800">
            <li class="flex items-start gap-2">
                <i class="fas fa-chevron-right text-orange-400 mt-1 text-xs flex-shrink-0"></i>
                <span><strong>Salin kode unik</strong> di atas dan simpan baik-baik.</span>
            </li>
            <li class="flex items-start gap-2">
                <i class="fas fa-chevron-right text-orange-400 mt-1 text-xs flex-shrink-0"></i>
                <span>Gunakan menu <strong>Lacak Laporan</strong> di halaman utama untuk mengecek status.</span>
            </li>
            <li class="flex items-start gap-2">
                <i class="fas fa-chevron-right text-orange-400 mt-1 text-xs flex-shrink-0"></i>
                <span>Tunggu balasan atau arahan dari guru BK.</span>
            </li>
        </ul>
    </div>

    {{-- ── CTA ── --}}
    <a href="{{ route('home') }}"
       class="w-full flex items-center justify-center gap-2 py-3.5 border border-purple-200
              text-purple-700 font-semibold rounded-xl hover:bg-purple-50 transition text-sm sm:text-base">
        <i class="fas fa-home text-sm"></i> Kembali ke Beranda
    </a>

</div>

<script>
function copyCode(code) {
    navigator.clipboard.writeText(code).then(() => {
        const btn  = document.getElementById('copyBtn');
        const txt  = document.getElementById('copyText');
        const icon = document.getElementById('copyIcon');

        btn.classList.replace('bg-purple-600','bg-green-500');
        btn.classList.replace('hover:bg-purple-700','hover:bg-green-600');
        icon.className = 'fas fa-check';
        txt.textContent = 'Kode Tersalin!';

        setTimeout(() => {
            btn.classList.replace('bg-green-500','bg-purple-600');
            btn.classList.replace('hover:bg-green-600','hover:bg-purple-700');
            icon.className = 'fas fa-copy';
            txt.textContent = 'Salin Kode';
        }, 2000);
    }).catch(() => {
        // Fallback: select text manually
        const el = document.createElement('textarea');
        el.value = code;
        document.body.appendChild(el);
        el.select();
        document.execCommand('copy');
        document.body.removeChild(el);
    });
}
</script>
@endsection