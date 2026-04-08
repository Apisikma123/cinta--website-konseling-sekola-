@php
$nameParts = explode(' ', auth()->user()->name);
$initials = strtoupper(substr($nameParts[0] ?? '', 0, 1)) . strtoupper(substr($nameParts[1] ?? '', 0, 1));
@endphp

<!-- Brand -->
<div class="flex items-center justify-between px-5 py-4 border-b border-gray-200">
    <div class="flex items-center gap-3">
        <img src="{{ asset('img/icon.png') }}" alt="Logo" class="w-8 h-8 rounded-lg object-contain flex-shrink-0">
        <span class="text-sm font-bold text-gray-900 tracking-tight">BK Panel</span>
    </div>
    <button @click="sidebarOpen = false" class="lg:hidden p-1 text-gray-400 hover:text-gray-600 rounded">
        <i class="fas fa-xmark text-base"></i>
    </button>
</div>

<!-- User Card -->
<div class="px-4 py-3 border-b border-gray-200">
    <div class="flex items-center gap-3 bg-gray-50 border border-gray-200 rounded-lg p-2.5">
        @if(auth()->user()->profile_photo)
            <img src="{{ asset('storage/' . auth()->user()->profile_photo) }}" 
                 alt="{{ auth()->user()->name }}"
                 class="w-9 h-9 rounded-full object-cover flex-shrink-0 border border-gray-200">
        @else
            <div class="w-9 h-9 rounded-full bg-purple-600 text-white flex items-center justify-center text-xs font-bold flex-shrink-0">
                {{ $initials ?: strtoupper(substr(auth()->user()->name, 0, 1)) }}
            </div>
        @endif
        <div class="min-w-0 flex-1">
            <p class="text-sm font-semibold text-gray-900 truncate leading-tight">{{ auth()->user()->name }}</p>
            <p class="text-xs text-purple-600 font-medium mt-0.5">Guru BK</p>
        </div>
    </div>
</div>

<!-- Navigation -->
<nav class="flex-1 px-3 py-3 overflow-y-auto space-y-1">
    <p class="text-[10px] font-semibold text-gray-400 uppercase tracking-widest px-3 pt-1 pb-2">Menu Utama</p>

    <a href="{{ route('teacher.dashboard') }}" @click="sidebarOpen = false"
       class="flex items-center gap-3 py-2.5 px-4 rounded-lg text-sm font-medium transition-colors duration-150 {{ request()->routeIs('teacher.dashboard') ? 'bg-purple-100 text-purple-700' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
        <i class="fas fa-table-cells-large w-5 text-center {{ request()->routeIs('teacher.dashboard') ? 'text-purple-600' : 'text-gray-400' }}"></i>
        <span>Dashboard</span>
    </a>

    <a href="{{ route('teacher.reports.index') }}" @click="sidebarOpen = false"
       class="flex items-center justify-between py-2.5 px-4 rounded-lg text-sm font-medium transition-colors duration-150 {{ request()->routeIs('teacher.reports.*') ? 'bg-purple-100 text-purple-700' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
        <div class="flex items-center gap-3">
            <i class="fas fa-file-lines w-5 text-center {{ request()->routeIs('teacher.reports.*') ? 'text-purple-600' : 'text-gray-400' }}"></i>
            <span>Laporan</span>
        </div>
        @if(isset($totalUnreadChats) && $totalUnreadChats > 0)
            <span class="bg-red-500 text-white text-[10px] font-bold px-2 py-0.5 rounded-full">{{ $totalUnreadChats }}</span>
        @endif
    </a>

    <a href="{{ route('teacher.testimonials') }}" @click="sidebarOpen = false"
       class="flex items-center gap-3 py-2.5 px-4 rounded-lg text-sm font-medium transition-colors duration-150 {{ request()->routeIs('teacher.testimonials') ? 'bg-purple-100 text-purple-700' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
        <i class="fas fa-star w-5 text-center {{ request()->routeIs('teacher.testimonials') ? 'text-purple-600' : 'text-amber-400' }}"></i>
        <span>Testimoni</span>
    </a>

    <a href="{{ route('teacher.secret-management') }}" @click="sidebarOpen = false"
       class="flex items-center gap-3 py-2.5 px-4 rounded-lg text-sm font-medium transition-colors duration-150 {{ request()->routeIs('teacher.secret-management') ? 'bg-purple-100 text-purple-700' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
        <i class="fas fa-key w-5 text-center {{ request()->routeIs('teacher.secret-management') ? 'text-purple-600' : 'text-gray-400' }}"></i>
        <span>Kode Rahasia</span>
    </a>

    <div class="my-2 border-t border-gray-200"></div>
    <p class="text-[10px] font-semibold text-gray-400 uppercase tracking-widest px-3 pt-1 pb-2">Akun</p>

    <a href="{{ route('teacher.profile') }}" @click="sidebarOpen = false"
       class="flex items-center gap-3 py-2.5 px-4 rounded-lg text-sm font-medium transition-colors duration-150 {{ request()->routeIs('teacher.profile') ? 'bg-purple-100 text-purple-700' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
        <i class="fas fa-id-badge w-5 text-center {{ request()->routeIs('teacher.profile') ? 'text-purple-600' : 'text-gray-400' }}"></i>
        <span>Profil Saya</span>
    </a>

    <a href="{{ route('teacher.settings') }}" @click="sidebarOpen = false"
       class="flex items-center gap-3 py-2.5 px-4 rounded-lg text-sm font-medium transition-colors duration-150 {{ request()->routeIs('teacher.settings') ? 'bg-purple-100 text-purple-700' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
        <i class="fas fa-sliders w-5 text-center {{ request()->routeIs('teacher.settings') ? 'text-purple-600' : 'text-gray-400' }}"></i>
        <span>Pengaturan</span>
    </a>
</nav>

<!-- Logout -->
<div class="p-3 border-t border-gray-200">
    <form method="POST" action="{{ route('logout') }}">
        @csrf
        <button type="submit"
                class="flex items-center gap-3 py-2.5 px-4 rounded-lg text-sm font-medium text-red-500 hover:bg-red-50 w-full transition-colors duration-150">
            <i class="fas fa-arrow-right-from-bracket w-5 text-center"></i>
            <span>Keluar</span>
        </button>
    </form>
</div>
