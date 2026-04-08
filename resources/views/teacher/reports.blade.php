@extends('layouts.teacher', ['title' => 'Laporan'])

@section('content')
<div class="w-full px-4 sm:px-6 lg:px-8 space-y-6">

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-3">
        <div>
            <h2 class="text-xl font-bold text-gray-900">Semua Laporan</h2>
            <p class="text-sm text-gray-500 mt-1">
                Data laporan dari sekolah <strong class="text-gray-800">{{ $teacher->school ?? 'Anda' }}</strong>
            </p>
        </div>
    </div>

    {{-- Reports Table --}}
    @if($reports->count())
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
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
                            @if($report->is_claimed)
                                <p class="text-sm font-semibold text-gray-900 truncate max-w-[200px]">{{ $report->title }}</p>
                                <code class="text-xs text-gray-400 bg-gray-50 px-1.5 py-0.5 rounded border border-gray-100 font-mono">#{{ $report->tracking_code }}</code>
                            @else
                                <p class="text-sm font-semibold text-gray-400 truncate max-w-[200px] blur-[3px] select-none">████████████</p>
                                <code class="text-xs text-gray-300 bg-gray-50 px-1.5 py-0.5 rounded border border-gray-100 font-mono blur-[2px] select-none">#██████</code>
                            @endif
                        </td>
                        <td class="px-4 py-3">
                            @if($report->is_claimed)
                                <p class="text-sm font-medium text-gray-800">{{ $report->nama_murid }}</p>
                                <p class="text-xs text-gray-500">{{ $report->nama_sekolah }} · {{ $report->kelas }}</p>
                            @else
                                <p class="text-sm font-medium text-gray-700">{{ $report->censored_name }}</p>
                                <p class="text-xs text-gray-400">{{ $report->nama_sekolah }} · {{ $report->censored_kelas }}</p>
                            @endif
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
                               class="relative inline-flex items-center gap-1.5 px-3 py-1.5 text-sm font-medium text-purple-600 bg-purple-50 border border-purple-200 rounded-lg hover:bg-purple-100 transition-colors duration-150">
                                <i class="fas fa-eye text-xs"></i> Detail
                                @if(isset($unreadChatCounts[$report->id]) && $unreadChatCounts[$report->id] > 0)
                                    <span class="absolute -top-2 -right-2 bg-red-500 text-white text-[10px] font-bold w-5 h-5 flex items-center justify-center rounded-full border-2 border-white">{{ $unreadChatCounts[$report->id] }}</span>
                                @endif
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        @if($reports->hasPages())
        <div class="px-5 py-3 border-t border-gray-100 bg-gray-50">
            {{ $reports->links() }}
        </div>
        @endif
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
