<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Sistem Laporan BK Anonim">
    <title>{{ $title ?? 'Sistem Laporan BK' }}</title>
    
    <!-- Font Poppins -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    
    @vite(['resources/css/app.css'])
    <link rel="icon" href="{{ asset('img/icon.png') }}" type="image/png">
    
    <style>
        body { font-family: 'Poppins', sans-serif; }
        .fade-in { opacity: 0; animation: fadeIn 0.5s ease forwards; }
        @keyframes fadeIn { to { opacity: 1; } }
    </style>
</head>
<body class="bg-purple-50 min-h-screen">
    <!-- HEADER -->
    <header class="bg-white shadow-sm border-b border-purple-100">
        <div class="max-w-md mx-auto px-4 py-3 flex justify-between items-center">
            <div class="flex items-center">
                <img src="{{ asset('img/icon.png') }}" alt="Logo" class="w-8 h-8 mr-2">
                <span class="text-lg font-bold text-purple-800">Sistem BK</span>
            </div>
            <a href="{{ route('login') }}" class="text-sm bg-purple-100 hover:bg-purple-200 text-purple-700 px-3 py-1.5 rounded-lg transition">
                <i class="fas fa-user mr-1"></i> Login guru/admin
            </a>
        </div>
    </header>

    <!-- MAIN -->
    <main class="max-w-md mx-auto px-4 py-6">
        @yield('content')
    </main>

    <!-- FOOTER -->
    <footer class="max-w-md mx-auto px-4 py-6 text-center">
        <p class="text-xs text-purple-700">© {{ date('Y') }} Sistem Laporan BK</p>
        <p class="text-xs text-purple-600 mt-1">Laporan hanya dibaca oleh tim BK terpercaya</p>
    </footer>
</body>
</html>