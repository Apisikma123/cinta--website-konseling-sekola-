<header class="h-16 bg-white border-b border-gray-200 flex items-center justify-between px-4 sm:px-6 lg:px-8 flex-shrink-0 sticky top-0 z-30">
    <!-- Left Section -->
    <div class="flex items-center gap-4">
        <button @click="sidebarOpen = true" 
                class="lg:hidden p-2 rounded-lg bg-gray-100 text-gray-600 hover:bg-gray-200 transition-colors duration-200">
            <i data-feather="menu" class="w-5 h-5"></i>
        </button>
        <h1 class="text-lg font-semibold text-gray-900">{{ $title }}</h1>
    </div>

    <!-- Right Section -->
    <div class="flex items-center gap-4">
        <!-- User Dropdown -->
        <div class="relative" x-data="{ open: false }">
            <button @click="open = !open" 
                    class="flex items-center gap-3 p-2 rounded-lg hover:bg-gray-100 transition-colors duration-200">
                <div class="w-9 h-9 rounded-lg bg-purple-600 text-white flex items-center justify-center font-semibold text-sm">
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                </div>
                <div class="hidden sm:block text-left">
                    <p class="text-sm font-semibold text-gray-900">{{ auth()->user()->name }}</p>
                    <p class="text-xs text-gray-500">{{ auth()->user()->role }}</p>
                </div>
                <i data-feather="chevron-down" class="w-4 h-4 text-gray-500 hidden sm:block"></i>
            </button>

            <!-- Dropdown Menu -->
            <div x-show="open" 
                 @click.away="open = false" 
                 x-cloak
                 class="absolute right-0 mt-2 w-56 bg-white border border-gray-200 rounded-lg shadow-lg z-50 py-1">
                
                <a href="{{ auth()->user()->role === 'admin' ? route('admin.profile') : route('teacher.profile') }}" 
                   class="flex items-center gap-3 px-4 py-3 text-sm font-medium text-gray-700 hover:bg-purple-50 hover:text-purple-700 transition-colors duration-200">
                    <i data-feather="user" class="w-4 h-4"></i>
                    <span>Profil Saya</span>
                </a>

                <a href="{{ auth()->user()->role === 'admin' ? route('admin.settings') : route('teacher.settings') }}" 
                   class="flex items-center gap-3 px-4 py-3 text-sm font-medium text-gray-700 hover:bg-purple-50 hover:text-purple-700 transition-colors duration-200">
                    <i data-feather="settings" class="w-4 h-4"></i>
                    <span>Pengaturan</span>
                </a>

                <div class="my-1 border-t border-gray-100"></div>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" 
                            class="w-full flex items-center gap-3 px-4 py-3 text-sm font-medium text-red-600 hover:bg-red-50 transition-colors duration-200">
                        <i data-feather="log-out" class="w-4 h-4"></i>
                        <span>Keluar</span>
                    </button>
                </form>
            </div>
        </div>
    </div>
</header>
