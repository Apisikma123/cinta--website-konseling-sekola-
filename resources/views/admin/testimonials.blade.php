@extends('layouts.admin', ['title' => 'Manajemen Testimoni'])

@section('content')
<div class="w-full px-4 sm:px-6 lg:px-8 space-y-6">

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-3">
        <div>
            <h2 class="text-xl font-bold text-gray-900">Manajemen Testimoni</h2>
            <p class="text-sm text-gray-500 mt-1">Kelola visibilitas testimoni di halaman publik</p>
        </div>
        <span class="bg-primary-50 border border-primary-200 text-primary-700 text-sm font-semibold px-3 py-1.5 rounded-lg">
            {{ $testimonials->total() }} testimoni
        </span>
    </div>

    {{-- Table --}}
    @if($testimonials->count())
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full min-w-[800px]">
                <thead>
                    <tr class="bg-gray-100">
                        <th class="px-5 py-2.5 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Murid</th>
                        <th class="px-4 py-2.5 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Konten</th>
                        <th class="px-4 py-2.5 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Rating</th>
                        <th class="px-4 py-2.5 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Status</th>
                        <th class="px-4 py-2.5 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Visibilitas</th>
                        <th class="px-5 py-2.5 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($testimonials as $testimonial)
                    <tr class="hover:bg-gray-50">
                        <td class="px-5 py-3">
                            <div class="flex items-center gap-2.5">
                                <div class="w-10 h-10 rounded-full bg-primary-100 flex items-center justify-center text-primary-600 font-semibold flex-shrink-0">
                                    {{ strtoupper(substr($testimonial->student_name, 0, 1)) }}
                                </div>
                                <div>
                                    <p class="text-sm font-semibold text-gray-900">
                                        {{ $testimonial->is_anonymous ? 'Murid Anonim' : $testimonial->student_name }}
                                    </p>
                                    <p class="text-xs text-gray-500">{{ $testimonial->report->nama_sekolah ?? '—' }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-4 py-3">
                            <p class="text-sm text-gray-600 line-clamp-2 max-w-[250px]">{{ $testimonial->content }}</p>
                        </td>
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-1">
                                @for ($i = 0; $i < 5; $i++)
                                    <i class="fas fa-star {{ $i < $testimonial->rating ? 'text-amber-400' : 'text-gray-300' }} text-xs"></i>
                                @endfor
                            </div>
                        </td>
                        <td class="px-4 py-3">
                            @if($testimonial->is_approved)
                                <span class="inline-flex items-center gap-1.5 text-xs font-medium px-2.5 py-1 rounded-full bg-emerald-50 text-emerald-700 border border-emerald-200">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span>Disetujui
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 text-xs font-medium px-2.5 py-1 rounded-full bg-amber-50 text-amber-700 border border-amber-200">
                                    <span class="w-1.5 h-1.5 rounded-full bg-amber-400"></span>Menunggu
                                </span>
                            @endif
                        </td>
                        <td class="px-4 py-3">
                            @if($testimonial->is_visible)
                                <span class="inline-flex items-center gap-1.5 text-xs font-medium px-2.5 py-1 rounded-full bg-primary-50 text-primary-700 border border-primary-200">
                                    <span class="w-1.5 h-1.5 rounded-full bg-primary-500"></span>Tampil
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 text-xs font-medium px-2.5 py-1 rounded-full bg-gray-100 text-gray-600 border border-gray-200">
                                    <span class="w-1.5 h-1.5 rounded-full bg-gray-400"></span>Tersembunyi
                                </span>
                            @endif
                        </td>
                        <td class="px-5 py-3 text-right">
                            @if($testimonial->is_visible)
                                <form action="{{ route('admin.testimonials.hide', $testimonial) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="inline-flex items-center gap-1.5 px-3 py-1.5 text-sm font-medium rounded-lg transition-colors duration-150 bg-gray-500 text-white hover:bg-gray-600">
                                        <i class="fas fa-eye-slash text-xs"></i>
                                        Sembunyikan
                                    </button>
                                </form>
                            @else
                                <form action="{{ route('admin.testimonials.show', $testimonial) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="inline-flex items-center gap-1.5 px-3 py-1.5 text-sm font-medium rounded-lg transition-colors duration-150 bg-primary text-white hover:bg-primary/90">
                                        <i class="fas fa-eye text-xs"></i>
                                        Tampilkan
                                    </button>
                                </form>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        @if($testimonials->hasPages())
        <div class="px-5 py-3 border-t border-gray-100 bg-gray-50">
            {{ $testimonials->links() }}
        </div>
        @endif
    </div>
    @else
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm py-14 text-center">
        <div class="w-12 h-12 bg-gray-100 rounded-xl flex items-center justify-center mx-auto mb-3">
            <i class="fas fa-star text-xl text-gray-300"></i>
        </div>
        <p class="text-sm font-semibold text-gray-900">Belum ada testimoni</p>
        <p class="text-sm text-gray-500 mt-1">Testimoni dari siswa akan muncul di sini</p>
    </div>
    @endif

</div>
@endsection
