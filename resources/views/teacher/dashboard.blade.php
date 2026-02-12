@extends('layouts.teacher', ['title' => 'Dashboard'])

@section('content')
<div class="w-full px-4 sm:px-6 lg:px-8 space-y-6">

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-3">
        <div>
            <h2 class="text-xl font-bold text-gray-900">
                Selamat datang, {{ explode(' ', auth()->user()->name)[0] }}
            </h2>
            <p class="text-sm text-gray-500 mt-1">
                Pantau laporan siswa di <strong class="text-gray-800">{{ $teacher->school ?? 'sekolah Anda' }}</strong>
            </p>
        </div>
        <div class="flex items-center gap-2 bg-white border border-gray-200 rounded-lg px-3 py-1.5 text-sm text-gray-600">
            <i class="fas fa-calendar-day text-xs text-purple-600"></i>
            <span class="font-medium">{{ now()->translatedFormat('d F Y') }}</span>
        </div>
    </div>

    {{-- Stat Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
        {{-- Total Laporan --}}
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5">
            <div class="flex items-start justify-between gap-3">
                <div>
                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Total Laporan</p>
                    <p class="text-2xl font-bold text-gray-900 mt-2">{{ $totalReports }}</p>
                    <p class="text-xs text-gray-500 mt-1">Semua status</p>
                </div>
                <div class="w-10 h-10 bg-purple-50 rounded-lg flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-file-lines text-purple-600"></i>
                </div>
            </div>
        </div>

        {{-- Murid Terlibat --}}
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5">
            <div class="flex items-start justify-between gap-3">
                <div>
                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Murid Terlibat</p>
                    <p class="text-2xl font-bold text-gray-900 mt-2">{{ $studentCount }}</p>
                    <p class="text-xs text-gray-500 mt-1">Unik dari data laporan</p>
                </div>
                <div class="w-10 h-10 bg-blue-50 rounded-lg flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-users text-blue-600"></i>
                </div>
            </div>
        </div>

        {{-- Instansi --}}
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5">
            <div class="flex items-start justify-between gap-3">
                <div class="min-w-0 flex-1">
                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide">Instansi Anda</p>
                    <p class="text-base font-bold text-gray-900 mt-2 truncate">{{ $teacher->school ?? '—' }}</p>
                    <p class="text-xs text-gray-500 mt-1">Sekolah terdaftar</p>
                </div>
                <div class="w-10 h-10 bg-emerald-50 rounded-lg flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-school text-emerald-600"></i>
                </div>
            </div>
        </div>
    </div>

    {{-- Status Overview --}}
    <div class="grid grid-cols-3 gap-3">
        <div class="bg-amber-50 border border-amber-200 rounded-lg p-3 text-center">
            <p class="text-2xl font-bold text-amber-700">{{ $statusChart['baru'] ?? 0 }}</p>
            <p class="text-xs font-medium text-amber-600 mt-0.5">Baru</p>
        </div>
        <div class="bg-purple-50 border border-purple-200 rounded-lg p-3 text-center">
            <p class="text-2xl font-bold text-purple-700">{{ $statusChart['diproses'] ?? 0 }}</p>
            <p class="text-xs font-medium text-purple-600 mt-0.5">Diproses</p>
        </div>
        <div class="bg-emerald-50 border border-emerald-200 rounded-lg p-3 text-center">
            <p class="text-2xl font-bold text-emerald-700">{{ $statusChart['selesai'] ?? 0 }}</p>
            <p class="text-xs font-medium text-emerald-600 mt-0.5">Selesai</p>
        </div>
    </div>

    {{-- School Notice --}}
    <div class="bg-blue-50 border border-blue-200 rounded-lg px-4 py-3 flex items-center gap-3">
        <i class="fas fa-circle-info text-blue-600 flex-shrink-0"></i>
        <p class="text-sm text-blue-800">
            Notifikasi laporan hanya untuk sekolah <strong>{{ $teacher->school ?? '—' }}</strong>
        </p>
    </div>

    {{-- Recent Reports Table --}}
    @if($reports->count())
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="px-5 py-3.5 border-b border-gray-100 flex items-center justify-between flex-wrap gap-3">
            <div class="flex items-center gap-2">
                <i class="fas fa-list-ul text-xs text-purple-600"></i>
                <span class="text-sm font-semibold text-gray-900">Laporan Terbaru</span>
            </div>
            <a href="{{ route('teacher.reports.index') }}" class="text-sm font-medium text-purple-600 hover:text-purple-700">
                Lihat semua <i class="fas fa-arrow-right text-xs ml-1"></i>
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full min-w-[640px]">
                <thead>
                    <tr class="bg-gray-100">
                        <th class="px-5 py-2.5 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Judul / Kode</th>
                        <th class="px-4 py-2.5 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Pelapor & Kelas</th>
                        <th class="px-4 py-2.5 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Jenis</th>
                        <th class="px-4 py-2.5 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Tanggal</th>
                        <th class="px-4 py-2.5 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Status</th>
                        <th class="px-5 py-2.5 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($reports as $report)
                    <tr class="hover:bg-gray-50">
                        <td class="px-5 py-3">
                            <p class="text-sm font-semibold text-gray-900 truncate max-w-[200px]">{{ $report->title }}</p>
                            <code class="text-xs text-gray-400 bg-gray-50 px-1.5 py-0.5 rounded border border-gray-100 font-mono">#{{ $report->tracking_code }}</code>
                        </td>
                        <td class="px-4 py-3">
                            <p class="text-sm font-medium text-gray-800">{{ $report->nama_murid }}</p>
                            <p class="text-xs text-gray-500">{{ $report->nama_sekolah }} · {{ $report->kelas }}</p>
                        </td>
                        <td class="px-4 py-3 text-sm text-gray-600">{{ ucfirst($report->jenis_laporan ?? '—') }}</td>
                        <td class="px-4 py-3 text-sm text-gray-500 whitespace-nowrap">{{ $report->created_at->translatedFormat('d M Y') }}</td>
                        <td class="px-4 py-3">
                            @if($report->status === 'baru')
                                <span class="inline-flex items-center gap-1.5 text-xs font-medium px-2.5 py-1 rounded-full bg-amber-50 text-amber-700 border border-amber-200">
                                    <span class="w-1.5 h-1.5 rounded-full bg-amber-400"></span>Baru
                                </span>
                            @elseif($report->status === 'diproses')
                                <span class="inline-flex items-center gap-1.5 text-xs font-medium px-2.5 py-1 rounded-full bg-purple-50 text-purple-700 border border-purple-200">
                                    <span class="w-1.5 h-1.5 rounded-full bg-purple-500"></span>Diproses
                                </span>
                            @elseif($report->status === 'selesai')
                                <span class="inline-flex items-center gap-1.5 text-xs font-medium px-2.5 py-1 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span>Selesai
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 text-xs font-medium px-2.5 py-1 rounded-full bg-gray-100 text-gray-600 border border-gray-200">
                                    <span class="w-1.5 h-1.5 rounded-full bg-gray-400"></span>{{ ucfirst($report->status) }}
                                </span>
                            @endif
                        </td>
                        <td class="px-5 py-3 text-right">
                            <a href="{{ route('teacher.reports.show', $report) }}"
                               class="inline-flex items-center gap-1.5 px-3 py-1.5 text-sm font-medium text-purple-600 bg-purple-50 border border-purple-200 rounded-lg hover:bg-purple-100 transition-colors duration-150">
                                <i class="fas fa-eye text-xs"></i> Detail
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @else
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm py-14 text-center">
        <div class="w-12 h-12 bg-gray-100 rounded-xl flex items-center justify-center mx-auto mb-3">
            <i class="fas fa-inbox text-xl text-gray-300"></i>
        </div>
        <p class="text-sm font-semibold text-gray-900">Belum ada data</p>
        <p class="text-sm text-gray-500 mt-1">Laporan dari siswa {{ $teacher->school ?? '' }} akan tampil di sini</p>
    </div>
    @endif

</div>
@endsection
