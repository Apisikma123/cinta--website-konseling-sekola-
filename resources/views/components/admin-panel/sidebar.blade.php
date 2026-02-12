<aside x-cloak
       :class="{ 'translate-x-0': sidebarOpen, '-translate-x-full': !sidebarOpen }"
       class="fixed inset-y-0 left-0 z-50 w-64 bg-white border-r border-gray-200 transition-transform duration-300 lg:translate-x-0 lg:static lg:inset-0 flex flex-col">
    
    <!-- Logo Section -->
    <div class="h-16 border-b border-gray-200 flex items-center px-6 flex-shrink-0">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 bg-purple-600 rounded-lg flex items-center justify-center text-white font-bold">
                <i data-feather="heart" class="w-6 h-6"></i>
            </div>
            <span class="text-lg font-bold text-gray-900">SIS BK</span>
        </div>
        <button @click="sidebarOpen = false" class="lg:hidden ml-auto p-2 text-gray-500 hover:bg-gray-100 rounded-lg">
            <i data-feather="x" class="w-5 h-5"></i>
        </button>
    </div>

    <!-- Navigation Menu -->
    <nav class="flex-1 overflow-y-auto px-4 py-6 space-y-1">
        @php
            $role = auth()->user()->role ?? 'teacher';
            $currentRoute = request()->route()->getName() ?? '';
        @endphp

        @if($role === 'teacher')
            <!-- Teacher Menu -->
            <a href="{{ route('teacher.reports.index') }}" 
               class="flex items-center gap-3 px-4 py-3 rounded-lg font-medium text-base transition-colors duration-200 {{ str_contains($currentRoute, 'teacher.reports.index') ? 'bg-purple-100 text-purple-700' : 'text-gray-700 hover:bg-gray-100' }}">
                <i data-feather="grid" class="w-5 h-5 flex-shrink-0"></i>
                <span>Dashboard</span>
            </a>

            <a href="{{ route('teacher.reports.index') }}" 
               class="flex items-center gap-3 px-4 py-3 rounded-lg font-medium text-base transition-colors duration-200 {{ str_contains($currentRoute, 'teacher.reports') ? 'bg-purple-100 text-purple-700' : 'text-gray-700 hover:bg-gray-100' }}">
                <i data-feather="file-text" class="w-5 h-5 flex-shrink-0"></i>
                <span>Laporan</span>
            </a>

            <a href="{{ route('teacher.testimonials') }}" 
               class="flex items-center gap-3 px-4 py-3 rounded-lg font-medium text-base transition-colors duration-200 {{ str_contains($currentRoute, 'teacher.testimonials') ? 'bg-purple-100 text-purple-700' : 'text-gray-700 hover:bg-gray-100' }}">
                <i data-feather="star" class="w-5 h-5 flex-shrink-0"></i>
                <span>Testimoni</span>
            </a>

            <a href="{{ route('teacher.secret-management') }}" 
               class="flex items-center gap-3 px-4 py-3 rounded-lg font-medium text-base transition-colors duration-200 {{ str_contains($currentRoute, 'teacher.secret-management') ? 'bg-purple-100 text-purple-700' : 'text-gray-700 hover:bg-gray-100' }}">
                <i data-feather="lock" class="w-5 h-5 flex-shrink-0"></i>
                <span>Kode Rahasia</span>
            </a>

            <a href="{{ route('teacher.settings') }}" 
               class="flex items-center gap-3 px-4 py-3 rounded-lg font-medium text-base transition-colors duration-200 {{ str_contains($currentRoute, 'teacher.settings') ? 'bg-purple-100 text-purple-700' : 'text-gray-700 hover:bg-gray-100' }}">
                <i data-feather="user" class="w-5 h-5 flex-shrink-0"></i>
                <span>Profil</span>
            </a>

        @elseif($role === 'admin')
            <!-- Admin Menu -->
            <a href="/admin/dashboard" 
               class="flex items-center gap-3 px-4 py-3 rounded-lg font-medium text-base transition-colors duration-200 {{ str_contains($currentRoute, 'admin.dashboard') ? 'bg-purple-100 text-purple-700' : 'text-gray-700 hover:bg-gray-100' }}">
                <i data-feather="activity" class="w-5 h-5 flex-shrink-0"></i>
                <span>Dashboard</span>
            </a>

            <a href="/admin/teachers" 
               class="flex items-center gap-3 px-4 py-3 rounded-lg font-medium text-base transition-colors duration-200 {{ str_contains($currentRoute, 'admin.teachers') ? 'bg-purple-100 text-purple-700' : 'text-gray-700 hover:bg-gray-100' }}">
                <i data-feather="users" class="w-5 h-5 flex-shrink-0"></i>
                <span>Guru</span>
            </a>

            <a href="/admin/schools" 
               class="flex items-center gap-3 px-4 py-3 rounded-lg font-medium text-base transition-colors duration-200 {{ str_contains($currentRoute, 'admin.schools') ? 'bg-purple-100 text-purple-700' : 'text-gray-700 hover:bg-gray-100' }}">
                <i data-feather="home" class="w-5 h-5 flex-shrink-0"></i>
                <span>Sekolah</span>
            </a>

            <a href="/admin/approve-teachers" 
               class="flex items-center gap-3 px-4 py-3 rounded-lg font-medium text-base transition-colors duration-200 {{ str_contains($currentRoute, 'admin.approve') ? 'bg-purple-100 text-purple-700' : 'text-gray-700 hover:bg-gray-100' }}">
                <i data-feather="user-check" class="w-5 h-5 flex-shrink-0"></i>
                <span>Persetujuan</span>
            </a>

            <a href="/admin/profile" 
               class="flex items-center gap-3 px-4 py-3 rounded-lg font-medium text-base transition-colors duration-200 {{ str_contains($currentRoute, 'admin.profile') ? 'bg-purple-100 text-purple-700' : 'text-gray-700 hover:bg-gray-100' }}">
                <i data-feather="user" class="w-5 h-5 flex-shrink-0"></i>
                <span>Profil</span>
            </a>
        @endif
    </nav>

    <!-- User Profile Section -->
    <div class="border-t border-gray-200 p-4 flex-shrink-0">
        <div class="flex items-center gap-3 p-3 rounded-lg bg-gray-50 mb-3">
            <div class="w-10 h-10 rounded-lg bg-purple-600 text-white flex items-center justify-center font-bold text-sm flex-shrink-0">
                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
            </div>
            <div class="min-w-0 flex-1">
                <p class="text-sm font-semibold text-gray-900 truncate">{{ auth()->user()->name }}</p>
                <p class="text-xs text-gray-500 capitalize">{{ $role }}</p>
            </div>
        </div>

        <form method="POST" action="{{ route('logout') }}" class="w-full">
            @csrf
            <button type="submit" 
                    class="flex items-center gap-3 w-full px-4 py-3 text-sm font-medium text-gray-700 rounded-lg hover:bg-red-50 hover:text-red-700 transition-colors duration-200">
                <i data-feather="log-out" class="w-5 h-5 flex-shrink-0"></i>
                <span>Keluar</span>
            </button>
        </form>
    </div>
</aside>
