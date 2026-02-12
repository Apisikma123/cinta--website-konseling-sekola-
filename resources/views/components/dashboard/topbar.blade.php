@props(['title'])

<header class="h-20 bg-white border-b border-gray-200 flex items-center justify-between px-4 sm:px-6 lg:px-8 shrink-0 sticky top-0 z-40 shadow-sm">
    <div class="flex items-center gap-4">
        <button @click="sidebarOpen = true" class="lg:hidden p-2 rounded-lg bg-gray-100 text-gray-600 hover:bg-gray-200">
            <i data-feather="menu" class="w-6 h-6"></i>
        </button>
        <button @click="sidebarCollapsed = !sidebarCollapsed" class="hidden lg:flex p-2 rounded-lg bg-gray-100 text-gray-600 hover:bg-gray-200 transition-colors">
            <i :data-feather="sidebarCollapsed ? 'maximize-2' : 'minimize-2'" class="w-5 h-5"></i>
        </button>
        <h1 class="text-xl font-bold text-gray-900 tracking-tight">{{ $title }}</h1>
    </div>

    <div class="flex items-center gap-2 sm:gap-4">
        @if(auth()->user()->role === 'admin')
        <!-- Search Bar -->
        <div class="hidden md:flex items-center bg-gray-50 border border-gray-300 rounded-lg px-4 py-2 focus-within:ring-2 focus-within:ring-purple-500 transition-all">
            <i data-feather="search" class="w-4 h-4 text-gray-500"></i>
            <input type="text" placeholder="Cari data..." class="bg-transparent border-none focus:ring-0 text-sm font-medium ml-2 w-48 lg:w-64 placeholder-gray-500 text-gray-800">
        </div>
        @endif

        <!-- Notifications -->
        <div class="relative" x-data="{ open: false }">
            <button @click="open = !open" class="p-2.5 rounded-lg text-gray-500 hover:text-purple-700 hover:bg-purple-50 transition-all relative">
                <i data-feather="bell" class="w-6 h-6"></i>
                <span class="absolute top-2 right-2 w-2.5 h-2.5 bg-red-500 rounded-full border-2 border-white"></span>
            </button>
            <div x-show="open" @click.away="open = false" x-cloak
                 class="absolute right-0 mt-3 w-80 bg-white border border-gray-200 rounded-xl shadow-lg z-50 overflow-hidden">
                <div class="p-4 bg-gray-50 border-b border-gray-200">
                    <h3 class="text-sm font-bold text-gray-800">Notifikasi</h3>
                </div>
                <div class="max-h-80 overflow-y-auto p-6 text-center">
                    <p class="text-sm text-gray-500">Belum ada notifikasi baru.</p>
                </div>
            </div>
        </div>

        <!-- User Dropdown -->
        <div class="relative" x-data="{ open: false }">
            <button @click="open = !open" class="flex items-center gap-3 p-1 rounded-lg hover:bg-gray-50 transition-all">
                <div class="w-10 h-10 rounded-lg bg-purple-600 flex items-center justify-center text-white text-sm font-bold shadow-sm">
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                </div>
                <div class="hidden sm:block text-left">
                    <p class="text-sm font-bold text-gray-900 leading-none">{{ auth()->user()->name }}</p>
                    <p class="text-xs text-gray-500 mt-1">Online</p>
                </div>
                <i data-feather="chevron-down" class="w-4 h-4 text-gray-500"></i>
            </button>
            <div x-show="open" @click.away="open = false" x-cloak
                 class="absolute right-0 mt-3 w-56 bg-white border border-gray-200 rounded-xl shadow-lg z-50 py-2">
                <a href="{{ auth()->user()->role === 'admin' ? '/admin/profile' : '/teacher/profile' }}" class="flex items-center gap-3 px-4 py-3 text-sm font-medium text-gray-700 hover:bg-purple-50 hover:text-purple-700">
                    <i data-feather="user" class="w-4 h-4"></i> Profil Saya
                </a>
                <a href="{{ auth()->user()->role === 'admin' ? '/admin/settings' : '/teacher/settings' }}" class="flex items-center gap-3 px-4 py-3 text-sm font-medium text-gray-700 hover:bg-purple-50 hover:text-purple-700">
                    <i data-feather="settings" class="w-4 h-4"></i> Pengaturan
                </a>
                <div class="my-2 border-t border-gray-100"></div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full flex items-center gap-3 px-4 py-3 text-sm font-medium text-red-600 hover:bg-red-50">
                        <i data-feather="log-out" class="w-4 h-4"></i> Keluar
                    </button>
                </form>
            </div>
        </div>
    </div>
</header>
