<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>500 - Terjadi Kesalahan | SISTEM CINTA</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Outfit', sans-serif; }
        .pulse { animation: pulse 2s infinite; }
        @keyframes pulse {
            0% { transform: scale(1); opacity: 0.1; }
            50% { transform: scale(1.5); opacity: 0.2; }
            100% { transform: scale(1); opacity: 0.1; }
        }
    </style>
</head>
<body class="bg-[#F8F9FF] min-h-screen flex items-center justify-center p-6 overflow-hidden">
    <div class="absolute inset-0 z-0">
        <div class="absolute top-1/4 left-1/4 w-96 h-96 bg-red-400 rounded-full blur-3xl pulse"></div>
        <div class="absolute bottom-1/4 right-1/4 w-96 h-96 bg-indigo-400 rounded-full blur-3xl pulse" style="animation-delay: 1s"></div>
    </div>

    <div class="max-w-lg w-full text-center space-y-8 relative z-10">
        <div class="relative inline-block">
            <div class="w-32 h-32 bg-white rounded-3xl shadow-2xl flex items-center justify-center mx-auto transform rotate-12">
                <svg class="w-16 h-16 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                </svg>
            </div>
        </div>
        
        <div class="space-y-3">
            <h1 class="text-4xl font-bold text-gray-900 leading-tight">Ups, Ada Masalah di Server Kami</h1>
            <p class="text-gray-500 text-lg px-6">Tenang saja, ini bukan salahmu. Tim kami sudah diberitahu dan sedang berusaha memperbaikinya secepat mungkin.</p>
        </div>

        <div class="flex flex-col sm:flex-row gap-4 justify-center pt-4">
            <button onclick="window.location.reload()" class="px-8 py-4 bg-white text-gray-700 font-semibold rounded-2xl hover:bg-gray-50 transition-all duration-300 shadow-lg border border-gray-100">
                Segarkan Halaman
            </button>
            <a href="/" class="px-8 py-4 bg-indigo-600 text-white font-semibold rounded-2xl hover:bg-indigo-700 transition-all duration-300 shadow-lg shadow-indigo-200">
                Kembali ke Beranda
            </a>
        </div>
        
        <p class="text-gray-400 text-sm">SISTEM CINTA - Curahan Inspirasi Tanpa Ada Rahasia</p>
    </div>
</body>
</html>
