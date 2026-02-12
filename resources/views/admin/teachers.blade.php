@extends('layouts.admin', ['title' => 'Data Guru'])

@section('content')
<div class="w-full px-4 sm:px-6 lg:px-8 space-y-6">

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-3">
        <div>
            <h2 class="text-xl font-bold text-gray-900">Data Guru</h2>
            <p class="text-sm text-gray-500 mt-1">Kelola guru BK yang sudah terverifikasi</p>
        </div>
        <span class="bg-primary-50 border border-primary-200 text-primary-700 text-sm font-semibold px-3 py-1.5 rounded-lg">
            {{ $teachers->total() }} guru
        </span>
    </div>

    {{-- Table --}}
    @if($teachers->count())
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full min-w-[700px]">
                <thead>
                    <tr class="bg-gray-100">
                        <th class="px-5 py-2.5 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Nama</th>
                        <th class="px-4 py-2.5 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Email</th>
                        <th class="px-4 py-2.5 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Sekolah</th>
                        <th class="px-4 py-2.5 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Status</th>
                        <th class="px-5 py-2.5 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($teachers as $teacher)
                    @php
                        $teacherInitials = strtoupper(substr(explode(' ', $teacher->name)[0] ?? '', 0, 1)) . strtoupper(substr(explode(' ', $teacher->name)[1] ?? '', 0, 1));
                    @endphp
                    <tr class="hover:bg-gray-50">
                        <td class="px-5 py-3">
                            <div class="flex items-center gap-2.5">
                                @if($teacher->profile_photo)
                                    <img src="{{ asset('storage/' . $teacher->profile_photo) }}"
                                         alt="{{ $teacher->name }}"
                                         class="w-10 h-10 rounded-full object-cover flex-shrink-0 border border-gray-200"
                                         onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                    <div class="w-10 h-10 rounded-full bg-primary-100 flex items-center justify-center text-primary-600 font-semibold flex-shrink-0 hidden" id="fallback-{{ $teacher->id }}">
                                        {{ $teacherInitials ?: strtoupper(substr($teacher->name, 0, 1)) }}
                                    </div>
                                @else
                                    <div class="w-10 h-10 rounded-full bg-primary-100 flex items-center justify-center text-primary-600 font-semibold flex-shrink-0">
                                        {{ $teacherInitials ?: strtoupper(substr($teacher->name, 0, 1)) }}
                                    </div>
                                @endif
                                <div>
                                    <p class="text-sm font-semibold text-gray-900">{{ $teacher->name }}</p>
                                    <p class="text-xs text-gray-500">{{ $teacher->whatsapp ?? '—' }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-4 py-3 text-sm text-gray-600">{{ $teacher->email }}</td>
                        <td class="px-4 py-3 text-sm text-gray-600">{{ $teacher->school ?? '—' }}</td>
                        <td class="px-4 py-3">
                            @if($teacher->is_active)
                                <span class="inline-flex items-center gap-1.5 text-xs font-medium px-2.5 py-1 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span>Aktif
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 text-xs font-medium px-2.5 py-1 rounded-full bg-gray-100 text-gray-600 border border-gray-200">
                                    <span class="w-1.5 h-1.5 rounded-full bg-gray-400"></span>Nonaktif
                                </span>
                            @endif
                        </td>
                        <td class="px-5 py-3 text-right">
                            <div class="flex items-center justify-end gap-2">
                                {{-- Toggle Status --}}
                                <form id="toggle-form-{{ $teacher->id }}" action="/admin/teachers/{{ $teacher->id }}/toggle" method="POST">
                                    @csrf @method('PATCH')
                                    <button type="button"
                                            onclick="SwalUtils.confirm({
                                                title: '{{ $teacher->is_active ? 'Nonaktifkan' : 'Aktifkan' }} Guru?',
                                                text: 'Status {{ $teacher->name }} akan di{{ $teacher->is_active ? 'nonaktifkan' : 'aktifkan' }}.',
                                                icon: 'question',
                                                confirmText: '{{ $teacher->is_active ? 'Nonaktifkan' : 'Aktifkan' }}',
                                                onConfirm: () => document.getElementById('toggle-form-{{ $teacher->id }}').submit()
                                            })"
                                            class="inline-flex items-center gap-1.5 px-3 py-1.5 text-sm font-medium rounded-lg border transition-colors duration-150 {{ $teacher->is_active ? 'text-amber-700 bg-amber-50 border-amber-200 hover:bg-amber-100' : 'text-emerald-700 bg-emerald-50 border-emerald-200 hover:bg-emerald-100' }}">
                                        <i class="fas fa-{{ $teacher->is_active ? 'pause' : 'play' }} text-xs"></i>
                                        {{ $teacher->is_active ? 'Nonaktifkan' : 'Aktifkan' }}
                                    </button>
                                </form>

                                {{-- Delete --}}
                                <form id="delete-teacher-{{ $teacher->id }}" action="/admin/teachers/{{ $teacher->id }}" method="POST">
                                    @csrf @method('DELETE')
                                    <button type="button"
                                            onclick="SwalUtils.delete(() => document.getElementById('delete-teacher-{{ $teacher->id }}').submit())"
                                            class="inline-flex items-center gap-1.5 px-3 py-1.5 text-sm font-medium text-red-600 bg-white border border-red-200 rounded-lg hover:bg-red-50 transition-colors duration-150">
                                        <i class="fas fa-trash-can text-xs"></i> Hapus
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        @if($teachers->hasPages())
        <div class="px-5 py-3 border-t border-gray-100 bg-gray-50">
            {{ $teachers->links() }}
        </div>
        @endif
    </div>
    @else
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm py-14 text-center">
        <div class="w-12 h-12 bg-gray-100 rounded-xl flex items-center justify-center mx-auto mb-3">
            <i class="fas fa-users text-xl text-gray-300"></i>
        </div>
        <p class="text-sm font-semibold text-gray-900">Belum ada data</p>
        <p class="text-sm text-gray-500 mt-1">Guru yang sudah disetujui akan muncul di sini</p>
    </div>
    @endif

</div>
@endsection
