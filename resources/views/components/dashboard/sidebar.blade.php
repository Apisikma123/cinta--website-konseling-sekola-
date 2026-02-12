@props(['role' => auth()->user()->role])

<div x-cloak
     :class="{ 'translate-x-0': sidebarOpen, '-translate-x-full': !sidebarOpen }"
     class="fixed inset-y-0 left-0 z-50 w-72 bg-white border-r border-purple-100 transition-all duration-300 ease-in-out lg:translate-x-0 lg:static lg:inset-0"
     :style="sidebarCollapsed ? 'width: 5rem' : 'width: 18rem'">
    
    <div class="flex flex-col h-full">
        <!-- Logo -->
        <div class="flex items-center justify-between h-20 px-6 border-b border-purple-100">
            <div class="flex items-center gap-3 overflow-hidden">
                <div class="flex-shrink-0 w-10 h-10 bg-purple-600 rounded-lg flex items-center justify-center text-white shadow-sm">
                    <i data-feather="heart" class="w-6 h-6"></i>
                </div>
                <span x-show="!sidebarCollapsed" class="text-lg font-bold text-gray-900 tracking-tight whitespace-nowrap">SIS BK</span>
            </div>
            <button @click="sidebarOpen = false" class="lg:hidden p-2 text-gray-500 hover:bg-gray-100 rounded-lg">
                <i data-feather="x" class="w-6 h-6"></i>
            </button>
        </div>

        <!-- Navigation -->
        <nav class="flex-1 px-4 py-6 space-y-1.5 overflow-y-auto sidebar-scroll">
            @if($role === 'teacher')
                <x-dashboard.sidebar-item href="/teacher/dashboard" icon="grid" label="Dashboard" />
                <x-dashboard.sidebar-item href="/teacher/reports" icon="file-text" label="Semua Laporan" />
                <x-dashboard.sidebar-item href="/teacher/testimonials" icon="star" label="Testimoni" />
                <x-dashboard.sidebar-item href="/teacher/secret-management" icon="lock" label="Token Sekolah" />
                <x-dashboard.sidebar-item href="/teacher/profile" icon="user" label="Profil Saya" />
            @elseif($role === 'admin')
                <x-dashboard.sidebar-item href="/admin/dashboard" icon="activity" label="Overview" />
                <x-dashboard.sidebar-item href="/admin/teachers" icon="users" label="Data Guru" />
                <x-dashboard.sidebar-item href="/admin/schools" icon="home" label="Data Sekolah" />
                <x-dashboard.sidebar-item href="/admin/approve-teachers" icon="user-check" label="Persetujuan" />
                <x-dashboard.sidebar-item href="/admin/reports" icon="database" label="Database Laporan" />
                <x-dashboard.sidebar-item href="/admin/profile" icon="user" label="Profil Admin" />
            @endif
        </nav>

        <!-- Profile / Logout -->
        <div class="p-4 border-t border-purple-100 bg-gray-50">
            <div class="flex items-center p-3 rounded-lg bg-white border border-gray-200 mb-3"
                 :class="sidebarCollapsed ? 'justify-center border-none bg-transparent p-0' : 'gap-3'">
                <div class="w-10 h-10 rounded-lg bg-purple-100 text-purple-700 flex items-center justify-center font-bold flex-shrink-0 text-lg">
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                </div>
                <div x-show="!sidebarCollapsed" class="min-w-0">
                    <p class="text-sm font-bold text-gray-900 truncate">{{ auth()->user()->name }}</p>
                    <p class="text-xs text-gray-500 capitalize">{{ $role }}</p>
                </div>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" 
                        class="flex items-center w-full px-4 py-3 text-sm font-medium text-gray-600 rounded-lg hover:bg-red-50 hover:text-red-700 transition-colors duration-200"
                        :class="sidebarCollapsed ? 'justify-center' : 'gap-3'">
                    <i data-feather="log-out" class="w-5 h-5 flex-shrink-0"></i>
                    <span x-show="!sidebarCollapsed">Keluar Aplikasi</span>
                </button>
            </form>
        </div>
    </div>
</div>

<!-- Mobile Overlay -->
<div x-show="sidebarOpen" 
     @click="sidebarOpen = false"
     x-transition:enter="transition-opacity ease-linear duration-300"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"
     x-transition:leave="transition-opacity ease-linear duration-300"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0"
     class="fixed inset-0 z-40 bg-purple-900/20 lg:hidden"></div>
