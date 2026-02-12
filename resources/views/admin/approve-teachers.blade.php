@extends('layouts.admin', ['title' => 'Persetujuan Guru'])

@section('content')
<div class="w-full px-4 sm:px-6 lg:px-8 space-y-6">

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-3">
        <div>
            <h2 class="text-xl font-bold text-gray-900">Persetujuan Guru</h2>
            <p class="text-sm text-gray-500 mt-1">Tinjau dan setujui pendaftaran guru BK baru</p>
        </div>
        @if($pendingTeachers->total() > 0)
        <span class="bg-amber-50 border border-amber-200 text-amber-700 text-sm font-semibold px-3 py-1.5 rounded-lg">
            {{ $pendingTeachers->total() }} menunggu
        </span>
        @endif
    </div>

    {{-- Cards --}}
    @if($pendingTeachers->count())
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4">
        @foreach($pendingTeachers as $teacher)
        @php
            $teacherInitials = strtoupper(substr(explode(' ', $teacher->name)[0] ?? '', 0, 1)) . strtoupper(substr(explode(' ', $teacher->name)[1] ?? '', 0, 1));
        @endphp
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5 flex flex-col gap-4">
            {{-- Teacher info --}}
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-full bg-purple-100 text-purple-600 flex items-center justify-center text-lg font-bold flex-shrink-0">
                    {{ $teacherInitials ?: strtoupper(substr($teacher->name, 0, 1)) }}
                </div>
                <div class="min-w-0 flex-1">
                    <p class="text-base font-semibold text-gray-900 truncate">{{ $teacher->name }}</p>
                    <p class="text-sm text-gray-500 truncate">{{ $teacher->email }}</p>
                </div>
            </div>

            {{-- Details --}}
            <div class="space-y-2 flex-1">
                <div class="flex items-center gap-2 text-sm">
                    <i class="fas fa-school w-4 text-center text-gray-400 text-xs"></i>
                    <span class="text-gray-600">{{ $teacher->school ?? 'Belum ada sekolah' }}</span>
                </div>
                @if($teacher->whatsapp)
                <div class="flex items-center gap-2 text-sm">
                    <i class="fab fa-whatsapp w-4 text-center text-emerald-500 text-xs"></i>
                    <span class="text-gray-600">{{ $teacher->whatsapp }}</span>
                </div>
                @endif
                <div class="flex items-center gap-2 text-sm">
                    <i class="fas fa-calendar w-4 text-center text-gray-400 text-xs"></i>
                    <span class="text-gray-500">Mendaftar {{ $teacher->created_at->diffForHumans() }}</span>
                </div>
            </div>

            {{-- Actions --}}
            <div class="pt-3 border-t border-gray-100">
                <form id="approve-teacher-{{ $teacher->id }}" action="/admin/approve-teacher/{{ $teacher->id }}" method="POST">
                    @csrf
                    <button type="button"
                            onclick="SwalUtils.approve({
                                title: 'Setujui Guru?',
                                text: '{{ addslashes($teacher->name) }} akan mendapat akses ke panel Guru BK.',
                                confirmText: 'Setujui',
                                onConfirm: () => document.getElementById('approve-teacher-{{ $teacher->id }}').submit()
                            })"
                            class="w-full flex items-center justify-center gap-2 py-2 bg-primary text-white rounded-lg text-sm font-medium hover:bg-primary/90 transition-colors duration-150">
                        <i class="fas fa-check text-xs"></i> Setujui Guru
                    </button>
                </form>
            </div>
        </div>
        @endforeach
    </div>

    @if($pendingTeachers->hasPages())
    <div class="mt-4">
        {{ $pendingTeachers->links() }}
    </div>
    @endif

    @else
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm py-14 text-center">
        <div class="w-12 h-12 bg-emerald-50 rounded-xl flex items-center justify-center mx-auto mb-3">
            <i class="fas fa-check-double text-xl text-emerald-500"></i>
        </div>
        <p class="text-sm font-semibold text-gray-900">Belum ada data</p>
        <p class="text-sm text-gray-500 mt-1">Tidak ada pendaftaran baru yang menunggu</p>
    </div>
    @endif

</div>
@endsection
