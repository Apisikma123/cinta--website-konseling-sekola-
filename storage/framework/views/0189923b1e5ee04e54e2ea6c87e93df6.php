<?php $__env->startSection('content'); ?>
<style>
    /* ===== ROOT & TYPOGRAPHY ===== */
    html { 
        font-size: clamp(13px, 1.6vw, 18px); 
        scroll-behavior: smooth;
    }

    /* ===== FADE IN ANIMATIONS ===== */
    .fade-in-up {
        opacity: 0;
        animation: fadeInUp 0.6s ease forwards;
    }
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    .stagger-1 { animation-delay: 0.1s; }
    .stagger-2 { animation-delay: 0.2s; }
    .stagger-3 { animation-delay: 0.3s; }
    
    /* ===== GRADIENT TEXT ===== */
    .gradient-text {
        background: linear-gradient(135deg, #7C3AED, #5B21B6);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }
    
    /* ===== HOVER LIFT EFFECT ===== */
    .hover-lift {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    .hover-lift:hover {
        transform: translateY(-8px);
        box-shadow: 0 20px 40px rgba(124, 58, 237, 0.15);
    }

    /* ===== FEATURE CARD HOVER ===== */
    .feature-card {
        position: relative;
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        cursor: pointer;
        background: linear-gradient(135deg, #ffffff 0%, #fefefe 100%);
        will-change: transform;
    }
    .feature-card::before {
        content: '';
        position: absolute;
        inset: 0;
        border-radius: 1rem;
        padding: 2px;
        background: linear-gradient(135deg, #e9d5ff, #fce7f3);
        -webkit-mask: linear-gradient(#fff 0 0) content-box, linear-gradient(#fff 0 0);
        -webkit-mask-composite: xor;
        mask-composite: exclude;
        opacity: 0;
        transition: opacity 0.4s ease;
    }
    .feature-card:hover::before {
        opacity: 1;
    }
    .feature-card:hover {
        transform: translateY(-12px) scale(1.02);
        background: linear-gradient(135deg, #ffffff 0%, #f9f5ff 100%);
        box-shadow: 0 25px 50px rgba(124, 58, 237, 0.2);
        border-color: #e9d5ff;
    }
    .feature-icon {
        transition: all 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
    }
    .feature-card:hover .feature-icon {
        transform: scale(1.15) rotate(10deg);
    }
    .feature-card:hover .feature-title {
        color: #7C3AED;
        transform: translateY(-2px);
    }
    .feature-title {
        transition: all 0.3s ease;
    }
    .feature-description {
        transition: color 0.3s ease;
    }
    .feature-card:hover .feature-description {
        color: #374151;
    }
    
    /* Mobile optimization for features */
    @media (max-width: 768px) {
        .feature-card:hover {
            transform: translateY(-8px) scale(1.01);
            box-shadow: 0 20px 40px rgba(124, 58, 237, 0.15);
        }
        .feature-card:hover .feature-icon {
            transform: scale(1.1) rotate(5deg);
        }
    }

    /* ===== SCROLL ANIMATION ===== */
    .scroll-animate {
        opacity: 0;
        transform: translateY(40px);
        transition: opacity 0.6s ease, transform 0.6s ease;
    }
    .scroll-animate.visible {
        opacity: 1;
        transform: translateY(0);
    }

    /* ===== COUNSELOR CARD ANIMATIONS ===== */
    .counselor-card {
        position: relative;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        cursor: pointer;
    }
    .counselor-card:hover {
        transform: translateY(-8px) scale(1.02);
        box-shadow: 0 25px 50px rgba(124, 58, 237, 0.25);
    }
    .counselor-card:hover .avatar-circle {
        transform: scale(1.05);
        transition: transform 0.3s ease;
    }
    .counselor-card:hover .rotating-border {
        animation-duration: 4s;
    }
    .counselor-card:hover .teacher-name {
        color: #7C3AED;
        transition: color 0.3s ease;
    }
    .counselor-card::before {
        content: '';
        position: absolute;
        inset: 0;
        border-radius: 1rem;
        padding: 2px;
        background: linear-gradient(135deg, #7C3AED, #EC4899);
        -webkit-mask: linear-gradient(#fff 0 0) content-box, linear-gradient(#fff 0 0);
        -webkit-mask-composite: xor;
        mask-composite: exclude;
        opacity: 0;
        transition: opacity 0.3s ease;
    }
    .counselor-card:hover::before {
        opacity: 1;
    }

    /* Avatar Container */
    .avatar-container {
        position: relative;
        width: 120px;
        height: 120px;
        margin: 0 auto 1rem;
    }

    /* Avatar Image/Initial Circle */
    .avatar-circle {
        width: 100%;
        height: 100%;
        border-radius: 50%;
        position: relative;
        z-index: 2;
    }

    /* Rotating Border with Icons */
    .rotating-border {
        position: absolute;
        top: -8px;
        left: -8px;
        width: calc(100% + 16px);
        height: calc(100% + 16px);
        border-radius: 50%;
        animation: rotate 8s linear infinite;
        z-index: 1;
    }

    @keyframes rotate {
        from { transform: rotate(0deg); }
        to { transform: rotate(360deg); }
    }

    /* Icons on rotating border */
    .icon-orbit {
        position: absolute;
        width: 28px;
        height: 28px;
        background: linear-gradient(135deg, #7C3AED, #5B21B6);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 12px;
        box-shadow: 0 2px 8px rgba(124, 58, 237, 0.4);
    }

    .icon-orbit-1 {
        top: 0;
        left: 50%;
        transform: translate(-50%, -50%);
    }

    .icon-orbit-2 {
        top: 50%;
        right: 0;
        transform: translate(50%, -50%);
    }

    .icon-orbit-3 {
        bottom: 0;
        left: 50%;
        transform: translate(-50%, 50%);
    }

    .icon-orbit-4 {
        top: 50%;
        left: 0;
        transform: translate(-50%, -50%);
    }

    /* Pulse effect on avatar */
    .avatar-pulse {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        border-radius: 50%;
        background: linear-gradient(135deg, rgba(124, 58, 237, 0.2), rgba(91, 33, 182, 0.2));
        animation: pulse 2s ease-in-out infinite;
        z-index: 0;
    }

    @keyframes pulse {
        0%, 100% {
            transform: scale(1);
            opacity: 0.5;
        }
        50% {
            transform: scale(1.1);
            opacity: 0.2;
        }
    }

    /* Testimonial Avatar */
    .testimonial-avatar {
        width: 44px;
        height: 44px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 700;
        font-size: 1rem;
        flex-shrink: 0;
        transition: all 0.3s ease;
    }

    /* ===== TESTIMONIAL CARD HOVER ===== */
    .testimonial-card {
        position: relative;
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        cursor: pointer;
        background: linear-gradient(135deg, #ffffff 0%, #fefefe 100%);
        will-change: transform;
    }
    .testimonial-card::before {
        content: '';
        position: absolute;
        inset: 0;
        border-radius: 1rem;
        padding: 2px;
        background: linear-gradient(135deg, #f3e8ff, #fce7f3);
        -webkit-mask: linear-gradient(#fff 0 0) content-box, linear-gradient(#fff 0 0);
        -webkit-mask-composite: xor;
        mask-composite: exclude;
        opacity: 0;
        transition: opacity 0.4s ease;
    }
    .testimonial-card:hover::before {
        opacity: 1;
    }
    .testimonial-card:hover {
        transform: translateY(-10px) scale(1.02);
        background: linear-gradient(135deg, #ffffff 0%, #faf5ff 100%);
        box-shadow: 0 20px 40px rgba(124, 58, 237, 0.15);
        border-color: #e9d5ff;
    }
    .testimonial-card:hover .testimonial-avatar {
        transform: scale(1.15) rotate(5deg);
        box-shadow: 0 8px 20px rgba(124, 58, 237, 0.3);
    }
    .testimonial-card:hover .star-rating i {
        transform: scale(1.1);
    }
    .star-rating i {
        transition: all 0.3s ease;
        display: inline-block;
    }
    .testimonial-card:hover .testimonial-name {
        color: #7C3AED;
    }
    .testimonial-name {
        transition: color 0.3s ease;
    }
    .testimonial-content {
        transition: color 0.3s ease;
    }
    .testimonial-card:hover .testimonial-content {
        color: #374151;
    }
    
    /* Mobile optimization for testimonials */
    @media (max-width: 768px) {
        .testimonial-card:hover {
            transform: translateY(-6px) scale(1.01);
            box-shadow: 0 15px 30px rgba(124, 58, 237, 0.12);
        }
        .testimonial-card:hover .testimonial-avatar {
            transform: scale(1.1) rotate(3deg);
        }
    }
    
    /* Reduce motion for testimonials */
    @media (prefers-reduced-motion: reduce) {
        .testimonial-card,
        .testimonial-avatar,
        .star-rating i {
            transition: none;
        }
        .testimonial-card:hover {
            transform: translateY(-4px);
        }
        .testimonial-card:hover .testimonial-avatar {
            transform: scale(1.05);
        }
    }

    /* Progress Bar Animation */
    @keyframes slideIn {
        from {
            width: 0;
            opacity: 0;
        }
        to {
            opacity: 1;
        }
    }

    /* Mobile Optimizations */
    @media (max-width: 640px) {
        .avatar-container {
            width: 100px;
            height: 100px;
        }
        .icon-orbit {
            width: 24px;
            height: 24px;
            font-size: 10px;
        }
        .counselor-card:hover {
            transform: translateY(-4px) scale(1.01);
        }
    }

    /* Background glow on hover */
    .counselor-card:hover {
        background: linear-gradient(135deg, rgba(255, 255, 255, 1) 0%, rgba(124, 58, 237, 0.08) 100%);
    }

    /* ===== HOW TO USE STEPS HOVER ===== */
    .step-card {
        position: relative;
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        cursor: pointer;
        padding: 2rem;
        border-radius: 1.5rem;
        background: linear-gradient(135deg, #ffffff 0%, #faf5ff 100%);
        border: 2px solid transparent;
    }
    .step-card::before {
        content: '';
        position: absolute;
        inset: 0;
        border-radius: 1.5rem;
        padding: 2px;
        background: linear-gradient(135deg, #e9d5ff, #ddd6fe);
        -webkit-mask: linear-gradient(#fff 0 0) content-box, linear-gradient(#fff 0 0);
        -webkit-mask-composite: xor;
        mask-composite: exclude;
        opacity: 0;
        transition: opacity 0.4s ease;
    }
    .step-card:hover::before {
        opacity: 1;
    }
    .step-card:hover {
        transform: translateY(-12px);
        background: linear-gradient(135deg, #ffffff 0%, #f3e8ff 100%);
        box-shadow: 0 20px 40px rgba(124, 58, 237, 0.15);
    }
    .step-number {
        transition: all 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
        position: relative;
        z-index: 10;
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
    }
    .step-card:hover .step-number {
        transform: scale(1.2) rotate(360deg);
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.3);
    }
    /* Special orange glow for step 2 */
    .step-card:hover .step-number.orange-glow {
        box-shadow: 0 15px 40px rgba(251, 146, 60, 0.6), 0 0 30px rgba(251, 146, 60, 0.4);
    }
    .step-number.orange-glow {
        box-shadow: 0 8px 25px rgba(251, 146, 60, 0.4);
    }
    .step-card:hover .step-title {
        color: #7C3AED;
        transform: translateY(-2px);
    }
    .step-title {
        transition: all 0.3s ease;
    }
    .step-description {
        transition: all 0.3s ease;
    }
    .step-card:hover .step-description {
        color: #4b5563;
    }
    
    /* Ensure numbers are always visible */
    .step-number {
        font-weight: 900;
        font-size: 1.5rem;
        line-height: 1;
        will-change: transform;
    }
    
    /* Mobile optimization - lighter animations */
    @media (max-width: 768px) {
        .step-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 10px 25px rgba(124, 58, 237, 0.12);
        }
        .step-card:hover .step-number {
            transform: scale(1.1) rotate(180deg);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
        }
        .step-number {
            font-size: 1.25rem;
        }
    }
    
    /* Reduce motion for users who prefer it */
    @media (prefers-reduced-motion: reduce) {
        .step-card,
        .step-number,
        .step-title,
        .step-description {
            transition: none;
        }
        .step-card:hover {
            transform: none;
        }
        .step-card:hover .step-number {
            transform: scale(1.05);
        }
    }

    /* Smooth transitions for all interactive elements */
    * {
        -webkit-tap-highlight-color: transparent;
    }

    button, a {
        -webkit-touch-callout: none;
        user-select: none;
    }
</style>

<!-- HERO SECTION -->
<section class="py-12 sm:py-16 lg:py-20 mb-8 sm:mb-12">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 lg:gap-12 items-center">
            <!-- Left: Content -->
            <div class="fade-in-up">
                <h1 class="text-3xl sm:text-4xl lg:text-5xl font-bold leading-tight mb-4 lg:mb-6">
                    Lapor Masalah,<br>
                    <span class="gradient-text">Dapat Solusi Cepat</span>
                </h1>
                <p class="text-base sm:text-lg text-gray-600 mb-6 lg:mb-8 leading-relaxed">
                    Platform laporan anonim khusus siswa. Ceritakan masalahmu dengan aman, guru BK akan membantu.
                </p>
                <div class="flex flex-col sm:flex-row gap-3 sm:gap-4">
                    <a href="/create" class="inline-flex items-center justify-center bg-purple-600 hover:bg-purple-700 text-white font-bold py-3 px-6 sm:px-8 rounded-xl transition transform hover:scale-105">
                        <i class="fas fa-plus mr-2"></i> Buat Laporan
                    </a>
                    <a href="/track" class="inline-flex items-center justify-center bg-white hover:bg-gray-50 text-purple-600 font-bold py-3 px-6 sm:px-8 rounded-xl border-2 border-purple-200 transition">
                        <i class="fas fa-search mr-2"></i> Lacak Laporan
                    </a>
                </div>
            </div>

            <!-- Right: Illustration Card -->
            <div class="fade-in-up stagger-1 hidden md:flex">
                <div class="relative w-full">
                    <div class="absolute inset-0 bg-gradient-to-r from-purple-200 to-pink-200 rounded-2xl blur-3xl opacity-50"></div>
                    <div class="relative bg-gradient-to-br from-purple-500 to-purple-700 rounded-2xl p-8 text-white shadow-2xl">
                        <div class="flex items-center justify-between mb-6">
                            <h3 class="text-lg font-bold">Laporan Aman</h3>
                            <div class="w-12 h-12 bg-white/20 rounded-full flex items-center justify-center">
                                <i class="fas fa-shield-halved text-xl"></i>
                            </div>
                        </div>
                        <p class="text-sm text-white/80 mb-6">Privasi Anda adalah prioritas kami</p>
                        <div class="space-y-3">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 bg-white/20 rounded-lg flex items-center justify-center text-xs">
                                    <i class="fas fa-lock text-white"></i>
                                </div>
                                <span class="text-sm">Identitas Tersembunyi</span>
                            </div>
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 bg-white/20 rounded-lg flex items-center justify-center text-xs">
                                    <i class="fas fa-eye-slash text-white"></i>
                                </div>
                                <span class="text-sm">Chat Privat</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- FEATURES SECTION -->
<section class="py-12 sm:py-16 mb-8 sm:mb-12">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-10 sm:mb-12 scroll-animate">
            <h2 class="text-2xl sm:text-3xl lg:text-4xl font-bold text-gray-900 mb-3 sm:mb-4">
                Kenapa Pilih Sistem Kami?
            </h2>
            <p class="text-gray-600 text-sm sm:text-base max-w-2xl mx-auto">
                Platform terpercaya untuk siswa melaporkan masalah dengan aman dan mendapat penanganan cepat dari guru BK
            </p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
            <!-- Feature 1 -->
            <div class="scroll-animate">
                <div class="feature-card bg-white rounded-2xl p-6 sm:p-8 border border-purple-100 h-full">
                    <div class="feature-icon w-14 h-14 bg-gradient-to-br from-purple-100 to-purple-200 rounded-xl flex items-center justify-center text-2xl text-purple-600 mb-4">
                        <i class="fas fa-lock"></i>
                    </div>
                    <h3 class="feature-title text-lg font-bold text-gray-900 mb-2">Anonim & Aman</h3>
                    <p class="feature-description text-sm text-gray-600">Identitas Anda terlindungi sepenuhnya. Lapor tanpa takut.</p>
                </div>
            </div>

            <!-- Feature 2 -->
            <div class="scroll-animate">
                <div class="feature-card bg-white rounded-2xl p-6 sm:p-8 border border-purple-100 h-full">
                    <div class="feature-icon w-14 h-14 bg-gradient-to-br from-blue-100 to-blue-200 rounded-xl flex items-center justify-center text-2xl text-blue-600 mb-4">
                        <i class="fas fa-bolt"></i>
                    </div>
                    <h3 class="feature-title text-lg font-bold text-gray-900 mb-2">Respons Cepat</h3>
                    <p class="feature-description text-sm text-gray-600">Laporan langsung diterima guru BK dan ditangani prioritas.</p>
                </div>
            </div>

            <!-- Feature 3 -->
            <div class="scroll-animate">
                <div class="feature-card bg-white rounded-2xl p-6 sm:p-8 border border-purple-100 h-full">
                    <div class="feature-icon w-14 h-14 bg-gradient-to-br from-green-100 to-green-200 rounded-xl flex items-center justify-center text-2xl text-green-600 mb-4">
                        <i class="fas fa-chart-line"></i>
                    </div>
                    <h3 class="feature-title text-lg font-bold text-gray-900 mb-2">Transparan</h3>
                    <p class="feature-description text-sm text-gray-600">Pantau progres laporan Anda kapan saja dengan kode unik.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- STATS SECTION -->
<section class="py-12 sm:py-16 mb-8 sm:mb-12">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-gradient-to-r from-purple-600 to-purple-700 rounded-2xl text-white py-12 sm:py-16 px-6 scroll-animate">
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-8 text-center">
                <div>
                    <div class="text-4xl sm:text-5xl font-bold mb-2"><?php echo e($stats['total']); ?>+</div>
                    <p class="text-purple-100">Laporan Tertangani</p>
                </div>
                <div>
                    <div class="text-4xl sm:text-5xl font-bold mb-2"><?php echo e($stats['completed_percent']); ?>%</div>
                    <p class="text-purple-100">Laporan Selesai</p>
                </div>
                <div>
                    <div class="text-4xl sm:text-5xl font-bold mb-2"><?php echo e($stats['schools']); ?></div>
                    <p class="text-purple-100">Sekolah Aktif</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- HOW TO USE SECTION -->
<section class="py-12 sm:py-16 mb-8 sm:mb-12">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="text-2xl sm:text-3xl font-bold text-gray-900 mb-10 sm:mb-12 text-center scroll-animate">
            Cara Menggunakan
        </h2>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 sm:gap-8">
            <!-- Step 1 -->
            <div class="scroll-animate step-card">
                <div class="step-number flex items-center justify-center w-16 h-16 bg-purple-600 text-white rounded-full font-extrabold text-2xl mb-6 mx-auto shadow-lg">
                    1
                </div>
                <h3 class="step-title text-lg font-bold text-gray-900 text-center mb-3 transition-colors duration-300">Isi Form Laporan</h3>
                <p class="step-description text-sm text-gray-600 text-center leading-relaxed">
                    Ceritakan masalahmu dengan jujur dan jelas. Pilih kategori yang sesuai. Tanpa login!
                </p>
            </div>

            <!-- Step 2 -->
            <div class="scroll-animate step-card">
    <div class="step-number flex items-center justify-center w-16 h-16 text-white rounded-full font-extrabold text-2xl mb-6 mx-auto shadow-lg relative z-10" 
         style="background-color: #ff0000ff;">
        2
    </div>
    <h3 class="step-title text-lg font-bold text-gray-900 text-center mb-3 transition-colors duration-300">
        Simpan Kode Unik
    </h3>
    <p class="step-description text-sm text-gray-600 text-center leading-relaxed">
        Catat kode laporan untuk melacak perkembangan dan berkomunikasi dengan BK.
    </p>
</div>

            <!-- Step 3 -->
            <div class="scroll-animate step-card">
                <div class="step-number flex items-center justify-center w-16 h-16 bg-green-600 text-white rounded-full font-extrabold text-2xl mb-6 mx-auto shadow-lg">
                    3
                </div>
                <h3 class="step-title text-lg font-bold text-gray-900 text-center mb-3 transition-colors duration-300">Pantau & Chat BK</h3>
                <p class="step-description text-sm text-gray-600 text-center leading-relaxed">
                    Lihat status real-time dan chat langsung dengan guru BK untuk bantuan lebih.
                </p>
            </div>
        </div>
    </div>
</section>

<!-- COUNSELOR SECTION - ENHANCED -->
<?php if($teachers->isNotEmpty()): ?>
<section class="py-12 sm:py-16 mb-8 sm:mb-12 bg-gradient-to-b from-purple-50/50 to-white">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-10 sm:mb-12 scroll-animate">
            <h2 class="text-2xl sm:text-3xl lg:text-4xl font-bold text-gray-900 mb-3 sm:mb-4">
                <span class="gradient-text">Konselor SMP</span>
            </h2>
            <p class="text-base sm:text-lg text-gray-600 max-w-2xl mx-auto">
                Guru BK yang siap membantu Anda di setiap sekolah
            </p>
        </div>

        <div class="space-y-10">
            <?php $__currentLoopData = $teachers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $schoolName => $schoolTeachers): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="scroll-animate">
                    <!-- School Name Header -->
                    <div class="bg-gradient-to-r from-purple-600 to-purple-700 rounded-t-2xl p-5 shadow-lg">
                        <h3 class="text-lg sm:text-xl font-bold text-white flex items-center gap-3">
                            <div class="w-10 h-10 bg-white/20 rounded-lg flex items-center justify-center">
                                <i class="fas fa-school text-white"></i>
                            </div>
                            <?php echo e($schoolName); ?>

                        </h3>
                    </div>

                    <!-- Teachers Grid -->
                    <div class="bg-white rounded-b-2xl border-2 border-purple-100 shadow-lg p-6 sm:p-8">
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 sm:gap-8">
                            <?php $__currentLoopData = $schoolTeachers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $teacher): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="counselor-card bg-gradient-to-br from-white to-purple-50/50 rounded-2xl p-6 border-2 border-purple-100 hover:border-purple-300">
                                    <!-- Avatar Container with Rotating Icons -->
                                    <div class="avatar-container">
                                        <!-- Pulse Effect -->
                                        <div class="avatar-pulse"></div>
                                        
                                        <!-- Rotating Border with Icons -->
                                        <div class="rotating-border">
                                            <div class="icon-orbit icon-orbit-1">
                                                <i class="fas fa-book"></i>
                                            </div>
                                            <div class="icon-orbit icon-orbit-2">
                                                <i class="fas fa-pen"></i>
                                            </div>
                                            <div class="icon-orbit icon-orbit-3">
                                                <i class="fas fa-graduation-cap"></i>
                                            </div>
                                            <div class="icon-orbit icon-orbit-4">
                                                <i class="fas fa-heart"></i>
                                            </div>
                                        </div>

                                        <!-- Avatar Image/Initial -->
                                        <?php if($teacher->profile_photo): ?>
                                            <img src="<?php echo e(asset('storage/' . $teacher->profile_photo)); ?>" 
                                                 alt="<?php echo e($teacher->name); ?>" 
                                                 class="avatar-circle object-cover border-4 border-white shadow-xl">
                                        <?php else: ?>
                                            <div class="avatar-circle bg-gradient-to-br from-purple-400 to-purple-600 flex items-center justify-center text-white text-3xl font-bold border-4 border-white shadow-xl">
                                                <?php echo e(strtoupper(substr($teacher->name, 0, 1))); ?>

                                            </div>
                                        <?php endif; ?>
                                    </div>

                                    <!-- Teacher Info -->
                                    <div class="text-center">
                                        <h4 class="teacher-name font-bold text-gray-900 text-base sm:text-lg mb-1 transition-colors duration-300">
                                            <?php echo e($teacher->name); ?>

                                        </h4>
                                        <p class="text-xs sm:text-sm text-purple-600 font-medium mb-2">Guru BK</p>
                                        <p class="text-xs text-gray-500"><?php echo e($schoolName); ?></p>
                                    </div>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- TESTIMONIALS SECTION -->
<section class="py-12 sm:py-16 mb-8 sm:mb-12">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="text-2xl sm:text-3xl font-bold text-gray-900 mb-10 sm:mb-12 text-center scroll-animate">
            Apa Kata Mereka
        </h2>
        <?php if($testimonials->count()): ?>
        
        <?php $__currentLoopData = $testimonials; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $schoolName => $schoolTestimonials): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
        <div class="mb-12">
            <h3 class="text-lg sm:text-xl font-semibold text-gray-800 mb-6 scroll-animate flex items-center gap-2">
                <span class="w-1 h-1 bg-purple-600 rounded-full"></span>
                <?php echo e($schoolName); ?>

            </h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                <?php $__currentLoopData = $schoolTestimonials; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $testimonial): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="scroll-animate">
                    <div class="testimonial-card bg-white rounded-2xl p-6 border border-purple-100 h-full flex flex-col shadow-sm">
                        <!-- Star Rating -->
                        <div class="star-rating flex items-center gap-1 mb-4">
                            <?php for($i = 0; $i < 5; $i++): ?>
                                <i class="fas fa-star <?php echo e($i < $testimonial->rating ? 'text-amber-400' : 'text-gray-300'); ?> text-sm"></i>
                            <?php endfor; ?>
                        </div>
                        <p class="testimonial-content text-sm text-gray-600 flex-1 mb-4 leading-relaxed">
                            "<?php echo e(Str::limit($testimonial->content, 150)); ?>"
                        </p>
                        <div class="flex items-center gap-3">
                            <div class="testimonial-avatar bg-gradient-to-br from-purple-400 to-pink-400 text-white">
                                <?php
                                    $name = $testimonial->is_anonymous ? 'A' : substr($testimonial->student_name, 0, 1);
                                ?>
                                <?php echo e(strtoupper($name)); ?>

                            </div>
                            <div>
                                <p class="testimonial-name text-sm font-semibold text-gray-900"><?php echo e($testimonial->is_anonymous ? 'Murid Anonim' : $testimonial->student_name); ?></p>
                                <p class="text-xs text-gray-500"><?php echo e($testimonial->report->nama_sekolah ?? 'Siswa Puas'); ?></p>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        <?php else: ?>
        <div class="max-w-xl mx-auto scroll-animate">
            <div class="bg-white/60 border border-gray-200 rounded-xl p-6 text-center">
                <p class="text-gray-700 font-semibold mb-2">Belum ada testimoni yang disetujui.</p>
                <p class="text-sm text-gray-500">Testimoni yang muncul di sini adalah testimoni terbaru yang telah disetujui oleh guru.</p>
            </div>
        </div>
        <?php endif; ?>
    </div>
</section>

<!-- REPORT TYPES SECTION -->
<?php if($jenisBreakdown->count()): ?>
<section class="py-12 sm:py-16 mb-8 sm:mb-12">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <h2 class="text-2xl sm:text-3xl font-bold text-gray-900 mb-10 sm:mb-12 text-center scroll-animate">
            Jenis Laporan Terbanyak
        </h2>

        <div class="bg-white rounded-2xl p-6 sm:p-8 border border-purple-100 shadow-sm scroll-animate max-w-4xl mx-auto">
            <div class="space-y-6">
                <?php
                    $jenisIcons = [
                        'akademik' => 'fas fa-book-open',
                        'bullying' => 'fas fa-shield-halved',
                        'perundungan' => 'fas fa-hand-fist',
                        'kedisiplinan' => 'fas fa-user-check',
                        'keluarga' => 'fas fa-house',
                        'pertemanan' => 'fas fa-user-friends',
                        'kesehatan' => 'fas fa-heart-pulse',
                        'lainnya' => 'fas fa-ellipsis',
                    ];
                ?>
                <?php $__currentLoopData = $jenisBreakdown; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div>
                    <div class="flex items-center justify-between mb-3">
                        <div class="flex items-center gap-3 flex-1">
                            <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center text-purple-600 flex-shrink-0">
                                <i class="<?php echo e($jenisIcons[$item['jenis']] ?? 'fas fa-circle'); ?>"></i>
                            </div>
                            <span class="font-semibold text-gray-900 text-sm sm:text-base"><?php echo e(ucfirst($item['jenis'])); ?></span>
                        </div>
                        <span class="text-sm sm:text-base font-bold text-purple-600 ml-4 flex-shrink-0"><?php echo e($item['percent']); ?>%</span>
                    </div>
                    <div class="w-full h-3 bg-gray-200 rounded-full overflow-hidden">
                        <div class="h-full bg-gradient-to-r from-purple-500 to-purple-600 rounded-full transition-all duration-500" 
                             style="width: <?php echo e($item['percent']); ?>%; animation: slideIn 0.8s ease forwards; animation-delay: 0.2s;">
                        </div>
                    </div>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- CTA SECTION -->
<section class="py-12 sm:py-16 mb-8 sm:mb-12">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <div class="bg-gradient-to-r from-purple-600 via-purple-500 to-pink-500 rounded-2xl text-white py-12 sm:py-16 px-6 scroll-animate shadow-2xl">
            <div class="text-center max-w-3xl mx-auto">
                <h2 class="text-2xl sm:text-3xl lg:text-4xl font-bold mb-4 sm:mb-6">
                    Siap Cerita Masalahmu?
                </h2>
                <p class="text-base sm:text-lg text-white/90 mb-6 sm:mb-8">
                    Jangan tahan lagi. Lapor sekarang dan dapatkan bantuan dari guru BK yang peduli.
                </p>
                <div class="flex flex-col sm:flex-row gap-3 sm:gap-4 justify-center">
                    <a href="/create" class="inline-flex items-center justify-center bg-white text-purple-600 font-bold py-3 px-8 rounded-xl hover:bg-gray-50 transition transform hover:scale-105 shadow-lg text-sm sm:text-base">
                        <i class="fas fa-pen-to-square mr-2"></i>
                        Buat Laporan Sekarang
                    </a>
                    <a href="/track" class="inline-flex items-center justify-center bg-white/20 hover:bg-white/30 text-white font-bold py-3 px-8 rounded-xl border-2 border-white/50 transition text-sm sm:text-base">
                        <i class="fas fa-search mr-2"></i>
                        Atau Lacak Laporan
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Scroll Animation Script -->
<script>
    // Intersection Observer for scroll animations
    document.addEventListener('DOMContentLoaded', function() {
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };

        const observer = new IntersectionObserver(function(entries) {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('visible');
                    observer.unobserve(entry.target);
                }
            });
        }, observerOptions);

        // Observe all elements with scroll-animate class
        document.querySelectorAll('.scroll-animate').forEach(element => {
            observer.observe(element);
        });
    });

    // Smooth scroll for anchor links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });
</script>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.student', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\ngoding\sistem-cinta\resources\views/home.blade.php ENDPATH**/ ?>