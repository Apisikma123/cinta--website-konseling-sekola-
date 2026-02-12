@extends('layouts.teacher', ['title' => 'Pengaturan'])

@section('content')
<div class="w-full px-4 sm:px-6 lg:px-8 space-y-6">

    {{-- Header --}}
    <div>
        <h2 class="text-xl font-bold text-gray-900">Pengaturan</h2>
        <p class="text-sm text-gray-500 mt-1">Kelola nomor WhatsApp dan keamanan akun Anda</p>
    </div>

    @if($errors->any())
    <div class="bg-red-50 border border-red-200 rounded-lg px-4 py-3">
        @foreach($errors->all() as $error)
            <p class="text-sm text-red-700">• {{ $error }}</p>
        @endforeach
    </div>
    @endif

    {{-- Cards Flex Side by Side --}}
    <div class="flex flex-col lg:flex-row gap-6 items-start">
        
        {{-- Left: WhatsApp & Email --}}
        <div class="w-full lg:flex-1 space-y-6">
            
            {{-- WhatsApp Card --}}
            <form method="POST" action="{{ route('teacher.settings.update') }}"
                  class="w-full bg-white rounded-xl border border-gray-200 shadow-sm p-6">
                @csrf
                <div class="flex items-center gap-3 mb-5">
                    <div class="w-10 h-10 bg-emerald-50 rounded-lg flex items-center justify-center">
                        <i class="fab fa-whatsapp text-emerald-600 text-xl"></i>
                    </div>
                    <h3 class="text-base font-semibold text-gray-900">Nomor WhatsApp</h3>
                </div>

                <div class="mb-5">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Nomor Aktif</label>
                    <input type="text" name="whatsapp" value="{{ old('whatsapp', $user->whatsapp) }}"
                           placeholder="Contoh: 081234567890"
                           class="w-full py-2 px-3 border border-gray-300 rounded-lg text-base focus:ring-2 focus:ring-emerald-500 focus:border-transparent outline-none">
                    <p class="text-xs text-gray-500 mt-2">Digunakan untuk koordinasi laporan lewat WhatsApp.</p>
                </div>

                <div class="flex justify-end">
                    <button type="submit" class="bg-primary hover:bg-primary/90 text-white rounded-lg px-6 py-2 font-medium text-sm transition-colors duration-150">
                        Update WhatsApp
                    </button>
                </div>
            </form>

            {{-- Email Change Card --}}
            <div class="w-full bg-white rounded-xl border border-gray-200 shadow-sm p-6">
                <div class="flex items-center gap-3 mb-5">
                    <div class="w-10 h-10 bg-blue-50 rounded-lg flex items-center justify-center">
                        <i class="fas fa-envelope text-blue-600 text-lg"></i>
                    </div>
                    <h3 class="text-base font-semibold text-gray-900">Ganti Email</h3>
                </div>

                <form method="POST" action="{{ route('teacher.settings.change-email') }}">
                    @csrf
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-500 mb-1">Email Saat Ini</label>
                        <p class="text-base font-semibold text-gray-800">{{ $user->email }}</p>
                    </div>

                    <div class="mb-5">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Email Baru</label>
                        <input type="email" name="new_email" required
                               class="w-full py-2 px-3 border border-gray-300 rounded-lg text-base focus:ring-2 focus:ring-blue-500 focus:border-transparent outline-none">
                    </div>

                    <button type="submit" class="w-full py-2.5 px-4 bg-primary text-white rounded-lg font-medium text-sm hover:bg-primary/90 transition-colors duration-150">
                        Lanjutkan Ganti Email (Butuh OTP)
                    </button>
                </form>
            </div>
        </div>

        {{-- Right: Password Card --}}
        <div class="w-full lg:w-96 flex-shrink-0">
            <div class="w-full bg-white rounded-xl border border-gray-200 shadow-sm p-6">
                <div class="flex items-center gap-3 mb-5">
                    <div class="w-10 h-10 bg-red-50 rounded-lg flex items-center justify-center">
                        <i class="fas fa-key text-red-500 text-lg"></i>
                    </div>
                    <h3 class="text-base font-semibold text-gray-900">Ganti Password</h3>
                </div>

                <form method="POST" action="{{ route('teacher.settings.change-password') }}" x-data="{ show: false }">
                    @csrf
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Password Lama</label>
                            <input type="password" name="current_password" required
                                   class="w-full py-2 px-3 border border-gray-300 rounded-lg text-base focus:ring-2 focus:ring-purple-500 focus:border-transparent outline-none">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Password Baru</label>
                            <input :type="show ? 'text' : 'password'" name="password" required
                                   class="w-full py-2 px-3 border border-gray-300 rounded-lg text-base focus:ring-2 focus:ring-purple-500 focus:border-transparent outline-none">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Konfirmasi</label>
                            <input :type="show ? 'text' : 'password'" name="password_confirmation" required
                                   class="w-full py-2 px-3 border border-gray-300 rounded-lg text-base focus:ring-2 focus:ring-purple-500 focus:border-transparent outline-none">
                        </div>

                        <div class="flex items-center gap-2 cursor-pointer" @click="show = !show">
                            <input type="checkbox" :checked="show" class="rounded border-gray-300 text-purple-600 focus:ring-purple-500 cursor-pointer">
                            <span class="text-sm text-gray-600">Lihat Password</span>
                        </div>

                        <button type="submit" class="w-full bg-primary hover:bg-primary/90 text-white rounded-lg py-2.5 font-medium text-sm transition-colors duration-150 mt-2">
                            Ganti Password (Butuh OTP)
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
