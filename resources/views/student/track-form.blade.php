@extends('layouts.auth')

@section('content')
<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="mb-6">
        <a href="/" class="inline-flex items-center text-purple-600 hover:text-purple-800 font-medium transition" aria-label="Kembali">
            <i class="fas fa-arrow-left"></i>
        </a>
    </div>

    <section class="bg-gradient-to-br from-purple-50 to-white p-6 sm:p-8 rounded-xl shadow-sm mb-8">
        <div class="flex flex-col md:flex-row items-start md:items-center gap-6">
            <div class="flex-1 min-w-0">
                <h1 class="text-2xl sm:text-3xl md:text-4xl font-bold text-purple-800 leading-tight mb-2">Lacak Laporan</h1>
                <p class="text-sm sm:text-base text-gray-700">Masukkan kode unik untuk melihat status laporanmu.</p>
            </div>
            <div class="hidden md:flex w-20 h-20 flex-shrink-0 rounded-2xl bg-white shadow-inner border border-purple-100 items-center justify-center text-3xl text-purple-600">
                <i class="fas fa-magnifying-glass"></i>
            </div>
        </div>
    </section>

    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-8">
        <div class="bg-white rounded-lg p-4 sm:p-5 border border-purple-100 shadow-sm">
            <div class="text-xl sm:text-2xl mb-3 text-purple-600"><i class="fas fa-key"></i></div>
            <h3 class="font-semibold text-gray-900 text-sm sm:text-base">Kode Unik</h3>
            <p class="text-xs sm:text-sm text-gray-600 mt-2">Gunakan kode dari laporanmu.</p>
        </div>
        <div class="bg-white rounded-lg p-4 sm:p-5 border border-purple-100 shadow-sm">
            <div class="text-xl sm:text-2xl mb-3 text-purple-600"><i class="fas fa-chart-line"></i></div>
            <h3 class="font-semibold text-gray-900 text-sm sm:text-base">Status Terbaru</h3>
            <p class="text-xs sm:text-sm text-gray-600 mt-2">Pantau progres secara real-time.</p>
        </div>
        <div class="bg-white rounded-lg p-4 sm:p-5 border border-purple-100 shadow-sm">
            <div class="text-xl sm:text-2xl mb-3 text-purple-600"><i class="fas fa-comments"></i></div>
            <h3 class="font-semibold text-gray-900 text-sm sm:text-base">Komunikasi</h3>
            <p class="text-xs sm:text-sm text-gray-600 mt-2">Hubungi guru BK bila perlu.</p>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm p-6 sm:p-8 border border-purple-100">
        <form id="trackForm" class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-ticket mr-2 text-purple-600"></i>Kode Laporan
                </label>
                <input type="text" name="tracking_code" required
                       class="w-full px-4 py-3 rounded-lg border border-purple-200 focus:ring-2 focus:ring-purple-300 focus:border-transparent outline-none transition text-sm"
                       placeholder="Contoh: ABC123">
            </div>

            <button type="submit"
                    class="w-full bg-purple-600 hover:bg-purple-700 active:scale-95 text-white font-bold py-3 px-4 rounded-lg transition-all duration-300 flex items-center justify-center">
                <i class="fas fa-search mr-2"></i> <span>Cari Laporan</span>
            </button>
        </form>
    </div>
</div>

<script>
document.getElementById('trackForm').addEventListener('submit', function (e) {
    e.preventDefault();
    const code = this.tracking_code.value.trim();
    if (!code) return;
    
    // Show loading
    const loadingOverlay = document.getElementById('loadingOverlay');
    if (loadingOverlay) {
        loadingOverlay.classList.remove('hidden');
    }
    
    window.location.href = `/track/${encodeURIComponent(code)}`;
});
</script>

    <!-- Loading Modal -->
    <div id="loadingOverlay" class="fixed inset-0 bg-white flex items-center justify-center z-50 hidden">
        <x-loading message="Mencari laporan..." />
    </div>

@endsection