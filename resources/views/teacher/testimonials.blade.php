@extends('layouts.teacher', ['title' => 'Manajemen Testimoni'])

@section('content')
<div x-data="{ activeTab: 'pending' }" class="w-full px-4 sm:px-6 lg:px-8 space-y-6">

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-3 flex-wrap">
        <div>
            <h2 class="text-xl font-bold text-gray-900">Manajemen Testimoni</h2>
            <p class="text-sm text-gray-500 mt-1">Tinjau dan setujui ulasan dari siswa untuk ditampilkan di halaman publik</p>
        </div>
        <div class="flex gap-2 flex-wrap">
            <div class="bg-amber-50 border border-amber-200 rounded-lg px-3 py-1.5 flex items-center gap-1.5">
                <i class="fas fa-clock text-xs text-amber-600"></i>
                <span class="text-sm font-semibold text-amber-700">{{ $pending->count() }} Menunggu</span>
            </div>
            <div class="bg-emerald-50 border border-emerald-200 rounded-lg px-3 py-1.5 flex items-center gap-1.5">
                <i class="fas fa-check text-xs text-emerald-600"></i>
                <span class="text-sm font-semibold text-emerald-700">{{ $approved->count() }} Disetujui</span>
            </div>
        </div>
    </div>

    {{-- Tab Nav --}}
    <div class="bg-gray-100 border border-gray-200 rounded-lg p-1 inline-flex gap-1">
        <button @click="activeTab = 'pending'"
                class="flex items-center gap-2 px-4 py-2 rounded-md text-sm font-semibold transition-all duration-150"
                :class="activeTab === 'pending' ? 'bg-white text-purple-700 shadow-sm border border-gray-200' : 'text-gray-500 hover:text-gray-700 border border-transparent'">
            <i class="fas fa-clock text-xs"></i> Baru Masuk
            @if($pending->count() > 0)
            <span class="bg-amber-400 text-amber-900 text-[10px] font-bold px-1.5 py-0.5 rounded-full min-w-[18px] text-center">{{ $pending->count() }}</span>
            @endif
        </button>
        <button @click="activeTab = 'approved'"
                class="flex items-center gap-2 px-4 py-2 rounded-md text-sm font-semibold transition-all duration-150"
                :class="activeTab === 'approved' ? 'bg-white text-purple-700 shadow-sm border border-gray-200' : 'text-gray-500 hover:text-gray-700 border border-transparent'">
            <i class="fas fa-check-circle text-xs"></i> Disetujui
        </button>
    </div>

    {{-- PENDING TAB --}}
    <template x-if="activeTab === 'pending'">
        <div>
            @if($pending->count())
            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4">
                @foreach($pending as $testimonial)
                @php
                    $studentInitial = strtoupper(substr($testimonial->student_name, 0, 1));
                @endphp
                <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5 flex flex-col gap-3.5">
                    {{-- Stars --}}
                    <div class="flex items-center gap-1">
                        @for($i = 0; $i < 5; $i++)
                            <i class="fas fa-star text-sm {{ $i < $testimonial->rating ? 'text-amber-400' : 'text-gray-200' }}"></i>
                        @endfor
                        <span class="text-xs text-gray-500 ml-1.5 font-semibold">{{ $testimonial->rating }}/5</span>
                    </div>

                    {{-- Content --}}
                    <p class="text-sm text-gray-700 leading-relaxed flex-1">"{{ $testimonial->content }}"</p>

                    {{-- Student info --}}
                    <div class="flex items-center gap-2.5 pt-3 border-t border-gray-100">
                        <div class="w-8 h-8 rounded-full bg-purple-100 text-purple-600 flex items-center justify-center text-sm font-bold flex-shrink-0">
                            {{ $studentInitial }}
                        </div>
                        <div class="min-w-0 flex-1">
                            <p class="text-sm font-semibold text-gray-900 truncate">{{ $testimonial->student_name }}</p>
                            <div class="flex flex-col gap-0.5">
                                <p class="text-xs text-gray-500">{{ $testimonial->created_at->translatedFormat('d M Y') }}</p>
                                @if($testimonial->report?->school || $testimonial->report?->nama_sekolah)
                                <p class="text-xs text-purple-600 font-medium">{{ $testimonial->report?->school?->name ?? $testimonial->report?->nama_sekolah }}</p>
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- Actions --}}
                    <div class="flex gap-2">
                        <form id="reject-form-{{ $testimonial->id }}" action="{{ route('teacher.testimonials.reject', $testimonial->id) }}" method="POST" class="flex-1">
                            @csrf
                            <button type="button"
                                    onclick="SwalUtils.confirm({
                                        title: 'Tolak Testimoni',
                                        text: 'Testimoni dari {{ addslashes($testimonial->student_name) }} akan dihapus secara permanen.',
                                        icon: 'warning',
                                        confirmText: 'Ya, Tolak',
                                        onConfirm: () => document.getElementById('reject-form-{{ $testimonial->id }}').submit()
                                    })"
                                    class="w-full flex items-center justify-center gap-1.5 py-2 px-3 border border-red-200 rounded-lg text-sm font-medium text-red-600 hover:bg-red-50 transition-colors duration-150">
                                <i class="fas fa-xmark text-xs"></i> Tolak
                            </button>
                        </form>
                        <form id="approve-form-{{ $testimonial->id }}" action="{{ route('teacher.testimonials.approve', $testimonial->id) }}" method="POST" class="flex-1">
                            @csrf
                                    <button type="button"
                                            onclick="SwalUtils.approve({
                                                title: 'Setujui Testimoni',
                                                text: 'Testimoni dari {{ addslashes($testimonial->student_name) }} akan ditampilkan di halaman publik.',
                                                confirmText: 'Setujui',
                                                onConfirm: () => document.getElementById('approve-form-{{ $testimonial->id }}').submit()
                                            })"
                                            class="w-full flex items-center justify-center gap-1.5 py-2 px-3 bg-primary border border-primary rounded-lg text-sm font-medium text-white hover:bg-primary/90 transition-colors duration-150">
                                        <i class="fas fa-check text-xs"></i> Setujui
                                    </button>
                        </form>
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm py-14 text-center">
                <div class="w-12 h-12 bg-gray-100 rounded-xl flex items-center justify-center mx-auto mb-3">
                    <i class="fas fa-inbox text-xl text-gray-300"></i>
                </div>
                <p class="text-sm font-semibold text-gray-900">Belum ada data</p>
                <p class="text-sm text-gray-500 mt-1">Semua testimoni sudah ditangani</p>
            </div>
            @endif
        </div>
    </template>

    {{-- APPROVED TAB --}}
    <template x-if="activeTab === 'approved'">
        <div>
            @if($approved->count())
            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4">
                @foreach($approved as $testimonial)
                @php
                    $studentInitial = strtoupper(substr($testimonial->student_name, 0, 1));
                @endphp
                <div class="{{ $testimonial->is_visible ? 'bg-emerald-50 border-emerald-200' : 'bg-gray-100 border-gray-300' }} rounded-xl border p-5 flex flex-col gap-3.5">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-1">
                            @for($i = 0; $i < 5; $i++)
                                <i class="fas fa-star text-sm {{ $i < $testimonial->rating ? 'text-amber-400' : ($testimonial->is_visible ? 'text-emerald-200' : 'text-gray-300') }}"></i>
                            @endfor
                        </div>
                        @if($testimonial->is_visible)
                            <span class="bg-emerald-100 border border-emerald-200 text-emerald-800 text-[10px] font-bold uppercase tracking-wide px-2.5 py-0.5 rounded-full">
                                Published
                            </span>
                        @else
                            <span class="bg-gray-200 border border-gray-300 text-gray-600 text-[10px] font-bold uppercase tracking-wide px-2.5 py-0.5 rounded-full">
                                Hidden
                            </span>
                        @endif
                    </div>

                    <p class="text-sm text-gray-700 leading-relaxed flex-1">"{{ $testimonial->content }}"</p>

                    <div class="flex items-center gap-2.5 pt-3 border-t {{ $testimonial->is_visible ? 'border-emerald-200' : 'border-gray-300' }}">
                        <div class="w-8 h-8 rounded-full {{ $testimonial->is_visible ? 'bg-emerald-100 text-emerald-700 border-emerald-200' : 'bg-gray-200 text-gray-600 border-gray-300' }} flex items-center justify-center text-sm font-bold flex-shrink-0 border">
                            {{ $studentInitial }}
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-semibold text-gray-900">{{ $testimonial->student_name }}</p>
                            <div class="flex flex-col gap-0.5">
                                <p class="text-xs text-gray-500">{{ $testimonial->created_at->translatedFormat('M Y') }}</p>
                                @if($testimonial->report?->school || $testimonial->report?->nama_sekolah)
                                <p class="text-xs text-purple-600 font-medium">{{ $testimonial->report?->school?->name ?? $testimonial->report?->nama_sekolah }}</p>
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- Hide/Unhide Button --}}
                    <div class="pt-2 border-t {{ $testimonial->is_visible ? 'border-emerald-200' : 'border-gray-300' }}">
                        @if($testimonial->is_visible)
                            <form action="{{ route('teacher.testimonials.hide', $testimonial) }}" method="POST">
                                @csrf
                                <button type="submit" class="w-full flex items-center justify-center gap-1.5 py-2 px-3 bg-gray-500 text-white rounded-lg text-sm font-medium hover:bg-gray-600 transition-colors duration-150">
                                    <i class="fas fa-eye-slash text-xs"></i> Sembunyikan
                                </button>
                            </form>
                        @else
                            <form action="{{ route('teacher.testimonials.show', $testimonial) }}" method="POST">
                                @csrf
                                <button type="submit" class="w-full flex items-center justify-center gap-1.5 py-2 px-3 bg-primary text-white rounded-lg text-sm font-medium hover:bg-primary/90 transition-colors duration-150">
                                    <i class="fas fa-eye text-xs"></i> Tampilkan
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm py-14 text-center">
                <div class="w-12 h-12 bg-gray-100 rounded-xl flex items-center justify-center mx-auto mb-3">
                    <i class="fas fa-star text-xl text-gray-300"></i>
                </div>
                <p class="text-sm font-semibold text-gray-900">Belum ada data</p>
                <p class="text-sm text-gray-500 mt-1">Setujui testimoni dari tab Baru Masuk</p>
            </div>
            @endif
        </div>
    </template>

</div>
@endsection
