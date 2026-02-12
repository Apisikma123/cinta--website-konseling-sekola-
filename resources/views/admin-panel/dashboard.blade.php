@extends('layouts.admin-panel', ['title' => 'Dashboard'])

@section('content')
<div class="space-y-8">
    <!-- Page Header -->
    <div>
        <h2 class="text-2xl font-bold text-gray-900">Selamat Datang Kembali</h2>
        <p class="text-gray-600 mt-1">Berikut adalah ringkasan aktivitas terbaru Anda.</p>
    </div>

    <!-- Stats Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <x-admin-panel.stat-card 
            label="Total Laporan" 
            value="24" 
            icon="file-text"
            subtitle="Bulan ini" />
        
        <x-admin-panel.stat-card 
            label="Laporan Diproses" 
            value="8" 
            icon="clock"
            subtitle="Menunggu" />
        
        <x-admin-panel.stat-card 
            label="Laporan Selesai" 
            value="16" 
            icon="check-circle"
            subtitle="Bulan ini" />
        
        <x-admin-panel.stat-card 
            label="Tingkat Penyelesaian" 
            value="67%" 
            icon="trending-up"
            subtitle="Rata-rata" />
    </div>

    <!-- Main Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Recent Reports -->
        <div class="lg:col-span-2 bg-white rounded-lg border border-gray-200 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50">
                <h3 class="text-lg font-semibold text-gray-900">Laporan Terbaru</h3>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="border-b border-gray-200">
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Siswa</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Tanggal</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        <tr class="hover:bg-gray-50 transition-colors duration-200">
                            <td class="px-6 py-4 text-sm text-gray-900">Budi Santoso</td>
                            <td class="px-6 py-4 text-sm text-gray-600">15 Feb 2026</td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                    Diproses
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm">
                                <a href="#" class="text-purple-600 hover:text-purple-700 font-medium">Lihat</a>
                            </td>
                        </tr>
                        <tr class="hover:bg-gray-50 transition-colors duration-200">
                            <td class="px-6 py-4 text-sm text-gray-900">Siti Nurhaliza</td>
                            <td class="px-6 py-4 text-sm text-gray-600">14 Feb 2026</td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800">
                                    Selesai
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm">
                                <a href="#" class="text-purple-600 hover:text-purple-700 font-medium">Lihat</a>
                            </td>
                        </tr>
                        <tr class="hover:bg-gray-50 transition-colors duration-200">
                            <td class="px-6 py-4 text-sm text-gray-900">Ahmad Wijaya</td>
                            <td class="px-6 py-4 text-sm text-gray-600">13 Feb 2026</td>
                            <td class="px-6 py-4">
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800">
                                    Selesai
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm">
                                <a href="#" class="text-purple-600 hover:text-purple-700 font-medium">Lihat</a>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="px-6 py-4 border-t border-gray-200 bg-gray-50">
                <a href="#" class="text-sm font-medium text-purple-600 hover:text-purple-700">Lihat Semua Laporan →</a>
            </div>
        </div>

        <!-- Quick Stats -->
        <div class="space-y-6">
            <!-- Activity -->
            <div class="bg-white rounded-lg border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Aktivitas Hari Ini</h3>
                <div class="space-y-4">
                    <div class="flex items-start gap-3">
                        <div class="w-2 h-2 rounded-full bg-purple-600 mt-2 flex-shrink-0"></div>
                        <div>
                            <p class="text-sm font-medium text-gray-900">Laporan baru diterima</p>
                            <p class="text-xs text-gray-500">10:30 AM</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-3">
                        <div class="w-2 h-2 rounded-full bg-emerald-600 mt-2 flex-shrink-0"></div>
                        <div>
                            <p class="text-sm font-medium text-gray-900">Laporan diselesaikan</p>
                            <p class="text-xs text-gray-500">09:15 AM</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-3">
                        <div class="w-2 h-2 rounded-full bg-blue-600 mt-2 flex-shrink-0"></div>
                        <div>
                            <p class="text-sm font-medium text-gray-900">Profil diperbarui</p>
                            <p class="text-xs text-gray-500">Kemarin</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="bg-white rounded-lg border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Aksi Cepat</h3>
                <div class="space-y-3">
                    <a href="#" class="flex items-center gap-3 p-3 rounded-lg bg-purple-50 hover:bg-purple-100 transition-colors duration-200">
                        <i data-feather="plus-circle" class="w-5 h-5 text-purple-600 flex-shrink-0"></i>
                        <span class="text-sm font-medium text-purple-700">Laporan Baru</span>
                    </a>
                    <a href="#" class="flex items-center gap-3 p-3 rounded-lg bg-gray-50 hover:bg-gray-100 transition-colors duration-200">
                        <i data-feather="message-square" class="w-5 h-5 text-gray-600 flex-shrink-0"></i>
                        <span class="text-sm font-medium text-gray-700">Pesan</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
