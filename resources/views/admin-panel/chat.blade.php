@extends('layouts.admin-panel', ['title' => 'Chat'])

@section('content')
<div class="h-[calc(100vh-8rem)] flex flex-col bg-white rounded-lg border border-gray-200 overflow-hidden">
    <!-- Chat Header -->
    <div class="h-16 border-b border-gray-200 flex items-center justify-between px-6 flex-shrink-0 bg-gray-50">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-lg bg-blue-100 text-blue-600 flex items-center justify-center font-bold text-sm">
                BS
            </div>
            <div>
                <p class="text-sm font-semibold text-gray-900">Budi Santoso</p>
                <p class="text-xs text-gray-500">Online</p>
            </div>
        </div>
        <button class="p-2 text-gray-500 hover:bg-gray-200 rounded-lg transition-colors duration-200">
            <i data-feather="more-vertical" class="w-5 h-5"></i>
        </button>
    </div>

    <!-- Messages Area -->
    <div class="flex-1 overflow-y-auto p-6 space-y-4">
        <!-- Received Message -->
        <div class="flex justify-start">
            <div class="max-w-xs bg-gray-100 rounded-lg p-4">
                <p class="text-sm text-gray-900">Halo Bu, saya ingin konsultasi tentang masalah akademik saya.</p>
                <p class="text-xs text-gray-500 mt-2">10:30 AM</p>
            </div>
        </div>

        <!-- Sent Message -->
        <div class="flex justify-end">
            <div class="max-w-xs bg-purple-600 text-white rounded-lg p-4">
                <p class="text-sm">Baik Budi, silakan ceritakan masalahnya.</p>
                <p class="text-xs text-purple-200 mt-2">10:31 AM</p>
            </div>
        </div>

        <!-- Received Message -->
        <div class="flex justify-start">
            <div class="max-w-xs bg-gray-100 rounded-lg p-4">
                <p class="text-sm text-gray-900">Saya kesulitan memahami pelajaran matematika, terutama bagian integral.</p>
                <p class="text-xs text-gray-500 mt-2">10:32 AM</p>
            </div>
        </div>

        <!-- Sent Message -->
        <div class="flex justify-end">
            <div class="max-w-xs bg-purple-600 text-white rounded-lg p-4">
                <p class="text-sm">Itu hal yang wajar. Mari kita bahas bersama-sama. Kapan Anda bisa bertemu?</p>
                <p class="text-xs text-purple-200 mt-2">10:33 AM</p>
            </div>
        </div>

        <!-- Received Message -->
        <div class="flex justify-start">
            <div class="max-w-xs bg-gray-100 rounded-lg p-4">
                <p class="text-sm text-gray-900">Sore ini jam 3 sore bisa Bu?</p>
                <p class="text-xs text-gray-500 mt-2">10:34 AM</p>
            </div>
        </div>

        <!-- Sent Message -->
        <div class="flex justify-end">
            <div class="max-w-xs bg-purple-600 text-white rounded-lg p-4">
                <p class="text-sm">Baik, sore ini jam 3 sore di ruang BK. Sampai jumpa!</p>
                <p class="text-xs text-purple-200 mt-2">10:35 AM</p>
            </div>
        </div>
    </div>

    <!-- Message Input -->
    <div class="h-20 border-t border-gray-200 flex items-center gap-3 px-6 flex-shrink-0 bg-gray-50">
        <input type="text" 
               placeholder="Ketik pesan..." 
               class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent text-base">
        <button class="p-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition-colors duration-200">
            <i data-feather="send" class="w-5 h-5"></i>
        </button>
    </div>
</div>
@endsection
