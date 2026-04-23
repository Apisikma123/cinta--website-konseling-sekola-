<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>419 - Halaman Kadaluarsa</title>
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
        .spin-animation {
            animation: spin 4s linear infinite;
        }
        @keyframes spin {
            from { transform: rotate(0deg); }
            to { transform: rotate(360deg); }
        }
    </style>
</head>
<body class="bg-gradient-to-br from-purple-50 to-violet-50 min-h-screen flex items-center justify-center p-4">
    <div class="max-w-2xl w-full">
        <!-- SVG 419 Illustration -->
        <div class="text-center mb-8">
            <svg class="float-animation w-32 h-32 sm:w-48 sm:h-48 mx-auto mb-6" viewBox="0 0 300 300" fill="none" xmlns="http://www.w3.org/2000/svg">
                <!-- Background Circle -->
                <circle cx="150" cy="150" r="140" fill="#F3E8FF" stroke="#E9D5FF" stroke-width="2"/>
                
                <!-- 419 Text -->
                <text x="150" y="140" font-size="80" font-weight="700" text-anchor="middle" fill="url(#gradientText)" font-family="Poppins">
                    419
                </text>
                
                <!-- Decorative Elements -->
                <circle cx="80" cy="80" r="8" fill="#7E22CE" opacity="0.6"/>
                <circle cx="220" cy="100" r="6" fill="#C4B5FD" opacity="0.7"/>
                <circle cx="240" cy="200" r="7" fill="#7E22CE" opacity="0.5"/>
                <circle cx="60" cy="220" r="6" fill="#C4B5FD" opacity="0.6"/>
                
                <!-- Clock Icon with Animation -->
                <g transform="translate(150, 200)">
                    <circle cx="0" cy="0" r="25" fill="#E9D5FF" stroke="#C4B5FD" stroke-width="2"/>
                    <!-- Clock hands -->
                    <line x1="0" y1="-8" x2="0" y2="-15" stroke="#7E22CE" stroke-width="2" stroke-linecap="round"/>
                    <line x1="12" y1="0" x2="18" y2="0" stroke="#7E22CE" stroke-width="2" stroke-linecap="round"/>
                    <circle cx="0" cy="0" r="2" fill="#7E22CE"/>
                </g>
                
                <!-- Gradient Definition -->
                <defs>
                    <linearGradient id="gradientText" x1="0%" y1="0%" x2="100%" y2="100%">
                        <stop offset="0%" style="stop-color:#7E22CE;stop-opacity:1" />
                        <stop offset="100%" style="stop-color:#9333EA;stop-opacity:1" />
                    </linearGradient>
                </defs>
            </svg>
        </div>

        <!-- Content -->
        <div class="bg-white rounded-3xl shadow-2xl p-6 sm:p-10 border border-purple-100 text-center">
            <h1 class="text-3xl sm:text-4xl font-bold text-purple-800 mb-3">
                Halaman Kadaluarsa
            </h1>
            <p class="text-base sm:text-lg text-gray-600 mb-2">
                Sesi Anda telah berakhir atau token tidak valid.
            </p>
            <p class="text-sm sm:text-base text-gray-500 mb-8">
                Untuk keamanan, halaman yang Anda akses telah kadaluarsa. Silakan refresh atau kembali untuk melanjutkan.
            </p>

            <!-- Error Details -->
            <div class="bg-gradient-to-r from-purple-50 to-violet-50 rounded-xl p-4 sm:p-6 mb-8 border border-purple-100">
                <div class="flex items-center justify-center gap-2 mb-2">
                    <i class="fas fa-hourglass-end text-purple-600 text-lg"></i>
                    <p class="text-sm text-purple-700 font-semibold">Sesi Berakhir</p>
                </div>
                <p class="text-xs sm:text-sm text-gray-700">
                    Error Code: <span class="font-mono font-bold text-purple-600">{{ $exception->getStatusCode() }}</span> —
                    <span class="text-gray-600">{{ $exception->getMessage() ?: 'Page Expired' }}</span>
                </p>
            </div>

            <!-- Why This Happened -->
            <div class="bg-blue-50 rounded-xl p-4 sm:p-6 mb-8 border border-blue-200 text-left">
                <p class="text-sm font-semibold text-blue-900 mb-3 flex items-center gap-2">
                    <i class="fas fa-info-circle text-blue-600"></i>
                    Kenapa ini terjadi?
                </p>
                <ul class="text-xs sm:text-sm text-gray-700 space-y-2">
                    <li class="flex gap-2">
                        <span class="text-blue-600 font-bold min-w-fit">•</span>
                        <span>Sesi Anda timeout karena tidak ada aktivitas terlalu lama</span>
                    </li>
                    <li class="flex gap-2">
                        <span class="text-blue-600 font-bold min-w-fit">•</span>
                        <span>Anda membuka halaman di tab/jendela yang berbeda</span>
                    </li>
                    <li class="flex gap-2">
                        <span class="text-blue-600 font-bold min-w-fit">•</span>
                        <span>Cookie atau cache browser telah dihapus</span>
                    </li>
                </ul>
            </div>

            <!-- Action Buttons -->
            <div class="flex flex-col sm:flex-row gap-3 justify-center">
                <a href="/" class="flex items-center justify-center bg-gradient-to-r from-purple-600 to-purple-700 hover:from-purple-700 hover:to-purple-800 active:scale-95 text-white font-semibold py-3 px-6 rounded-lg transition-all duration-300">
                    <i class="fas fa-home mr-2"></i> <span>Kembali ke Beranda</span>
                </a>
                <a href="javascript:window.location.reload()" class="flex items-center justify-center bg-blue-600 hover:bg-blue-700 active:scale-95 text-white font-semibold py-3 px-6 rounded-lg transition-all duration-300">
                    <i class="fas fa-sync mr-2"></i> <span>Refresh Halaman</span>
                </a>
                <a href="javascript:history.back()" class="flex items-center justify-center bg-gray-200 hover:bg-gray-300 active:scale-95 text-gray-700 font-semibold py-3 px-6 rounded-lg transition-all duration-300">
                    <i class="fas fa-undo mr-2"></i> <span>Kembali</span>
                </a>
            </div>
        </div>

        <!-- Footer Message -->
        <div class="text-center mt-8">
            <p class="text-xs sm:text-sm text-gray-500">
                Jika masalah terus berlanjut, silakan hubungi kami di <a href="mailto:cintakonseling@cinta-inovasi.my.id" class="underline hover:text-gray-700">cintakonseling@cinta-inovasi.my.id</a>
            </p>
        </div>
    </div>
</body>
</html>
