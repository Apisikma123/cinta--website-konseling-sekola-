<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Admin' }} · Admin Panel</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <link rel="icon" href="{{ asset('img/icon.png') }}" type="image/png">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        *, *::before, *::after { box-sizing: border-box; }
        [x-cloak] { display: none !important; }
        body { font-family: 'Inter', system-ui, -apple-system, sans-serif; margin: 0; }

        /* SweetAlert2 overrides */
        .swal2-container { font-family: 'Inter', sans-serif !important; }
        .swal2-popup { border-radius: 12px !important; border: 1px solid #e5e7eb !important; box-shadow: 0 20px 40px rgba(0,0,0,0.1) !important; padding: 28px 24px 20px !important; }
        .swal2-title { font-size: 16px !important; font-weight: 700 !important; color: #111827 !important; }
        .swal2-html-container { font-size: 14px !important; color: #6b7280 !important; line-height: 1.6 !important; }
        .swal2-actions { gap: 8px !important; margin-top: 20px !important; }
        .swal2-styled { border-radius: 8px !important; padding: 8px 18px !important; font-size: 14px !important; font-weight: 600 !important; font-family: 'Inter', sans-serif !important; box-shadow: none !important; }
        .swal2-confirm { background: #7c3aed !important; } .swal2-confirm:hover { background: #6d28d9 !important; }
        .swal2-cancel { background: #f3f4f6 !important; color: #374151 !important; } .swal2-cancel:hover { background: #e5e7eb !important; }
        .swal2-deny { background: #dc2626 !important; } .swal2-deny:hover { background: #b91c1c !important; }
        .swal2-input { border-radius: 8px !important; border: 1px solid #d1d5db !important; font-size: 14px !important; font-family: monospace !important; box-shadow: none !important; padding: 8px 12px !important; height: auto !important; margin: 14px 0 0 !important; }
        .swal2-input:focus { border-color: #7c3aed !important; box-shadow: 0 0 0 3px #ede9fe !important; }
        .swal2-validation-message { background: none !important; color: #dc2626 !important; font-size: 12px !important; margin-top: 6px !important; }
        .swal2-icon { margin-bottom: 12px !important; width: 48px !important; height: 48px !important; }
    </style>
</head>
<body class="bg-gray-50 text-gray-900" x-data="{ sidebarOpen: false }">

{{-- Overlay (mobile) --}}
<div x-show="sidebarOpen" x-cloak
     @click="sidebarOpen = false"
     class="fixed inset-0 z-40 bg-gray-900/50 lg:hidden"
     x-transition:enter="transition-opacity ease-out duration-200"
     x-transition:enter-start="opacity-0"
     x-transition:enter-end="opacity-100"
     x-transition:leave="transition-opacity ease-in duration-150"
     x-transition:leave-start="opacity-100"
     x-transition:leave-end="opacity-0"></div>

{{-- ═══════════════════════════
     SIDEBAR
═══════════════════════════ --}}
<aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
       class="fixed inset-y-0 left-0 z-50 w-64 bg-white border-r border-gray-200 flex flex-col transition-transform duration-200 ease-in-out lg:translate-x-0 lg:static lg:z-auto">

    {{-- Brand --}}
    <div class="flex items-center justify-between px-5 py-4 border-b border-gray-100">
        <div class="flex items-center gap-3">
            <img src="{{ asset('img/icon.png') }}" alt="Logo" class="w-8 h-8 rounded-lg object-contain flex-shrink-0">
            <span class="text-sm font-bold text-gray-900 tracking-tight">Admin Panel</span>
        </div>
        <button @click="sidebarOpen = false" class="lg:hidden p-1 text-gray-400 hover:text-gray-600 rounded">
            <i class="fas fa-xmark text-base"></i>
        </button>
    </div>

    {{-- User Card --}}
    <div class="px-4 py-3 border-b border-gray-100">
        <div class="flex items-center gap-3 bg-gray-50 border border-gray-100 rounded-lg p-2.5">
            <div class="w-9 h-9 rounded-lg bg-purple-600 text-white flex items-center justify-center text-xs font-bold flex-shrink-0">
                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
            </div>
            <div class="min-w-0 flex-1">
                <p class="text-sm font-semibold text-gray-900 truncate leading-tight">{{ auth()->user()->name }}</p>
                <p class="text-xs text-purple-600 font-medium mt-0.5">Administrator</p>
            </div>
        </div>
    </div>

    {{-- Navigation --}}
    <nav class="flex-1 px-3 py-3 overflow-y-auto space-y-1">
        <p class="text-[10px] font-semibold text-gray-400 uppercase tracking-widest px-3 pt-1 pb-2">Menu Utama</p>

        <a href="/admin/dashboard"
           class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors duration-150 {{ request()->is('admin/dashboard') ? 'bg-purple-50 text-purple-700 font-semibold' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
            <i class="fas fa-table-cells-large w-4 text-center {{ request()->is('admin/dashboard') ? 'text-purple-600' : 'text-gray-400' }}"></i>
            <span>Dashboard</span>
        </a>

        <a href="/admin/approve-teachers"
           class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors duration-150 {{ request()->is('admin/approve-teachers') ? 'bg-purple-50 text-purple-700 font-semibold' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
            <i class="fas fa-user-check w-5 text-center {{ request()->is('admin/approve-teachers') ? 'text-purple-600' : 'text-gray-400' }}"></i>
            <span>Persetujuan Guru</span>
        </a>

        <a href="/admin/teachers"
           class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors duration-150 {{ request()->is('admin/teachers') ? 'bg-purple-50 text-purple-700 font-semibold' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
            <i class="fas fa-users w-5 text-center {{ request()->is('admin/teachers') ? 'text-purple-600' : 'text-gray-400' }}"></i>
            <span>Data Guru</span>
        </a>

        <a href="/admin/schools"
           class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors duration-150 {{ request()->is('admin/schools') ? 'bg-purple-50 text-purple-700 font-semibold' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
            <i class="fas fa-school w-5 text-center {{ request()->is('admin/schools') ? 'text-purple-600' : 'text-gray-400' }}"></i>
            <span>Data Sekolah</span>
        </a>

        <div class="my-2 border-t border-gray-100"></div>
        <p class="text-[10px] font-semibold text-gray-400 uppercase tracking-widest px-3 pt-1 pb-2">Akun</p>

        <a href="{{ route('admin.profile') }}"
           class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors duration-150 {{ request()->routeIs('admin.profile') ? 'bg-purple-50 text-purple-700 font-semibold' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
            <i class="fas fa-id-badge w-5 text-center {{ request()->routeIs('admin.profile') ? 'text-purple-600' : 'text-gray-400' }}"></i>
            <span>Profil</span>
        </a>

        <a href="{{ route('admin.settings') }}"
           class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium transition-colors duration-150 {{ request()->routeIs('admin.settings') ? 'bg-purple-50 text-purple-700 font-semibold' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
            <i class="fas fa-sliders w-5 text-center {{ request()->routeIs('admin.settings') ? 'text-purple-600' : 'text-gray-400' }}"></i>
            <span>Pengaturan</span>
        </a>
    </nav>

    {{-- Logout --}}
    <div class="p-3 border-t border-gray-100">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit"
                    class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-sm font-medium text-red-500 hover:bg-red-50 w-full transition-colors duration-150">
                <i class="fas fa-arrow-right-from-bracket w-4 text-center"></i>
                <span>Keluar</span>
            </button>
        </form>
    </div>
</aside>

{{-- ═══════════════════════════
     MAIN WRAPPER
═══════════════════════════ --}}
<div class="lg:ml-64 min-h-screen flex flex-col">

    {{-- Navbar --}}
    <header class="sticky top-0 z-30 bg-white border-b border-gray-200 h-14 flex items-center justify-between px-4 sm:px-6">
        <div class="flex items-center gap-3">
            <button @click="sidebarOpen = true" class="lg:hidden p-2 -ml-2 text-gray-500 hover:text-gray-700 rounded-lg hover:bg-gray-100">
                <i class="fas fa-bars text-lg"></i>
            </button>
            <h1 class="text-sm font-semibold text-gray-900">{{ $title ?? 'Dashboard' }}</h1>
        </div>

        {{-- Profile dropdown --}}
        <div x-data="{ open: false }" class="relative">
            <button @click="open = !open" @click.away="open = false"
                    class="flex items-center gap-2 py-1.5 pl-1.5 pr-3 rounded-lg border border-transparent hover:border-gray-200 hover:bg-gray-50 transition-all duration-150"
                    :class="open ? 'border-gray-200 bg-gray-50' : ''">
                <div class="w-7 h-7 rounded-md bg-purple-600 text-white flex items-center justify-center text-xs font-bold flex-shrink-0">
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                </div>
                <span class="hidden sm:block text-sm font-medium text-gray-700 max-w-[120px] truncate">
                    {{ auth()->user()->name }}
                </span>
                <i class="fas fa-chevron-down text-[9px] text-gray-400 transition-transform duration-150"
                   :class="open ? 'rotate-180' : ''"></i>
            </button>

            <div x-show="open" x-cloak
                 x-transition:enter="transition ease-out duration-100"
                 x-transition:enter-start="opacity-0 scale-95"
                 x-transition:enter-end="opacity-100 scale-100"
                 x-transition:leave="transition ease-in duration-75"
                 x-transition:leave-start="opacity-100 scale-100"
                 x-transition:leave-end="opacity-0 scale-95"
                 class="absolute right-0 mt-2 w-48 bg-white rounded-lg border border-gray-200 shadow-lg py-1 z-50">
                <a href="{{ route('admin.profile') }}" class="flex items-center gap-2 px-3 py-2 text-sm text-gray-700 hover:bg-gray-50">
                    <i class="fas fa-id-badge w-4 text-center text-purple-500 text-xs"></i> Profil
                </a>
                <a href="{{ route('admin.settings') }}" class="flex items-center gap-2 px-3 py-2 text-sm text-gray-700 hover:bg-gray-50">
                    <i class="fas fa-sliders w-4 text-center text-purple-500 text-xs"></i> Pengaturan
                </a>
                <div class="my-1 border-t border-gray-100"></div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="flex items-center gap-2 px-3 py-2 text-sm text-red-500 hover:bg-red-50 w-full">
                        <i class="fas fa-arrow-right-from-bracket w-4 text-center text-xs"></i> Keluar
                    </button>
                </form>
            </div>
        </div>
    </header>

    {{-- Page Content --}}
    <main class="flex-1 overflow-x-hidden p-4 sm:p-6 lg:p-8">
        <div class="max-w-7xl mx-auto">
            @yield('content')
        </div>
    </main>
</div>

{{-- SweetAlert2 --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>

<script>
    window.SwalUtils = {
        confirm(options) {
            return Swal.fire({
                title:  options.title   || 'Konfirmasi',
                text:   options.text    || 'Apakah Anda yakin?',
                icon:   options.icon    || 'question',
                showCancelButton:  true,
                confirmButtonText: options.confirmText || 'Ya, Lanjutkan',
                cancelButtonText:  options.cancelText  || 'Batal',
                reverseButtons:    true,
            }).then(r => {
                if (r.isConfirmed && options.onConfirm) options.onConfirm();
                return r;
            });
        },

        delete(onConfirm) {
            return Swal.fire({
                title: 'Konfirmasi Hapus',
                html:  'Tindakan ini <strong>tidak dapat dibatalkan</strong>.<br>Ketik <code style="background:#f3f4f6;padding:2px 6px;border-radius:4px;font-size:13px">HAPUS</code> untuk melanjutkan.',
                icon:  'warning',
                input: 'text',
                inputPlaceholder: 'Ketik HAPUS',
                showCancelButton:   true,
                confirmButtonText:  'Hapus',
                confirmButtonColor: '#dc2626',
                cancelButtonText:   'Batal',
                reverseButtons:     true,
                preConfirm: val => {
                    if (val !== 'HAPUS') {
                        Swal.showValidationMessage('Teks tidak sesuai. Ketik HAPUS (huruf kapital semua).');
                        return false;
                    }
                    return true;
                }
            }).then(r => { if (r.isConfirmed) onConfirm(); });
        },

        success(message) {
            return Swal.fire({
                icon: 'success', title: 'Berhasil',
                text: message,   confirmButtonText: 'Tutup',
                timer: 3500,     timerProgressBar: false,
            });
        },

        error(message) {
            return Swal.fire({
                icon: 'error', title: 'Terjadi Kesalahan',
                text: message, confirmButtonText: 'Tutup',
            });
        },

        toast(message, icon = 'success') {
            Swal.mixin({
                toast: true, position: 'top-end',
                showConfirmButton: false, timer: 2500,
            }).fire({ icon, title: message });
        },

        approve(options) {
            return this.confirm({ icon: 'question', ...options });
        }
    };

    document.addEventListener('DOMContentLoaded', () => {
        @if(session('success'))
            SwalUtils.toast("{{ addslashes(session('success')) }}", 'success');
        @endif
        @if(session('error'))
            SwalUtils.error("{{ addslashes(session('error')) }}");
        @endif
        @if(session('info'))
            SwalUtils.toast("{{ addslashes(session('info')) }}", 'info');
        @endif
    });
</script>

@stack('scripts')
</body>
</html>
