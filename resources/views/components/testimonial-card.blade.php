<div class="bg-white rounded-lg p-6 border border-gray-200 transition-shadow duration-300 h-full flex flex-col enter-colored card-lift hover-overlay">
    <!-- Star Rating -->
    <div class="flex items-center gap-1 mb-4">
        @for ($i = 0; $i < 5; $i++)
            <i class="fas fa-star text-sm {{ $i < ($rating ?? 5) ? 'text-purple-500' : 'text-gray-300' }}"></i>
        @endfor
    </div>
    
    <!-- Testimonial Content -->
    <p class="text-sm text-gray-600 flex-1 mb-4 leading-relaxed">
        "{{ $slot }}"
    </p>
    
    <!-- Avatar & Author Info -->
    <div class="flex items-center gap-3">
        @php
            $nameParts = explode(' ', trim($studentName ?? 'Murid'));
            $initials = strtoupper(substr($nameParts[0], 0, 1));
            if (count($nameParts) > 1) {
                $initials .= strtoupper(substr(end($nameParts), 0, 1));
            }
        @endphp
        
        @php
            $gradients = ['from-purple-500 to-purple-700','from-purple-400 to-purple-600','from-indigo-500 to-purple-600'];
            $idx = crc32($studentName ?? 'murid') % count($gradients);
            $avatarGradient = $gradients[$idx];
        @endphp
        <div class="w-10 h-10 rounded-full bg-gradient-to-br {{ $avatarGradient }} flex items-center justify-center text-white font-bold text-sm flex-shrink-0">
            {{ $initials }}
        </div>
        
        <div>
            <p class="text-sm font-medium text-gray-900">
                {{ $anonymous ? '😊 Anonim' : $studentName }}
            </p>
            <p class="text-xs text-gray-500">Siswa</p>
        </div>
    </div>
</div>