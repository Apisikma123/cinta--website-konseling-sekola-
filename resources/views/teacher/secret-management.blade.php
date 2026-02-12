@extends('layouts.teacher', ['title' => 'Kode Rahasia'])

@section('content')
<div class="w-full px-4 sm:px-6 lg:px-8 space-y-6">

    {{-- Header --}}
    <div>
        <h2 class="text-xl font-bold text-gray-900">Kode Rahasia Sekolah</h2>
        <p class="text-sm text-gray-500 mt-1 leading-relaxed">
            Kode ini digunakan siswa dari <strong class="text-gray-800">{{ auth()->user()->school ?? 'sekolah Anda' }}</strong>
            untuk membuat laporan. Jaga kerahasiaannya.
        </p>
    </div>

    {{-- Token Card --}}
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">

        {{-- Card header --}}
        <div class="px-5 py-3.5 border-b border-gray-100 flex items-center justify-between flex-wrap gap-3">
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 bg-purple-50 rounded-lg flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-key text-sm text-purple-600"></i>
                </div>
                <div>
                    <p class="text-sm font-semibold text-gray-900">Kode Aktif</p>
                    <p class="text-xs text-gray-500">Klik area kode untuk menyalin</p>
                </div>
            </div>
            <span class="inline-flex items-center gap-1.5 bg-emerald-50 border border-emerald-200 text-emerald-700 text-xs font-semibold px-2.5 py-1 rounded-full">
                <span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span>Aktif
            </span>
        </div>

        {{-- Code display --}}
        <div x-data="{ copied: false }" class="p-6 sm:p-8">
            <div @click="navigator.clipboard.writeText('{{ $secret->code }}').then(() => { copied = true; setTimeout(() => copied = false, 2500) })"
                 class="bg-gray-50 border-2 border-dashed border-purple-200 rounded-xl p-6 sm:p-8 cursor-pointer flex items-center justify-between gap-4 hover:bg-purple-50 hover:border-purple-400 transition-colors duration-200">

                <code class="font-mono text-2xl sm:text-4xl font-medium tracking-[0.2em] text-gray-900 leading-none select-all">
                    {{ $secret->code }}
                </code>

                <div class="flex-shrink-0 text-center">
                    <div x-show="!copied" class="w-11 h-11 bg-purple-600 rounded-lg flex items-center justify-center text-white">
                        <i class="fas fa-copy"></i>
                    </div>
                    <div x-show="copied" x-cloak class="w-11 h-11 bg-emerald-500 rounded-lg flex items-center justify-center text-white">
                        <i class="fas fa-check"></i>
                    </div>
                    <p x-show="!copied" class="text-xs text-gray-500 mt-1.5 font-medium">Salin</p>
                    <p x-show="copied" x-cloak class="text-xs text-emerald-600 mt-1.5 font-semibold">Tersalin!</p>
                </div>
            </div>

            <p class="text-sm text-gray-500 mt-3 text-center">
                <i class="fas fa-hand-pointer text-xs text-purple-600 mr-1"></i>
                Klik kotak kode di atas untuk menyalin ke clipboard
            </p>
        </div>

        {{-- Footer --}}
        <div class="px-5 py-3.5 bg-gray-50 border-t border-gray-100 flex items-center justify-between flex-wrap gap-3">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 bg-white border border-gray-200 rounded-lg flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-clock-rotate-left text-xs text-gray-400"></i>
                </div>
                <div>
                    <p class="text-[10px] font-semibold text-gray-400 uppercase tracking-widest">Terakhir Diperbarui</p>
                    <p class="text-sm font-semibold text-gray-700 mt-0.5">
                        {{ $secret->updated_at->diffForHumans() }}
                        <span class="text-xs text-gray-500 font-normal">· {{ $secret->updated_at->translatedFormat('d M Y') }}</span>
                    </p>
                </div>
            </div>

            <form id="regen-form" action="{{ route('teacher.secret.regenerate') }}" method="POST">
                @csrf
                <button type="button"
                        onclick="SwalUtils.confirm({
                            title: 'Ganti Kode Rahasia?',
                            text: 'Kode {{ $secret->code }} akan langsung tidak aktif. Siswa tidak dapat melapor dengan kode lama.',
                            icon: 'warning',
                            confirmText: 'Ya, Ganti Kode',
                            onConfirm: () => document.getElementById('regen-form').submit()
                        })"
                        class="inline-flex items-center gap-2 bg-white border border-red-200 text-red-600 px-4 py-2 rounded-lg text-sm font-medium hover:bg-red-50 transition-colors duration-150">
                    <i class="fas fa-rotate text-xs"></i> Atur Ulang Kode
                </button>
            </form>
        </div>
    </div>

    {{-- Warning Panel --}}
    <div class="bg-amber-50 border border-amber-200 rounded-lg px-4 py-3.5 flex gap-3">
        <i class="fas fa-triangle-exclamation text-amber-600 flex-shrink-0 mt-0.5"></i>
        <div>
            <p class="text-sm font-semibold text-amber-800 mb-2">Perhatian</p>
            <ul class="text-sm text-amber-700 space-y-1 list-disc pl-4">
                <li>Kode ini unik untuk sekolah <strong>{{ auth()->user()->school ?? 'Anda' }}</strong></li>
                <li>Jangan bagikan kepada pihak yang tidak berkepentingan</li>
                <li>Atur ulang kode segera jika terjadi kebocoran</li>
                <li>Siswa perlu kode ini saat pertama kali mendaftar laporan</li>
            </ul>
        </div>
    </div>

</div>
@endsection
