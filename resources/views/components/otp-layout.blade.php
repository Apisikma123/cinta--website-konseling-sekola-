@props([
    'title' => 'Verifikasi OTP',
    'subtitle' => 'Masukkan kode OTP yang dikirim ke email Anda',
    'backRoute' => null,
    'backLabel' => 'Kembali'
])

@extends('layouts.auth', ['title' => $title])

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gray-50 px-4">
    <div class="w-full max-w-xs bg-white p-5 rounded-lg shadow">
        {{-- Header --}}
        <div class="text-center mb-5">
            <div class="w-12 h-12 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-3">
                <i class="fas fa-shield-alt text-xl text-purple-600"></i>
            </div>
            <h1 class="text-lg font-bold text-gray-900">{{ $title }}</h1>
            <p class="text-sm text-gray-500 mt-1">{{ $subtitle }}</p>
        </div>

        {{-- Alert Messages --}}
        @if(session('success'))
        <div class="bg-emerald-50 border border-emerald-200 rounded-lg p-3 mb-4">
            <div class="flex items-center gap-2">
                <i class="fas fa-check-circle text-emerald-600 text-sm"></i>
                <span class="text-sm text-emerald-700">{{ session('success') }}</span>
            </div>
        </div>
        @endif

        @if($errors->any())
        <div class="bg-red-50 border border-red-200 rounded-lg p-3 mb-4">
            <div class="flex items-center gap-2">
                <i class="fas fa-exclamation-circle text-red-600 text-sm"></i>
                <span class="text-sm text-red-700">{{ $errors->first() }}</span>
            </div>
        </div>
        @endif

        {{-- Content --}}
        {{ $slot }}

        {{-- Back Link --}}
        @if($backRoute)
        <div class="mt-4 pt-4 border-t border-gray-200 text-center">
            <a href="{{ $backRoute }}" class="text-sm text-gray-500 hover:text-gray-700 flex items-center justify-center gap-1">
                <i class="fas fa-arrow-left text-xs"></i>
                {{ $backLabel }}
            </a>
        </div>
        @endif
    </div>
</div>
@endsection
