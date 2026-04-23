<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>CINTA - Curahan Inovatif Anak Tanpa Batas</title>
    
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
    <!-- NAVIGATION BAR -->
    <nav class="bg-white shadow-sm border-b border-purple-100">
        <div class="max-w-md mx-auto px-4 py-3 flex justify-between items-center">
            <div class="flex items-center">
                <img src="{{ asset('img/icon.png') }}" alt="Logo" class="w-8 h-8 mr-2">
                <span class="text-lg font-bold text-purple-800">CINTA</span>
            </div>
            
            <!-- TOMBOL LOGIN (hanya muncul di halaman guest) -->
            @if(!request()->is('login*') && !request()->is('register*') && !auth()->check())
                <a href="{{ route('login') }}" 
                   class="text-sm bg-purple-100 hover:bg-purple-200 text-purple-700 px-3 py-1 rounded-lg transition">
                    <i class="fas fa-sign-in-alt mr-1"></i> Login
                </a>
            @elseif(auth()->check())
                <!-- Profil dropdown untuk guru/admin -->
                <div class="relative">
                    <div class="flex items-center space-x-3">
                        <div class="w-8 h-8 bg-gradient-to-br from-purple-500 to-pink-500 rounded-full flex items-center justify-center text-white text-sm font-bold">
                            {{ substr(auth()->user()->name, 0, 1) }}{{ substr(explode(' ', auth()->user()->name)[1] ?? '', 0, 1) }}
                        </div>

                        <div class="text-sm">
                            <div class="font-medium text-gray-800">{{ auth()->user()->name }}</div>
                            <div class="text-xs text-gray-500">{{ ucfirst(auth()->user()->role) }}</div>
                        </div>

                        <div>
                            @php $dash = auth()->user()->role === 'admin' ? url('/admin/dashboard') : url('/teacher/dashboard'); @endphp
                            <a href="{{ $dash }}" class="text-sm text-purple-600 hover:text-purple-800 mr-3">Dashboard</a>

                            <form method="POST" action="{{ url('/logout') }}" style="display:inline">
                                @csrf
                                <button type="submit" class="text-sm text-red-600 hover:text-red-800">Logout</button>
                            </form>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </nav>

    <!-- MAIN CONTENT -->
    <!-- TOAST NOTIFICATION -->
    <div id="notificationContainer" class="fixed top-4 right-4 z-50 space-y-2"></div>

    <main class="max-w-md mx-auto px-4 py-6">
        @yield('content')
    </main>

    <!-- FOOTER -->
    <footer class="max-w-md mx-auto px-4 py-6 text-center text-xs text-purple-600">
        © {{ date('Y') }} CINTA • Privasi Terjamin
        <br>
        <a href="mailto:cintakonseling@cinta-inovasi.my.id" class="hover:text-purple-800 transition">cintakonseling@cinta-inovasi.my.id</a>
    </footer>

    <script>
    function showNotification(message, type = 'info', duration = 4000) {
        const container = document.getElementById('notificationContainer');
        
        const typeConfig = {
            success: { bg: 'bg-green-100', border: 'border-green-300', text: 'text-green-800', icon: 'fa-check-circle' },
            error: { bg: 'bg-red-100', border: 'border-red-300', text: 'text-red-800', icon: 'fa-exclamation-circle' },
            info: { bg: 'bg-blue-100', border: 'border-blue-300', text: 'text-blue-800', icon: 'fa-info-circle' }
        };
        
        const config = typeConfig[type] || typeConfig.info;
        
        const notification = document.createElement('div');
        notification.className = `${config.bg} border ${config.border} ${config.text} px-4 py-3 rounded-lg shadow-md flex items-center gap-3 animate-fade-in`;
        notification.innerHTML = `
            <i class="fas ${config.icon}"></i>
            <span>${message}</span>
            <button onclick="this.parentElement.remove()" class="ml-auto text-lg leading-none opacity-70 hover:opacity-100">&times;</button>
        `;
        
        container.appendChild(notification);
        
        setTimeout(() => {
            notification.style.opacity = '0';
            notification.style.transition = 'opacity 0.3s ease';
            setTimeout(() => notification.remove(), 300);
        }, duration);
    }
    </script>

    <style>
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(-10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .animate-fade-in {
        animation: fadeIn 0.3s ease;
    }
    </style>
</body>
</html>