<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>500 - Kesalahan Server</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    @vite(['resources/css/app.css'])
    <link rel="icon" href="{{ asset('img/icon.png') }}" type="image/png">
    <style>
        body { font-family: 'Poppins', sans-serif; }
        .float-animation {
            animation: float 3s ease-in-out infinite;
        }
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
        }
        .shake-animation {
            animation: shake 0.5s ease-in-out infinite;
        }
        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            25% { transform: translateX(-8px); }
            75% { transform: translateX(8px); }
        }
    </style>
</head>
<body class="bg-gradient-to-br from-purple-50 to-violet-50 min-h-screen flex items-center justify-center p-4">
    <div class="max-w-2xl w-full">
        <!-- SVG 500 Illustration -->
        <div class="text-center mb-8">
            <svg class="float-animation w-32 h-32 sm:w-48 sm:h-48 mx-auto mb-6" viewBox="0 0 300 300" fill="none" xmlns="http://www.w3.org/2000/svg">
                <!-- Background Circle -->
                <circle cx="150" cy="150" r="140" fill="#FEE2E2" stroke="#FECACA" stroke-width="2"/>
                
                <!-- 500 Text -->
                <text x="150" y="140" font-size="80" font-weight="700" text-anchor="middle" fill="url(#gradientText)" font-family="Poppins">
                    500
                </text>
                
                <!-- Decorative Elements -->
                <circle cx="80" cy="80" r="8" fill="#EF4444" opacity="0.6"/>
                <circle cx="220" cy="100" r="6" fill="#F87171" opacity="0.7"/>
                <circle cx="240" cy="200" r="7" fill="#EF4444" opacity="0.5"/>
                <circle cx="60" cy="220" r="6" fill="#F87171" opacity="0.6"/>
                
                <!-- Server/Gear Icon with Animation -->
                <g transform="translate(150, 200)">
                    <circle cx="0" cy="0" r="25" fill="#FCA5A5" stroke="#F87171" stroke-width="2"/>
                    <!-- Exclamation mark -->
                    <text x="0" y="8" font-size="40" font-weight="700" text-anchor="middle" fill="#DC2626" font-family="Poppins">!</text>
                </g>
                
                <!-- Gradient Definition -->
                <defs>
                    <linearGradient id="gradientText" x1="0%" y1="0%" x2="100%" y2="100%">
                        <stop offset="0%" style="stop-color:#EF4444;stop-opacity:1" />
                        <stop offset="100%" style="stop-color:#DC2626;stop-opacity:1" />
                    </linearGradient>
                </defs>
            </svg>
        </div>

        <!-- Content -->
        <div class="bg-white rounded-3xl shadow-2xl p-6 sm:p-10 border border-purple-100 text-center">
            <h1 class="text-3xl sm:text-4xl font-bold text-purple-800 mb-3">
                Terjadi Kesalahan di Server
            </h1>
            <p class="text-base sm:text-lg text-gray-600 mb-2">
                Maaf, sesuatu yang tidak terduga terjadi.
            </p>
            <p class="text-sm sm:text-base text-gray-500 mb-8">
                Tim kami sudah diberitahu dan sedang berusaha memperbaikinya secepat mungkin. Silakan coba lagi dalam beberapa saat.
            </p>

            <!-- Error Details -->
            <div class="bg-gradient-to-r from-red-50 to-pink-50 rounded-xl p-4 sm:p-6 mb-8 border border-red-100">
                <div class="flex items-center justify-center gap-2 mb-2">
                    <i class="fas fa-exclamation-circle text-red-600 text-lg"></i>
                    <p class="text-sm text-red-700 font-semibold">Kesalahan Server Internal</p>
                </div>
                <p class="text-xs sm:text-sm text-gray-700">
                    Error Code: <span class="font-mono font-bold text-red-600">5{{ $exception->getStatusCode() ?? '00' }}</span> — 
                    <span class="text-gray-600">{{ $exception->getMessage() ?: 'Internal Server Error' }}</span>
                </p>
            </div>

            <!-- What You Can Do -->
            <div class="bg-blue-50 rounded-xl p-4 sm:p-6 mb-8 border border-blue-200 text-left">
                <p class="text-sm font-semibold text-blue-900 mb-3 flex items-center gap-2">
                    <i class="fas fa-lightbulb text-blue-600"></i>
                    Yang bisa Anda lakukan:
                </p>
                <ul class="text-xs sm:text-sm text-gray-700 space-y-2">
                    <li class="flex gap-2">
                        <span class="text-blue-600 font-bold min-w-fit">•</span>
                        <span>Refresh halaman dan coba lagi</span>
                    </li>
                    <li class="flex gap-2">
                        <span class="text-blue-600 font-bold min-w-fit">•</span>
                        <span>Bersihkan cache browser (Ctrl+Shift+Delete)</span>
                    </li>
                    <li class="flex gap-2">
                        <span class="text-blue-600 font-bold min-w-fit">•</span>
                        <span>Kembali ke halaman utama dan mulai dari awal</span>
                    </li>
                </ul>
            </div>

            <!-- Action Buttons -->
            <div class="flex flex-col sm:flex-row gap-3 justify-center">
                <a href="javascript:window.location.reload()" class="flex items-center justify-center bg-gradient-to-r from-red-600 to-red-700 hover:from-red-700 hover:to-red-800 active:scale-95 text-white font-semibold py-3 px-6 rounded-lg transition-all duration-300">
                    <i class="fas fa-sync mr-2"></i> <span>Refresh Halaman</span>
                </a>
                <a href="/" class="flex items-center justify-center bg-blue-600 hover:bg-blue-700 active:scale-95 text-white font-semibold py-3 px-6 rounded-lg transition-all duration-300">
                    <i class="fas fa-home mr-2"></i> <span>Kembali ke Beranda</span>
                </a>
                <a href="javascript:history.back()" class="flex items-center justify-center bg-gray-200 hover:bg-gray-300 active:scale-95 text-gray-700 font-semibold py-3 px-6 rounded-lg transition-all duration-300">
                    <i class="fas fa-undo mr-2"></i> <span>Kembali</span>
                </a>
            </div>
        </div>

        <!-- Footer Message -->
        <div class="text-center mt-8">
            <p class="text-xs sm:text-sm text-gray-500">
                Jika masalah terus berlanjut, silakan hubungi tim dukungan kami di <a href="mailto:cintakonseling@cinta-inovasi.my.id" class="underline hover:text-gray-700">cintakonseling@cinta-inovasi.my.id</a>
            </p>
        </div>
    </div>
</body>
</html>
