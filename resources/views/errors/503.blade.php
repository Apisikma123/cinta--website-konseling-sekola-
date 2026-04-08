<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>503 - Layanan Tidak Tersedia</title>
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
    </style>
</head>
<body class="bg-gradient-to-br from-purple-50 to-violet-50 min-h-screen flex items-center justify-center p-4">
    <div class="max-w-2xl w-full">
        <!-- SVG 503 Illustration -->
        <div class="text-center mb-8">
            <svg class="float-animation w-32 h-32 sm:w-48 sm:h-48 mx-auto mb-6" viewBox="0 0 300 300" fill="none" xmlns="http://www.w3.org/2000/svg">
                <!-- Background Circle -->
                <circle cx="150" cy="150" r="140" fill="#E2E8F0" stroke="#CBD5E1" stroke-width="2"/>
                
                <!-- 503 Text -->
                <text x="150" y="140" font-size="80" font-weight="700" text-anchor="middle" fill="url(#gradientText)" font-family="Poppins">
                    503
                </text>
                
                <!-- Decorative Elements -->
                <circle cx="80" cy="80" r="8" fill="#475569" opacity="0.6"/>
                <circle cx="220" cy="100" r="6" fill="#94A3B8" opacity="0.7"/>
                <circle cx="240" cy="200" r="7" fill="#475569" opacity="0.5"/>
                <circle cx="60" cy="220" r="6" fill="#94A3B8" opacity="0.6"/>
                
                <!-- Maintenance Icon -->
                <g transform="translate(150, 200)">
                    <circle cx="0" cy="0" r="25" fill="#CBD5E1" stroke="#94A3B8" stroke-width="2"/>
                    <!-- Wrench -->
                    <g transform="translate(0, 0)">
                        <path d="M -8 -2 L 8 -2 Q 12 -2 12 2 L 12 2 Q 12 6 8 6 L -2 6 Q -6 6 -6 2 L -6 -2" stroke="#475569" stroke-width="2" fill="none" stroke-linecap="round"/>
                        <rect x="-2" y="0" width="4" height="10" fill="#475569" rx="1"/>
                    </g>
                </g>
                
                <!-- Gradient Definition -->
                <defs>
                    <linearGradient id="gradientText" x1="0%" y1="0%" x2="100%" y2="100%">
                        <stop offset="0%" style="stop-color:#475569;stop-opacity:1" />
                        <stop offset="100%" style="stop-color:#334155;stop-opacity:1" />
                    </linearGradient>
                </defs>
            </svg>
        </div>

        <!-- Content -->
        <div class="bg-white rounded-3xl shadow-2xl p-6 sm:p-10 border border-purple-100 text-center">
            <h1 class="text-3xl sm:text-4xl font-bold text-purple-800 mb-3">
                Layanan Sedang Dirawat
            </h1>
            <p class="text-base sm:text-lg text-gray-600 mb-2">
                Sistem sedang dalam pemeliharaan dan akan kembali online segera.
            </p>
            <p class="text-sm sm:text-base text-gray-500 mb-8">
                Kami melakukan update dan perbaikan untuk memberikan layanan yang lebih baik. Terima kasih atas kesabaran Anda.
            </p>

            <!-- Error Details -->
            <div class="bg-gradient-to-r from-slate-50 to-gray-50 rounded-xl p-4 sm:p-6 mb-8 border border-slate-100">
                <div class="flex items-center justify-center gap-2 mb-2">
                    <i class="fas fa-tools text-slate-600 text-lg"></i>
                    <p class="text-sm text-slate-700 font-semibold">Pemeliharaan Sistem</p>
                </div>
                <p class="text-xs sm:text-sm text-gray-700">
                    Error Code: <span class="font-mono font-bold text-slate-600">{{ $exception->getStatusCode() }}</span> — 
                    <span class="text-gray-600">Service Unavailable</span>
                </p>
            </div>

            <!-- Expected Timeline -->
            <div class="bg-blue-50 rounded-xl p-4 sm:p-6 mb-8 border border-blue-200">
                <p class="text-sm font-semibold text-blue-900 mb-3 flex items-center justify-center gap-2">
                    <i class="fas fa-clock text-blue-600"></i>
                    Estimasi Waktu Pemeliharaan
                </p>
                <p class="text-xs sm:text-sm text-gray-700">
                    Sistem kami diharapkan akan kembali online dalam beberapa jam. Kami akan memberikan update lebih lanjut melalui media sosial kami.
                </p>
            </div>

            <!-- Action Buttons -->
            <div class="flex flex-col sm:flex-row gap-3 justify-center">
                <a href="javascript:window.location.reload()" class="flex items-center justify-center bg-gradient-to-r from-slate-600 to-slate-700 hover:from-slate-700 hover:to-slate-800 active:scale-95 text-white font-semibold py-3 px-6 rounded-lg transition-all duration-300">
                    <i class="fas fa-sync mr-2"></i> <span>Coba Lagi</span>
                </a>
                <a href="/" class="flex items-center justify-center bg-blue-600 hover:bg-blue-700 active:scale-95 text-white font-semibold py-3 px-6 rounded-lg transition-all duration-300">
                    <i class="fas fa-home mr-2"></i> <span>Kembali ke Beranda</span>
                </a>
            </div>
        </div>

        <!-- Footer Message -->
        <div class="text-center mt-8">
            <p class="text-xs sm:text-sm text-gray-500 mb-2">
                Follow kami di media sosial untuk update terbaru tentang status layanan.
            </p>
            <div class="flex justify-center gap-3">
                <a href="#" class="text-gray-400 hover:text-slate-600 transition">
                    <i class="fab fa-twitter text-lg"></i>
                </a>
                <a href="#" class="text-gray-400 hover:text-slate-600 transition">
                    <i class="fab fa-facebook text-lg"></i>
                </a>
                <a href="#" class="text-gray-400 hover:text-slate-600 transition">
                    <i class="fab fa-instagram text-lg"></i>
                </a>
            </div>
        </div>
    </div>
</body>
</html>
