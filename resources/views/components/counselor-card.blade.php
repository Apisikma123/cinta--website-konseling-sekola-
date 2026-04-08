@props(['counselor'])

@php
    $schoolName = $counselor->school ?? 'Sekolah';
@endphp

<style>
    /* subtle decorative motion for counselor cards */
    @keyframes slideY {
        0% { transform: translateY(-8%); }
        50% { transform: translateY(8%); }
        100% { transform: translateY(-8%); }
    }
    .slide-y { animation: slideY 9s ease-in-out infinite; }
    @media (prefers-reduced-motion: reduce) {
        .slide-y { animation: none !important; }
    }
</style>

<div class="relative">
    <!-- decorative pencil (left) -->
    <div class="absolute -left-4 top-8 opacity-80 pointer-events-none slide-y" aria-hidden="true">
        <svg width="28" height="96" viewBox="0 0 28 96" fill="none" xmlns="http://www.w3.org/2000/svg">
            <rect x="10" y="8" width="8" height="72" rx="4" fill="#7C3AED" opacity="0.12" />
            <rect x="9" y="80" width="10" height="8" rx="2" fill="#F3F4F6" />
            <polygon points="9,8 14,0 19,8" fill="#A78BFA" opacity="0.9"/>
        </svg>
    </div>

    <!-- decorative eraser (right) -->
    <div class="absolute -right-3 bottom-10 opacity-75 pointer-events-none slide-y" aria-hidden="true">
        <svg width="36" height="28" viewBox="0 0 36 28" fill="none" xmlns="http://www.w3.org/2000/svg">
            <rect x="2" y="4" width="32" height="20" rx="4" fill="#EEE6FF" />
            <rect x="4" y="6" width="28" height="12" rx="3" fill="#7C3AED" opacity="0.06" />
        </svg>
    </div>

    <div class="bg-white rounded-2xl border border-purple-100 shadow-sm p-6 text-center h-full transition hover:shadow-lg card-lift">
        <div class="flex flex-col items-center">
            @if(!empty($counselor->profile_photo))
                <img src="{{ asset('storage/' . $counselor->profile_photo) }}" alt="{{ $counselor->name }}" class="w-20 h-20 rounded-full object-cover mx-auto mb-4" loading="lazy">
            @else
                @php
                    $nameParts = explode(' ', trim($counselor->name));
                    $initials = strtoupper(substr($nameParts[0], 0, 1));
                    if (count($nameParts) > 1) {
                        $initials .= strtoupper(substr(end($nameParts), 0, 1));
                    }
                @endphp
                <div class="w-20 h-20 rounded-full bg-gradient-to-br from-purple-600 to-purple-700 flex items-center justify-center text-white font-semibold text-xl mb-4">
                    {{ $initials }}
                </div>
            @endif

        <h3 class="text-md font-semibold text-gray-800 mb-1">{{ $counselor->name }}</h3>
        <p class="text-sm text-gray-500 mb-4">{{ $schoolName }}</p>

        <div class="mt-auto">
            <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-medium bg-purple-50 text-purple-700">
                Siap Membantu
            </span>
        </div>
    </div>
</div>
