@extends('layouts.auth')

@section('content')
<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="mb-6">
        <a href="{{ route('home') }}" class="inline-flex items-center text-purple-600 hover:text-purple-800 font-medium transition" aria-label="Kembali ke Beranda">
            <i class="fas fa-arrow-left"></i>
        </a>
    </div>

    <section class="bg-gradient-to-br from-green-50 to-white p-6 sm:p-8 rounded-xl shadow-sm mb-8">
        <div class="flex flex-col md:flex-row items-start md:items-center gap-6">
            <div class="flex-1 min-w-0">
                <h1 class="text-2xl sm:text-3xl md:text-4xl font-bold text-green-700 leading-tight mb-2">Laporan Diverifikasi 🎉</h1>
                <p class="text-sm sm:text-base text-gray-700">Email kamu berhasil dikonfirmasi. Berikut adalah kode unik untuk melacak status laporanmu.</p>
            </div>
            <div class="hidden md:flex w-20 h-20 flex-shrink-0 rounded-2xl bg-white shadow-inner border border-green-100 items-center justify-center text-3xl text-green-500">
                <i class="fas fa-check-circle"></i>
            </div>
        </div>
    </section>

    @if(session('success'))
    <div class="mb-8 bg-green-50 rounded-xl p-4 sm:p-5 border border-green-200 shadow-sm flex items-start gap-4">
        <div class="text-green-500 text-xl mt-0.5"><i class="fas fa-check-circle"></i></div>
        <div>
            <h3 class="font-semibold text-green-800">Berhasil!</h3>
            <p class="text-sm text-green-700 mt-1">{{ session('success') }}</p>
        </div>
    </div>
    @endif

    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-8">
        <div class="bg-white rounded-lg p-4 sm:p-5 border border-purple-100 shadow-sm">
            <div class="text-xl sm:text-2xl mb-3 text-purple-600"><i class="fas fa-envelope-open-text"></i></div>
            <h3 class="font-semibold text-gray-900 text-sm sm:text-base">Email Notifikasi</h3>
            <p class="text-xs sm:text-sm text-gray-600 mt-2">Salinan kode dikirim ke <strong>{{ $report->email_murid }}</strong>.</p>
        </div>
        <div class="bg-white rounded-lg p-4 sm:p-5 border border-purple-100 shadow-sm">
            <div class="text-xl sm:text-2xl mb-3 text-purple-600"><i class="fas fa-shield-halved"></i></div>
            <h3 class="font-semibold text-gray-900 text-sm sm:text-base">Rahasia & Aman</h3>
            <p class="text-xs sm:text-sm text-gray-600 mt-2">Jangan bagikan kode ini kepada siapapun.</p>
        </div>
        <div class="bg-white rounded-lg p-4 sm:p-5 border border-purple-100 shadow-sm">
            <div class="text-xl sm:text-2xl mb-3 text-purple-600"><i class="fas fa-location-crosshairs"></i></div>
            <h3 class="font-semibold text-gray-900 text-sm sm:text-base">Pantau Status</h3>
            <p class="text-xs sm:text-sm text-gray-600 mt-2">Gunakan kode untuk mengecek progres laporan.</p>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm p-6 sm:p-8 border border-purple-100">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 items-center">
            
            <!-- Kode Section -->
            <div class="bg-gradient-to-br from-purple-50 to-pink-50 border border-purple-200 rounded-2xl p-6 md:p-8 shadow-sm text-center">
                <p class="text-gray-500 text-sm sm:text-base mb-4 font-medium uppercase tracking-wider">Kode Konsultasi</p>
                <div class="bg-white border border-purple-100 rounded-xl py-6 px-4 mb-6 shadow-sm overflow-hidden">
                    <p class="text-4xl sm:text-5xl md:text-6xl font-bold tracking-widest font-mono bg-gradient-to-r from-purple-600 to-pink-600 bg-clip-text text-transparent break-all">
                        {{ $report->tracking_code }}
                    </p>
                </div>
                
                <button onclick="copyCode('{{ $report->tracking_code }}')" id="copyBtn"
                        class="w-full bg-purple-600 hover:bg-purple-700 active:scale-95 text-white font-bold py-3 md:py-4 rounded-xl transition duration-300 flex items-center justify-center gap-2 shadow-md">
                    <i class="fas fa-copy text-lg"></i>
                    <span id="copyText" class="text-base md:text-lg">Salin Kode</span>
                </button>
            </div>
            
            <!-- Info Section -->
            <div class="space-y-6">
                <!-- Data Pengirim -->
                <div class="bg-blue-50 border border-blue-100 rounded-xl p-5 md:p-6 shadow-sm">
                    <div class="flex items-start gap-3 mb-4">
                        <div class="bg-blue-100 w-10 h-10 rounded-lg flex items-center justify-center text-blue-600">
                            <i class="fas fa-user"></i>
                        </div>
                        <div>
                            <h3 class="text-blue-900 font-bold mb-1">Data Pengirim</h3>
                            <div class="space-y-2 mt-3">
                                <div class="flex gap-2 text-sm text-blue-800">
                                    <span class="font-medium w-20 shrink-0 opacity-70">Nama</span>
                                    <span class="break-words min-w-0 font-semibold">{{ $report->nama_murid }}</span>
                                </div>
                                <div class="flex gap-2 text-sm text-blue-800">
                                    <span class="font-medium w-20 shrink-0 opacity-70">Kelas</span>
                                    <span class="font-semibold">{{ $report->kelas }}</span>
                                </div>
                                <div class="flex gap-2 text-sm text-blue-800">
                                    <span class="font-medium w-20 shrink-0 opacity-70">Kategori</span>
                                    <span class="font-semibold uppercase tracking-wider text-xs bg-blue-200 text-blue-800 px-2 py-0.5 rounded">{{ $report->jenis_laporan }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Langkah Selanjutnya -->
                <div class="bg-orange-50 border border-orange-100 rounded-xl p-5 md:p-6 shadow-sm">
                    <div class="flex items-start gap-3">
                        <div class="bg-orange-100 w-10 h-10 rounded-lg flex items-center justify-center text-orange-600">
                            <i class="fas fa-lightbulb"></i>
                        </div>
                        <div>
                            <h3 class="text-orange-900 font-bold mb-3">Langkah Selanjutnya</h3>
                            <ul class="space-y-3 text-sm text-orange-800">
                                <li class="flex items-start gap-2">
                                    <i class="fas fa-chevron-right text-orange-500 mt-1 text-xs"></i>
                                    <span><strong>Salin kode unik</strong> di atas dan simpan baik-baik.</span>
                                </li>
                                <li class="flex items-start gap-2">
                                    <i class="fas fa-chevron-right text-orange-500 mt-1 text-xs"></i>
                                    <span>Gunakan menu <strong>Lacak Laporan</strong> di halaman utama untuk mengecek status.</span>
                                </li>
                                <li class="flex items-start gap-2">
                                    <i class="fas fa-chevron-right text-orange-500 mt-1 text-xs"></i>
                                    <span>Tunggu balasan atau arahan dari guru BK.</span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

        </div>
        
        <div class="mt-8 pt-8 flex items-center justify-center">
            <a href="{{ route('home') }}" style="min-width: 250px;" class="inline-flex items-center justify-center gap-3 px-16 py-4 border border-purple-200 text-purple-700 font-semibold rounded-lg hover:bg-purple-50 transition">
                <i class="fas fa-home"></i> Kembali ke Beranda
            </a>
        </div>
    </div>
</div>

<script>
function copyCode(code) {
    navigator.clipboard.writeText(code).then(() => {
        const btn = document.getElementById('copyBtn');
        const txt = document.getElementById('copyText');
        const icon = btn.querySelector('i');
        
        const origText = txt.textContent;
        const origIcon = icon.className;
        
        btn.classList.replace('bg-purple-600', 'bg-green-500');
        btn.classList.replace('hover:bg-purple-700', 'hover:bg-green-600');
        icon.className = 'fas fa-check text-lg';
        txt.textContent = 'Kode Tersalin!';
        
        setTimeout(() => {
            btn.classList.replace('bg-green-500', 'bg-purple-600');
            btn.classList.replace('hover:bg-green-600', 'hover:bg-purple-700');
            icon.className = origIcon;
            txt.textContent = origText;
        }, 2000);
    }).catch(() => {
        Swal.fire({
            icon: 'error',
            title: 'Gagal Menualin!',
            text: 'Terjadi kesalahan saat menyalin kode. Silakan salin manual.',
            confirmButtonColor: '#9333ea'
        });
    });
}
</script>
@endsection