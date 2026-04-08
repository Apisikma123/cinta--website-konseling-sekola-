@extends('layouts.admin', ['title' => 'Dashboard'])

@section('content')
<div class="w-full px-4 sm:px-6 lg:px-8 space-y-6">

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-3">
        <div>
            <h2 class="text-xl font-bold text-gray-900">Dashboard Admin</h2>
            <p class="text-sm text-gray-500 mt-1">Ringkasan data sistem bimbingan konseling</p>
        </div>
        <div class="flex items-center gap-2 bg-white border border-gray-200 rounded-lg px-3 py-1.5 text-sm text-gray-600">
            <i class="fas fa-calendar-day text-xs text-purple-600"></i>
            <span class="font-medium">{{ now()->translatedFormat('d F Y') }}</span>
        </div>
    </div>

    {{-- Stat Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        {{-- Active Schools --}}
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6">
            <div class="flex items-start justify-between gap-3">
                <div>
                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Sekolah Aktif</p>
                    <p class="text-2xl font-bold text-gray-900 mt-2">{{ $stats['active_schools'] }}</p>
                    <p class="text-xs text-gray-500 mt-1">dari {{ $stats['total_schools'] }} total</p>
                </div>
                <div class="w-10 h-10 bg-emerald-50 rounded-lg flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-school text-emerald-600"></i>
                </div>
            </div>
        </div>

        {{-- Total Teachers --}}
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6">
            <div class="flex items-start justify-between gap-3">
                <div>
                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Total Guru</p>
                    <p class="text-2xl font-bold text-gray-900 mt-2">{{ $stats['total_teachers'] }}</p>
                    <p class="text-xs text-gray-500 mt-1">Guru terdaftar</p>
                </div>
                <div class="w-10 h-10 bg-blue-50 rounded-lg flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-chalkboard-user text-blue-600"></i>
                </div>
            </div>
        </div>

        {{-- Pending Approval --}}
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6">
            <div class="flex items-start justify-between gap-3">
                <div>
                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Menunggu Approval</p>
                    <p class="text-2xl font-bold text-gray-900 mt-2">{{ $stats['pending_teachers'] }}</p>
                    <p class="text-xs text-gray-500 mt-1">Perlu disetujui</p>
                </div>
                <div class="w-10 h-10 bg-amber-50 rounded-lg flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-clock text-amber-600"></i>
                </div>
            </div>
        </div>

        {{-- Approved Teachers --}}
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6">
            <div class="flex items-start justify-between gap-3">
                <div>
                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Guru Tersetujui</p>
                    <p class="text-2xl font-bold text-gray-900 mt-2">{{ $stats['approved_teachers'] }}</p>
                    <p class="text-xs text-gray-500 mt-1">Sudah di-approve</p>
                </div>
                <div class="w-10 h-10 bg-emerald-50 rounded-lg flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-check-circle text-emerald-600"></i>
                </div>
            </div>
        </div>
    </div>

    {{-- Status Overview --}}
    <div class="grid grid-cols-2 gap-3">
        <div class="bg-emerald-50 border border-emerald-200 rounded-lg p-3 text-center">
            <p class="text-2xl font-bold text-emerald-700">{{ $schoolsChart['aktif'] }}</p>
            <p class="text-xs font-medium text-emerald-600 mt-0.5">Sekolah Aktif</p>
        </div>
        <div class="bg-gray-100 border border-gray-200 rounded-lg p-3 text-center">
            <p class="text-2xl font-bold text-gray-700">{{ $schoolsChart['nonaktif'] }}</p>
            <p class="text-xs font-medium text-gray-600 mt-0.5">Sekolah Nonaktif</p>
        </div>
    </div>

    {{-- Action Links --}}
    @if($stats['pending_teachers'] > 0)
    <div class="bg-amber-50 border border-amber-200 rounded-lg px-4 py-3 flex items-center justify-between gap-3 flex-wrap">
        <div class="flex items-center gap-3">
            <i class="fas fa-bell text-amber-600"></i>
            <p class="text-sm text-amber-800">
                Ada <strong>{{ $stats['pending_teachers'] }}</strong> guru yang menunggu persetujuan
            </p>
        </div>
        <a href="/admin/approve-teachers" class="text-sm font-medium text-purple-600 hover:text-purple-700">
            Tinjau sekarang <i class="fas fa-arrow-right text-xs ml-1"></i>
        </a>
    </div>
    @endif

    {{-- Guru Terbaru --}}
    @if($teachers && $teachers->count())
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
            <div>
                <h3 class="font-bold text-gray-900">Guru Terbaru</h3>
                <p class="text-xs text-gray-500 mt-0.5">Guru yang terakhir mendaftar dan terverifikasi</p>
            </div>
            <a href="/admin/approve-teachers" class="text-sm font-medium text-purple-600 hover:text-purple-700">
                Lihat semua <i class="fas fa-arrow-right ml-1"></i>
            </a>
        </div>

        <div class="divide-y divide-gray-100">
            @foreach($teachers as $teacher)
            @php
                $statusColor = $teacher->approval_status === 'approved' ? 'emerald' : ($teacher->approval_status === 'pending' ? 'amber' : 'red');
                $statusIcon = $teacher->approval_status === 'approved' ? 'check-circle' : ($teacher->approval_status === 'pending' ? 'clock' : 'x-circle');
                $statusLabel = $teacher->approval_status === 'approved' ? 'Disetujui' : ($teacher->approval_status === 'pending' ? 'Pending' : 'Ditolak');
            @endphp
            <div class="px-5 py-4 flex items-center justify-between gap-4 hover:bg-gray-50 transition-colors">
                <div class="flex items-center gap-3 flex-1 min-w-0">
                    {{-- Photo --}}
                    @if($teacher->profile_photo && file_exists(public_path('storage/' . $teacher->profile_photo)))
                        <img src="{{ asset('storage/' . $teacher->profile_photo) }}"
                             alt="{{ $teacher->name }}"
                             class="w-12 h-12 rounded-full object-cover flex-shrink-0 border border-gray-200">
                    @else
                        <div class="w-12 h-12 rounded-full bg-gradient-to-br from-purple-400 to-purple-600 flex items-center justify-center text-white font-bold text-sm flex-shrink-0">
                            {{ strtoupper(substr($teacher->name, 0, 1)) }}{{ strtoupper(substr(explode(' ', $teacher->name)[1] ?? '', 0, 1)) }}
                        </div>
                    @endif

                    {{-- Info --}}
                    <div class="flex-1 min-w-0">
                        <p class="font-semibold text-gray-900 truncate">{{ $teacher->name }}</p>
                        <p class="text-xs text-gray-500 truncate">{{ $teacher->email }}</p>
                    </div>
                </div>

                {{-- Status Badge --}}
                <div class="flex items-center gap-3 flex-shrink-0">
                    <span class="inline-flex items-center gap-1 text-xs font-medium px-3 py-1 rounded-full bg-{{ $statusColor }}-50 text-{{ $statusColor }}-700 border border-{{ $statusColor }}-200 whitespace-nowrap">
                        <i class="fas fa-{{ $statusIcon }} text-xs"></i>
                        {{ $statusLabel }}
                    </span>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @else
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm py-14 text-center">
        <div class="w-12 h-12 bg-gray-100 rounded-xl flex items-center justify-center mx-auto mb-3">
            <i class="fas fa-users text-xl text-gray-300"></i>
        </div>
        <p class="text-sm font-semibold text-gray-900">Belum ada guru yang terverifikasi</p>
    </div>
    @endif

</div>
@endsection
