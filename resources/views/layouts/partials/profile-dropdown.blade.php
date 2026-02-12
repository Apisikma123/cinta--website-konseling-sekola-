@php
$navInitials = strtoupper(substr(explode(' ', auth()->user()->name)[0] ?? '', 0, 1)) . strtoupper(substr(explode(' ', auth()->user()->name)[1] ?? '', 0, 1));
$role = $role ?? 'admin';
$profileRoute = $role === 'teacher' ? route('teacher.profile') : route('admin.profile');
$settingsRoute = $role === 'teacher' ? route('teacher.settings') : route('admin.settings');
@endphp

<div x-data="{ open: false }" class="relative">
    <button @click="open = !open" 
            @click.away="open = false"
            class="flex items-center gap-2 py-1.5 pl-1.5 pr-3 rounded-lg border border-transparent hover:border-gray-200 hover:bg-gray-50 transition-all duration-150"
            :class="open ? 'border-gray-200 bg-gray-50' : ''">
        @if(auth()->user()->profile_photo)
            <img src="{{ asset('storage/' . auth()->user()->profile_photo) }}"
                 class="w-9 h-9 rounded-full object-cover flex-shrink-0" 
                 alt="{{ auth()->user()->name }}">
        @else
            <div class="w-9 h-9 rounded-full bg-purple-600 text-white flex items-center justify-center text-xs font-bold flex-shrink-0">
                {{ $navInitials ?: strtoupper(substr(auth()->user()->name, 0, 1)) }}
            </div>
        @endif
        <span class="hidden sm:block text-sm font-medium text-gray-700 max-w-[120px] truncate">
            {{ auth()->user()->name }}
        </span>
        <i class="fas fa-chevron-down text-[9px] text-gray-400 transition-transform duration-150"
           :class="open ? 'rotate-180' : ''"></i>
    </button>

    <div x-show="open" 
         x-cloak
         x-transition:enter="transition ease-out duration-100"
         x-transition:enter-start="opacity-0 scale-95"
         x-transition:enter-end="opacity-100 scale-100"
         x-transition:leave="transition ease-in duration-75"
         x-transition:leave-start="opacity-100 scale-100"
         x-transition:leave-end="opacity-0 scale-95"
         class="absolute right-0 mt-2 w-48 bg-white rounded-lg border border-gray-200 shadow-lg py-1 z-50">
        <a href="{{ $profileRoute }}" class="flex items-center gap-2 px-3 py-2 text-sm text-gray-700 hover:bg-gray-50">
            <i class="fas fa-id-badge w-4 text-center text-purple-500 text-xs"></i>
            {{ $role === 'teacher' ? 'Profil Saya' : 'Profil' }}
        </a>
        <a href="{{ $settingsRoute }}" class="flex items-center gap-2 px-3 py-2 text-sm text-gray-700 hover:bg-gray-50">
            <i class="fas fa-sliders w-4 text-center text-purple-500 text-xs"></i>
            Pengaturan
        </a>
        <div class="my-1 border-t border-gray-200"></div>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="flex items-center gap-2 px-3 py-2 text-sm text-red-500 hover:bg-red-50 w-full">
                <i class="fas fa-arrow-right-from-bracket w-4 text-center text-xs"></i>
                Keluar
            </button>
        </form>
    </div>
</div>
