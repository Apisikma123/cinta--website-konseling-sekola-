@extends('layouts.auth')

@section('content')
<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="mb-6">
        <a href="/" class="inline-flex items-center text-purple-600 hover:text-purple-800 font-medium transition" aria-label="Kembali">
            <i class="fas fa-arrow-left"></i>
        </a>
    </div>

    <section class="bg-gradient-to-br from-purple-50 to-white p-6 sm:p-8 rounded-xl shadow-sm mb-8">
        <div class="flex flex-col md:flex-row items-start md:items-center gap-6">
            <div class="flex-1 min-w-0">
                <h1 class="text-2xl sm:text-3xl md:text-4xl font-bold text-purple-800 leading-tight mb-2">Chat dengan Guru</h1>
                <p class="text-sm sm:text-base text-gray-700">Masukkan kode laporan untuk membuka chat atau WhatsApp guru BK.</p>
            </div>
            <div class="hidden md:flex w-20 h-20 flex-shrink-0 rounded-2xl bg-white shadow-inner border border-purple-100 items-center justify-center text-3xl text-purple-600">
                <i class="fas fa-comments"></i>
            </div>
        </div>
    </section>

    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-8">
        <div class="bg-white rounded-lg p-4 sm:p-5 border border-purple-100 shadow-sm">
            <div class="text-xl sm:text-2xl mb-3 text-purple-600"><i class="fas fa-ticket"></i></div>
            <h3 class="font-semibold text-gray-900 text-sm sm:text-base">Kode Laporan</h3>
            <p class="text-xs sm:text-sm text-gray-600 mt-2">Gunakan kode dari laporan.</p>
        </div>
        <div class="bg-white rounded-lg p-4 sm:p-5 border border-purple-100 shadow-sm">
            <div class="text-xl sm:text-2xl mb-3 text-purple-600"><i class="fas fa-comments"></i></div>
            <h3 class="font-semibold text-gray-900 text-sm sm:text-base">Chat dengan Guru</h3>
            <p class="text-xs sm:text-sm text-gray-600 mt-2">Komunikasi aman dengan guru BK.</p>
        </div>
        <div class="bg-white rounded-lg p-4 sm:p-5 border border-purple-100 shadow-sm">
            <div class="text-xl sm:text-2xl mb-3 text-green-600"><i class="fab fa-whatsapp"></i></div>
            <h3 class="font-semibold text-gray-900 text-sm sm:text-base">WhatsApp Guru</h3>
            <p class="text-xs sm:text-sm text-gray-600 mt-2">Akses cepat ke guru BK.</p>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm p-6 sm:p-8 border border-purple-100">
        <form id="chatCodeForm" class="space-y-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-ticket mr-2 text-purple-600"></i>Kode Laporan
                </label>
                <input type="text" name="tracking_code" required value="{{ request('code', '') }}"
                       class="w-full px-4 py-3 rounded-lg border border-purple-200 focus:ring-2 focus:ring-purple-300 focus:border-transparent outline-none transition text-sm"
                       placeholder="Contoh: ABC123">
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                <button type="button" id="openChat"
                        class="w-full bg-purple-600 hover:bg-purple-700 active:scale-95 text-white font-bold py-3 px-4 rounded-lg transition-all duration-300 flex items-center justify-center">
                    <i class="fas fa-comments mr-2"></i> <span>Chat dengan Guru</span>
                </button>
                <button type="button" id="openWhatsapp"
                        class="w-full bg-green-600 hover:bg-green-700 active:scale-95 text-white font-bold py-3 px-4 rounded-lg transition-all duration-300 flex items-center justify-center">
                    <i class="fab fa-whatsapp mr-2"></i> <span>WhatsApp Guru</span>
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    const form = document.getElementById('chatCodeForm');
    const getCode = () => form.tracking_code.value.trim();

    document.getElementById('openChat').addEventListener('click', () => {
        const code = getCode();
        if (!code) return;
        window.location.href = `/chat-murid/${encodeURIComponent(code)}`;
    });

    document.getElementById('openWhatsapp').addEventListener('click', () => {
        const code = getCode();
        if (!code) return;
        window.location.href = `/chat/${encodeURIComponent(code)}/whatsapp`;
    });
</script>
@endsection