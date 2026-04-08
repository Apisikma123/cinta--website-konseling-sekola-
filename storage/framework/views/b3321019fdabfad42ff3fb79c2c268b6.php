<!DOCTYPE html>
<html lang="<?php echo e(str_replace('_', '-', app()->getLocale())); ?>" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="<?php echo e(asset('img/icon.png')); ?>">
    <link rel="shortcut icon" type="image/png" href="<?php echo e(asset('img/icon.png')); ?>">

    <title><?php echo e(config('app.name', 'CINTA - Curahan Inovatif Anak Tanpa Batas')); ?></title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700;800;900&family=Inter:wght@100;200;300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    
    <!-- Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <!-- Feather Icons -->
    <script src="https://cdn.jsdelivr.net/npm/feather-icons/dist/feather.min.js"></script>

    <!-- Scripts & Styles -->
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
    
    <style>
        body { 
            font-family: 'Poppins', 'Inter', sans-serif; 
        }
        
        /* Page Load Animation */
        .fade-down {
            animation: fadeDown 0.6s ease-out forwards;
        }
        
        @keyframes fadeDown {
            from { 
                opacity: 0; 
                transform: translateY(-10px);
            }
            to { 
                opacity: 1; 
                transform: translateY(0);
            }
        }
        
        /* Navbar Scroll Shadow */
        .navbar-shadow {
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        }
        
        /* Menu Link Hover Underline */
        .menu-link {
            position: relative;
        }
        
        .menu-link::after {
            content: '';
            position: absolute;
            bottom: -4px;
            left: 0;
            width: 0;
            height: 2px;
            background: #9333ea;
            transition: width 0.3s ease;
        }
        
        .menu-link:hover::after {
            width: 100%;
        }
        
        /* Mobile Menu Animations */
        .mobile-menu {
            max-height: 0;
            opacity: 0;
            overflow: hidden;
            transition: max-height 0.3s ease-out, opacity 0.3s ease-out;
        }
        
        .mobile-menu.active {
            max-height: 500px;
            opacity: 1;
        }
        
        /* Hamburger Icon Animation */
        .hamburger-icon {
            transition: transform 0.3s ease;
        }
        
        .hamburger-icon.active {
            transform: rotate(90deg);
        }
        
        /* Button Hover Effect */
        .btn-login {
            transition: all 0.3s ease;
        }
        
        .btn-login:hover {
            transform: scale(1.03);
            box-shadow: 0 10px 15px -3px rgba(147, 51, 234, 0.3), 0 4px 6px -2px rgba(147, 51, 234, 0.2);
        }
        
        /* Mobile Menu Items Animation */
        .mobile-menu-item {
            animation: slideInLeft 0.3s ease-out forwards;
            opacity: 0;
        }
        
        .mobile-menu.active .mobile-menu-item {
            opacity: 1;
        }
        
        .mobile-menu.active .mobile-menu-item:nth-child(1) { animation-delay: 0.05s; }
        .mobile-menu.active .mobile-menu-item:nth-child(2) { animation-delay: 0.1s; }
        .mobile-menu.active .mobile-menu-item:nth-child(3) { animation-delay: 0.15s; }
        .mobile-menu.active .mobile-menu-item:nth-child(4) { animation-delay: 0.2s; }
        
        @keyframes slideInLeft {
            from {
                opacity: 0;
                transform: translateX(-20px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }
    </style>
</head>
<body class="bg-white text-gray-900 antialiased selection:bg-purple-100 selection:text-purple-600">

    <!-- Navigation -->
    <nav id="navbar" class="sticky top-0 z-50 bg-white/90 backdrop-blur-lg border-b border-gray-100 fade-down">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16 md:h-20 items-center">
                
                <!-- Logo & Brand -->
                <div class="flex items-center gap-2 md:gap-3">
                    <img src="<?php echo e(asset('img/icon.png')); ?>" alt="Logo CINTA" class="w-9 h-9 md:w-11 md:h-11 object-contain">
                    <div class="flex flex-col -space-y-1">
                        <span class="text-base md:text-xl font-bold text-gray-900 leading-tight">
                            CINTA
                        </span>
                        <span class="text-[10px] md:text-xs text-gray-500 font-medium hidden sm:block">Curahan Inovatif Anak Tanpa Batas</span>
                    </div>
                </div>

                <!-- Desktop Menu -->
                <div class="hidden md:flex items-center gap-1 xl:gap-2">
                    <a href="/" class="menu-link flex items-center gap-2 px-4 py-2 text-sm font-semibold text-gray-700 hover:text-purple-600 transition-colors duration-300">
                        <i data-feather="home" class="w-4 h-4"></i>
                        <span>Beranda</span>
                    </a>
                    <a href="<?php echo e(route('student.create')); ?>" class="menu-link flex items-center gap-2 px-4 py-2 text-sm font-semibold text-gray-700 hover:text-purple-600 transition-colors duration-300">
                        <i data-feather="edit" class="w-4 h-4"></i>
                        <span>Buat Laporan</span>
                    </a>
                    <a href="<?php echo e(route('student.track')); ?>" class="menu-link flex items-center gap-2 px-4 py-2 text-sm font-semibold text-gray-700 hover:text-purple-600 transition-colors duration-300">
                        <i data-feather="search" class="w-4 h-4"></i>
                        <span>Lacak Status</span>
                    </a>
                    <a href="<?php echo e(route('login')); ?>" class="btn-login flex items-center gap-2 ml-2 px-6 py-2.5 bg-purple-600 hover:bg-purple-700 text-white text-sm font-semibold rounded-xl shadow-md">
                        <i data-feather="log-in" class="w-4 h-4"></i>
                        <span>Login Guru</span>
                    </a>
                </div>

                <!-- Mobile Menu Button -->
                <button 
                    id="mobileMenuBtn" 
                    class="md:hidden w-10 h-10 flex items-center justify-center text-gray-700 hover:text-purple-600 transition-colors duration-300 rounded-lg hover:bg-purple-50"
                    aria-label="Toggle menu"
                >
                    <i data-feather="menu" class="hamburger-icon w-6 h-6"></i>
                </button>
            </div>
        </div>

        <!-- Mobile Menu Panel -->
        <div id="mobileMenu" class="mobile-menu md:hidden bg-white border-t border-gray-100">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 py-5 space-y-1">
                <a href="/" class="mobile-menu-item flex items-center gap-3 px-4 py-3 text-sm font-semibold text-gray-700 hover:text-purple-600 hover:bg-purple-50 rounded-lg transition-all duration-300">
                    <i data-feather="home" class="w-5 h-5"></i>
                    <span>Beranda</span>
                </a>
                <a href="<?php echo e(route('student.create')); ?>" class="mobile-menu-item flex items-center gap-3 px-4 py-3 text-sm font-semibold text-gray-700 hover:text-purple-600 hover:bg-purple-50 rounded-lg transition-all duration-300">
                    <i data-feather="edit" class="w-5 h-5"></i>
                    <span>Buat Laporan</span>
                </a>
                <a href="<?php echo e(route('student.track')); ?>" class="mobile-menu-item flex items-center gap-3 px-4 py-3 text-sm font-semibold text-gray-700 hover:text-purple-600 hover:bg-purple-50 rounded-lg transition-all duration-300">
                    <i data-feather="search" class="w-5 h-5"></i>
                    <span>Lacak Status</span>
                </a>
                <div class="mobile-menu-item pt-2 border-t border-gray-100">
                    <a href="<?php echo e(route('login')); ?>" class="flex items-center justify-center gap-2 w-full py-3.5 bg-purple-600 hover:bg-purple-700 text-white font-semibold rounded-xl shadow-md transition-all duration-300">
                        <i data-feather="log-in" class="w-5 h-5"></i>
                        <span>Login Guru</span>
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main>
        <?php echo $__env->yieldContent('content'); ?>
    </main>

    <!-- Toasts / Notifications -->
    <?php if ($__env->exists('components.toast')) echo $__env->make('components.toast', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>

    <script>
        // Initialize Feather Icons
        feather.replace();
        
        // Navbar Elements
        const navbar = document.getElementById('navbar');
        const mobileMenuBtn = document.getElementById('mobileMenuBtn');
        const mobileMenu = document.getElementById('mobileMenu');
        let isMenuOpen = false;
        
        // Mobile Menu Toggle
        if (mobileMenuBtn && mobileMenu) {
            mobileMenuBtn.addEventListener('click', (e) => {
                e.stopPropagation();
                toggleMenu();
            });
            
            // Close menu when clicking outside
            document.addEventListener('click', (e) => {
                if (isMenuOpen && !mobileMenu.contains(e.target) && !mobileMenuBtn.contains(e.target)) {
                    closeMenu();
                }
            });
            
            // Close menu on window resize to desktop
            window.addEventListener('resize', () => {
                if (window.innerWidth >= 768 && isMenuOpen) {
                    closeMenu();
                }
            });
        }
        
        function toggleMenu() {
            isMenuOpen = !isMenuOpen;
            mobileMenu.classList.toggle('active');
            
            const icon = mobileMenuBtn.querySelector('i');
            const hamburgerIcon = mobileMenuBtn.querySelector('.hamburger-icon');
            
            if (isMenuOpen) {
                icon.setAttribute('data-feather', 'x');
                hamburgerIcon.classList.add('active');
            } else {
                icon.setAttribute('data-feather', 'menu');
                hamburgerIcon.classList.remove('active');
            }
            
            feather.replace();
        }
        
        function closeMenu() {
            if (isMenuOpen) {
                isMenuOpen = false;
                mobileMenu.classList.remove('active');
                
                const icon = mobileMenuBtn.querySelector('i');
                const hamburgerIcon = mobileMenuBtn.querySelector('.hamburger-icon');
                
                icon.setAttribute('data-feather', 'menu');
                hamburgerIcon.classList.remove('active');
                
                feather.replace();
            }
        }
        
        // Navbar Scroll Shadow Effect
        let lastScroll = 0;
        window.addEventListener('scroll', () => {
            const currentScroll = window.pageYOffset;
            
            if (currentScroll > 10) {
                navbar.classList.add('navbar-shadow');
            } else {
                navbar.classList.remove('navbar-shadow');
            }
            
            lastScroll = currentScroll;
        });
        
        // Close mobile menu when clicking on menu links
        document.querySelectorAll('.mobile-menu-item').forEach(item => {
            item.addEventListener('click', () => {
                closeMenu();
            });
        });
    </script>

    
    <?php echo $__env->make('components.loading', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
</body>
</html><?php /**PATH D:\ngoding\sistem-cinta\resources\views/layouts/student.blade.php ENDPATH**/ ?>