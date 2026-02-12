@props(['counselor'])

@php
    $schoolName = $counselor->school ?? 'Sekolah Mitra';
@endphp

<div class="group relative w-full bg-white rounded-2xl shadow-md hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 border border-purple-100 flex flex-col items-center p-8 overflow-hidden">
    
    <!-- Educational Decorations (Pencil & Book) -->
    <!-- Spinning Pencil (Top Right) -->
    <div class="absolute -top-4 -right-4 w-16 h-16 bg-purple-50 rounded-full flex items-center justify-center opacity-0 group-hover:opacity-100 transition-all duration-500 transform scale-75 group-hover:scale-100 translate-x-4 translate-y-4 group-hover:translate-x-0 group-hover:translate-y-0 z-20 shadow-sm border border-purple-100">
        <i class="fas fa-pencil-alt text-purple-600 text-xl" style="animation: spinSlow 6s linear infinite;"></i>
    </div>
    
    <!-- Fading Book (Bottom Left) -->
    <div class="absolute -bottom-4 -left-4 w-16 h-16 bg-purple-50 rounded-full flex items-center justify-center opacity-0 group-hover:opacity-100 transition-all duration-500 transform scale-75 group-hover:scale-100 -translate-x-4 -translate-y-4 group-hover:translate-x-0 group-hover:translate-y-0 z-20 shadow-sm border border-purple-100">
        <i class="fas fa-book text-purple-600 text-xl"></i>
    </div>

    <!-- Background Accent -->
    <div class="absolute top-0 right-0 w-32 h-32 bg-purple-50/50 rounded-bl-[100px] -mr-16 -mt-16 z-0 transition-transform duration-500 group-hover:scale-110"></div>

    <!-- Profile Photo (96px = optimal size between 80-96px) -->
    <div class="relative z-10 mb-6 mt-2">
        <div class="w-24 h-24 rounded-full p-1 bg-gradient-to-tr from-purple-100 to-purple-50 shadow-sm group-hover:shadow-purple-200 transition-all duration-500">
            @if(!empty($counselor->profile_photo))
                <img src="{{ asset('storage/' . $counselor->profile_photo) }}" alt="{{ $counselor->name }}" class="w-full h-full rounded-full object-cover border-4 border-white bg-white" loading="lazy">
            @else
                @php
                    $nameParts = explode(' ', trim($counselor->name));
                    $initials = strtoupper(substr($nameParts[0], 0, 1));
                    if (count($nameParts) > 1) {
                        $initials .= strtoupper(substr(end($nameParts), 0, 1));
                    }
                @endphp
                <div class="w-full h-full rounded-full bg-white flex items-center justify-center text-purple-600 font-bold text-2xl border-4 border-white">
                    {{ $initials }}
                </div>
            @endif
        </div>
        <!-- Status Indicator -->
        <div class="absolute bottom-1 right-1 w-6 h-6 bg-green-400 border-4 border-white rounded-full z-20 shadow-sm"></div>
    </div>

    <!-- Content -->
    <div class="relative z-10 text-center flex-1 flex flex-col justify-between w-full">
        <div class="space-y-3">
            <h3 class="text-lg font-bold text-gray-900 group-hover:text-purple-600 transition-colors duration-300">{{ $counselor->name }}</h3>
            <div class="inline-flex items-center gap-1.5 px-3 py-1 bg-purple-50 text-purple-600 rounded-full text-[10px] font-black uppercase tracking-widest border border-purple-100">
                <i class="fas fa-school text-[8px]"></i>
                {{ $schoolName }}
            </div>
            <p class="text-gray-500 text-sm leading-relaxed line-clamp-2 mt-3">
                Siap mendampingi dan memberikan solusi terbaik bagi perkembangan mental dan akademik siswa.
            </p>
        </div>

        <div class="pt-6 w-full mt-auto">
            <div class="flex items-center justify-center gap-2 py-3 px-4 rounded-xl bg-purple-600 text-white font-bold text-sm shadow-lg shadow-purple-200 group-hover:bg-purple-700 transition-all duration-300">
                <span>Hubungi Konselor</span>
                <i class="fas fa-arrow-right text-xs group-hover:translate-x-1 transition-transform"></i>
            </div>
        </div>
    </div>
</div>

<style>
    @keyframes spinSlow {
        from { transform: rotate(0deg); }
        to { transform: rotate(360deg); }
    }
</style>

