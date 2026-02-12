@extends('layouts.admin', ['title' => 'Profil Admin'])

@section('content')
<div class="w-full px-4 sm:px-6 lg:px-8 space-y-6">
    <div>
        <h2 class="text-xl font-bold text-gray-900">Profil Admin</h2>
        <p class="text-sm text-gray-500 mt-1">Informasi akun administrator</p>
    </div>

    @php
        $adminInitials = strtoupper(substr(explode(' ', $user->name)[0] ?? '', 0, 1)) . strtoupper(substr(explode(' ', $user->name)[1] ?? '', 0, 1));
    @endphp

    <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="p-6">
            <div class="flex flex-col sm:flex-row gap-6 items-start">
                @if($user->profile_photo)
                    <img src="{{ asset('storage/' . $user->profile_photo) }}" 
                         alt="{{ $user->name }}"
                         class="w-20 h-20 rounded-full object-cover flex-shrink-0 border border-gray-200">
                @else
                    <div class="w-20 h-20 rounded-full bg-purple-600 text-white flex items-center justify-center text-3xl font-bold flex-shrink-0">
                        {{ $adminInitials ?: strtoupper(substr($user->name, 0, 1)) }}
                    </div>
                @endif
                <div class="flex-1 w-full space-y-4">
                    <div>
                        <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wide mb-1">Nama</label>
                        <p class="text-lg font-semibold text-gray-900">{{ $user->name }}</p>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wide mb-1">Email</label>
                        <p class="text-base text-gray-600">{{ $user->email }}</p>
                    </div>
                    <div>
                        <label class="block text-xs font-semibold text-gray-400 uppercase tracking-wide mb-1">Role</label>
                        <span class="inline-flex items-center gap-1.5 bg-purple-50 text-purple-700 text-xs font-semibold px-2.5 py-1 rounded-full border border-purple-200">
                            <i class="fas fa-shield-halved text-[10px]"></i> Administrator
                        </span>
                    </div>
                </div>
            </div>
        </div>
        <div class="px-6 py-4 bg-gray-50 border-t border-gray-100 flex justify-end">
            <a href="{{ route('admin.settings') }}" class="inline-flex items-center gap-2 bg-primary hover:bg-primary/90 text-white rounded-lg px-5 py-2 font-medium text-sm transition-colors duration-150">
                <i class="fas fa-pen text-xs"></i> Edit Pengaturan
            </a>
        </div>
    </div>

    <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6">
        <h3 class="text-sm font-semibold text-gray-900 mb-4">Ringkasan Sistem</h3>
        <div class="grid grid-cols-2 gap-4">
            <div class="bg-gray-50 rounded-lg p-4 border border-gray-100">
                <p class="text-xs font-medium text-gray-500 uppercase">Total Sekolah</p>
                <p class="text-2xl font-semibold text-gray-900 mt-1">{{ \App\Models\School::count() }}</p>
            </div>
            <div class="bg-gray-50 rounded-lg p-4 border border-gray-100">
                <p class="text-xs font-medium text-gray-500 uppercase">Guru BK</p>
                <p class="text-2xl font-semibold text-gray-900 mt-1">{{ \App\Models\User::where('role', 'teacher')->count() }}</p>
            </div>
        </div>
    </div>
</div>
@endsection
