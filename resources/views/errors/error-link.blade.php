@extends('layouts.auth')

@section('content')
<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">

    <section class="bg-gradient-to-br from-red-50 to-white p-6 sm:p-8 rounded-xl shadow-sm mb-8 border border-red-100">
        <div class="flex flex-col md:flex-row items-start md:items-center gap-6">
            <div class="flex-1 min-w-0">
                <h1 class="text-2xl sm:text-3xl md:text-4xl font-bold text-red-700 leading-tight mb-2">Link Tidak Valid</h1>
                <p class="text-sm sm:text-base text-gray-700">Maaf, terjadi kendala saat memverifikasi link Anda.</p>
            </div>
            <div class="hidden md:flex w-20 h-20 flex-shrink-0 rounded-2xl bg-white shadow-inner border border-red-100 items-center justify-center text-3xl text-red-500">
                <i class="fas fa-exclamation-triangle"></i>
            </div>
        </div>
    </section>

    <!-- Error Message Banner -->
    <div class="mb-8 bg-red-50 rounded-xl p-4 sm:p-5 border border-red-200 shadow-sm flex items-start gap-4">
        <div class="text-red-500 text-xl mt-0.5"><i class="fas fa-times-circle"></i></div>
        <div>
            <h3 class="font-semibold text-red-800">Verifikasi Gagal</h3>
            <p class="text-sm text-red-700 mt-1">{{ session('error', 'Link verifikasi tidak valid atau sudah kadaluarsa.') }}</p>
        </div>
    </div>

    <!-- 3 Columns for Causes -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-8">
        <div class="bg-white rounded-lg p-4 sm:p-5 border border-red-100 shadow-sm">
            <div class="text-xl sm:text-2xl mb-3 text-red-500"><i class="fas fa-hourglass-end"></i></div>
            <h3 class="font-semibold text-gray-900 text-sm sm:text-base">Masa Berlaku Habis</h3>
            <p class="text-xs sm:text-sm text-gray-600 mt-2">Link kedaluwarsa setelah 15 menit dibuat.</p>
        </div>
        <div class="bg-white rounded-lg p-4 sm:p-5 border border-red-100 shadow-sm">
            <div class="text-xl sm:text-2xl mb-3 text-red-500"><i class="fas fa-ban"></i></div>
            <h3 class="font-semibold text-gray-900 text-sm sm:text-base">Sudah Digunakan</h3>
            <p class="text-xs sm:text-sm text-gray-600 mt-2">Link ini sudah pernah diklik sebelumnya.</p>
        </div>
        <div class="bg-white rounded-lg p-4 sm:p-5 border border-red-100 shadow-sm">
            <div class="text-xl sm:text-2xl mb-3 text-red-500"><i class="fas fa-link-slash"></i></div>
            <h3 class="font-semibold text-gray-900 text-sm sm:text-base">URL Terpotong / Server Error</h3>
            <p class="text-xs sm:text-sm text-gray-600 mt-2">Pastikan URL utuh, atau tunggu beberapa saat.</p>
        </div>
    </div>

    <!-- Main Section: Solutions & Action -->
    <div class="bg-white rounded-xl shadow-sm p-6 sm:p-8 border border-red-100">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 items-stretch">
            
            <!-- Rekomendasi Solusi -->
            <div class="bg-orange-50 border border-orange-100 rounded-xl p-5 md:p-6 shadow-sm">
                <div class="flex items-start gap-3 mb-4">
                    <div class="bg-orange-100 w-10 h-10 rounded-lg flex items-center justify-center text-orange-600">
                        <i class="fas fa-lightbulb"></i>
                    </div>
                    <div class="mt-1">
                        <h3 class="text-orange-900 font-bold mb-1">Rekomendasi Solusi</h3>
                    </div>
                </div>
                
                <ul class="space-y-4 pl-2 mt-4 text-sm text-orange-800">
                    <li class="flex items-start gap-3">
                        <span class="bg-orange-200 text-orange-700 rounded-full w-6 h-6 flex items-center justify-center text-xs font-bold shrink-0">1</span>
                        <div class="leading-relaxed">
                            <span class="font-semibold block text-orange-900 mb-1">Isi Ulang Form (Daftar Ulang)</span>
                            Mengingat link ini sudah hangus, cara paling tepat adalah membuat laporan baru dari awal.
                        </div>
                    </li>
                    <li class="flex items-start gap-3">
                        <span class="bg-orange-200 text-orange-700 rounded-full w-6 h-6 flex items-center justify-center text-xs font-bold shrink-0">2</span>
                        <div class="leading-relaxed">
                            <span class="font-semibold block text-orange-900 mb-1">Segera Verifikasi</span>
                            Saat menerima email baru nanti, pastikan Anda mengklik link sebelum masa berlaku 15 menit habis.
                        </div>
                    </li>
                    <li class="flex items-start gap-3">
                        <span class="bg-orange-200 text-orange-700 rounded-full w-6 h-6 flex items-center justify-center text-xs font-bold shrink-0">3</span>
                        <div class="leading-relaxed">
                            <span class="font-semibold block text-orange-900 mb-1">Hubungi Tim BK</span>
                            Jika situs terus mengalami eror sistem (<a href="mailto:support@sistemcinta.id" class="underline text-orange-700">support@sistemcinta.id</a>), Anda dapat mencatat laporan manual kepada guru.
                        </div>
                    </li>
                </ul>
            </div>

            <!-- Action Area -->
            <div class="bg-gradient-to-br from-red-50 to-orange-50 border border-red-100 rounded-xl p-6 shadow-sm flex flex-col justify-center items-center text-center">
                <i class="fas fa-power-off text-6xl text-red-200 mb-6 font-light"></i>
                <h3 class="text-gray-800 font-bold text-lg mb-2">Tindakan Selanjutnya</h3>
                <p class="text-gray-600 text-sm mb-8 px-4">Anda dapat kembali ke halaman utama untuk membuat laporan konseling baru.</p>
                
                <div class="w-full space-y-3 px-4">
                    <a href="{{ route('home') }}" 
                       class="w-full bg-purple-600 hover:bg-purple-700 active:scale-95 text-white font-bold py-3 md:py-4 rounded-xl transition duration-300 flex items-center justify-center gap-2 shadow-sm">
                        <i class="fas fa-file-circle-plus text-lg"></i>
                        <span class="text-base">Buat Laporan Baru</span>
                    </a>
                </div>
            </div>

        </div>
        
        <div class="mt-8 pt-8 flex items-center justify-center">
            <a href="{{ route('home') }}" style="min-width: 250px;" class="inline-flex items-center justify-center gap-3 px-16 py-4 border border-red-200 text-red-700 font-semibold rounded-lg hover:bg-red-50 transition">
                <i class="fas fa-home"></i> Kembali ke Beranda
            </a>
        </div>
    </div>
</div>
@endsection
