@extends('layouts.admin-panel', ['title' => 'Data Guru'])

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Data Guru BK</h2>
            <p class="text-gray-600 mt-1">Kelola semua guru bimbingan konseling di sistem.</p>
        </div>
        <a href="#" class="inline-flex items-center justify-center gap-2 px-6 py-2 bg-purple-600 text-white rounded-lg font-medium hover:bg-purple-700 transition-colors duration-200">
            <i data-feather="plus" class="w-5 h-5"></i>
            <span>Tambah Guru</span>
        </a>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-lg border border-gray-200 p-4">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Cari Nama</label>
                <input type="text" placeholder="Ketik nama guru..." class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent text-base">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Sekolah</label>
                <select class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent text-base">
                    <option>Semua Sekolah</option>
                    <option>SMA Negeri 1</option>
                    <option>SMA Negeri 2</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                <select class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent text-base">
                    <option>Semua Status</option>
                    <option>Aktif</option>
                    <option>Nonaktif</option>
                </select>
            </div>
            <div class="flex items-end">
                <button class="w-full px-4 py-2 bg-gray-100 text-gray-700 rounded-lg font-medium hover:bg-gray-200 transition-colors duration-200">
                    Filter
                </button>
            </div>
        </div>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-gray-50 border-b border-gray-200">
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Nama Guru</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Email</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Sekolah</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    <tr class="hover:bg-gray-50 transition-colors duration-200">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-lg bg-purple-100 text-purple-600 flex items-center justify-center font-bold text-sm">
                                    DR
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-900">Dr. Rini Suryani</p>
                                    <p class="text-xs text-gray-500">ID: 12345</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600">rini.suryani@email.com</td>
                        <td class="px-6 py-4 text-sm text-gray-600">SMA Negeri 1 Jakarta</td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800">
                                Aktif
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-2">
                                <a href="#" class="inline-flex items-center gap-2 px-3 py-1 text-sm font-medium text-blue-600 hover:bg-blue-50 rounded-lg transition-colors duration-200">
                                    <i data-feather="edit-2" class="w-4 h-4"></i>
                                    Edit
                                </a>
                                <button @click="$dispatch('delete-confirm', { id: 1, name: 'Dr. Rini Suryani' })" 
                                        class="inline-flex items-center gap-2 px-3 py-1 text-sm font-medium text-red-600 hover:bg-red-50 rounded-lg transition-colors duration-200">
                                    <i data-feather="trash-2" class="w-4 h-4"></i>
                                    Hapus
                                </button>
                            </div>
                        </td>
                    </tr>

                    <tr class="hover:bg-gray-50 transition-colors duration-200">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-lg bg-blue-100 text-blue-600 flex items-center justify-center font-bold text-sm">
                                    AW
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-900">Andi Wijaya</p>
                                    <p class="text-xs text-gray-500">ID: 12346</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600">andi.wijaya@email.com</td>
                        <td class="px-6 py-4 text-sm text-gray-600">SMA Negeri 2 Jakarta</td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-emerald-100 text-emerald-800">
                                Aktif
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-2">
                                <a href="#" class="inline-flex items-center gap-2 px-3 py-1 text-sm font-medium text-blue-600 hover:bg-blue-50 rounded-lg transition-colors duration-200">
                                    <i data-feather="edit-2" class="w-4 h-4"></i>
                                    Edit
                                </a>
                                <button @click="$dispatch('delete-confirm', { id: 2, name: 'Andi Wijaya' })" 
                                        class="inline-flex items-center gap-2 px-3 py-1 text-sm font-medium text-red-600 hover:bg-red-50 rounded-lg transition-colors duration-200">
                                    <i data-feather="trash-2" class="w-4 h-4"></i>
                                    Hapus
                                </button>
                            </div>
                        </td>
                    </tr>

                    <tr class="hover:bg-gray-50 transition-colors duration-200">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-lg bg-emerald-100 text-emerald-600 flex items-center justify-center font-bold text-sm">
                                    SN
                                </div>
                                <div>
                                    <p class="text-sm font-medium text-gray-900">Siti Nurhaliza</p>
                                    <p class="text-xs text-gray-500">ID: 12347</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600">siti.nurhaliza@email.com</td>
                        <td class="px-6 py-4 text-sm text-gray-600">SMA Negeri 3 Jakarta</td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                Nonaktif
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-2">
                                <a href="#" class="inline-flex items-center gap-2 px-3 py-1 text-sm font-medium text-blue-600 hover:bg-blue-50 rounded-lg transition-colors duration-200">
                                    <i data-feather="edit-2" class="w-4 h-4"></i>
                                    Edit
                                </a>
                                <button @click="$dispatch('delete-confirm', { id: 3, name: 'Siti Nurhaliza' })" 
                                        class="inline-flex items-center gap-2 px-3 py-1 text-sm font-medium text-red-600 hover:bg-red-50 rounded-lg transition-colors duration-200">
                                    <i data-feather="trash-2" class="w-4 h-4"></i>
                                    Hapus
                                </button>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="px-6 py-4 border-t border-gray-200 bg-gray-50 flex items-center justify-between">
            <p class="text-sm text-gray-600">Menampilkan 1-3 dari 12 data</p>
            <div class="flex items-center gap-2">
                <button class="px-3 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-100 transition-colors duration-200">
                    Sebelumnya
                </button>
                <button class="px-3 py-2 bg-purple-600 text-white rounded-lg text-sm font-medium">1</button>
                <button class="px-3 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-100 transition-colors duration-200">2</button>
                <button class="px-3 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-100 transition-colors duration-200">
                    Selanjutnya
                </button>
            </div>
        </div>
    </div>
</div>
@endsection
