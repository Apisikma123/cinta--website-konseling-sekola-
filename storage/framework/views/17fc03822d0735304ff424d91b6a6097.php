<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo e($title ?? 'Admin'); ?> · Admin Panel</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <link rel="icon" href="<?php echo e(asset('img/icon.png')); ?>" type="image/png">
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css', 'resources/js/app.js']); ?>
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        *, *::before, *::after { box-sizing: border-box; }
        [x-cloak] { display: none !important; }
        html, body { font-family: 'Inter', system-ui, -apple-system, sans-serif; margin: 0; padding: 0; }
        
        /* Layout wrapper */
        .layout-wrapper { display: flex; min-height: 100vh; }
        
        /* Sidebar - Desktop: static, Mobile: fixed */
        .sidebar { 
            width: 256px; 
            background: white; 
            border-right: 1px solid #e5e7eb; 
            flex-shrink: 0;
            display: flex;
            flex-direction: column;
        }
        
        /* Main content */
        .main-content { 
            flex: 1; 
            display: flex; 
            flex-direction: column; 
            min-width: 0;
            min-height: 100vh;
        }
        
        /* Content area */
        .content-area {
            flex: 1;
            display: flex;
            flex-direction: column;
        }
        
        .content-wrapper {
            flex: 1;
            width: 100%;
        }
        
        /* Mobile styles */
        @media (max-width: 1023px) {
            .sidebar { 
                display: none; 
                position: fixed; 
                left: 0; 
                top: 0; 
                bottom: 0; 
                z-index: 50;
            }
            .sidebar.mobile-open { 
                display: flex; 
            }
            .mobile-overlay {
                display: none;
                position: fixed;
                inset: 0;
                background: rgba(0,0,0,0.5);
                z-index: 40;
            }
            .mobile-overlay.show { display: block; }
            .hamburger { display: flex !important; }
        }
        
        @media (min-width: 1024px) {
            .hamburger { display: none !important; }
            .mobile-overlay { display: none !important; }
            .sidebar { 
                display: flex !important; 
                position: static !important;
            }
        }

        /* SweetAlert2 - Minimal styling, preserve default animations */
        .swal2-container { font-family: 'Inter', sans-serif !important; }
        .swal2-popup { border-radius: 12px !important; border: 1px solid #e5e7eb !important; box-shadow: 0 20px 40px rgba(0,0,0,0.1) !important; padding: 28px 24px 20px !important; }
        .swal2-title { font-size: 16px !important; font-weight: 700 !important; color: #111827 !important; }
        .swal2-html-container { font-size: 14px !important; color: #6b7280 !important; line-height: 1.6 !important; }
        .swal2-actions { gap: 8px !important; margin-top: 20px !important; }
        .swal2-styled { border-radius: 8px !important; padding: 8px 18px !important; font-size: 14px !important; font-weight: 600 !important; font-family: 'Inter', sans-serif !important; box-shadow: none !important; }
        .swal2-confirm { background: #7c3aed !important; }
        .swal2-confirm:hover { background: #6d28d9 !important; }
        .swal2-cancel { background: #f3f4f6 !important; color: #374151 !important; }
        .swal2-cancel:hover { background: #e5e7eb !important; }
        .swal2-deny { background: #dc2626 !important; }
        .swal2-deny:hover { background: #b91c1c !important; }
        .swal2-input { border-radius: 8px !important; border: 1px solid #d1d5db !important; font-size: 14px !important; font-family: monospace !important; box-shadow: none !important; padding: 8px 12px !important; height: auto !important; margin: 14px 0 0 !important; }
        .swal2-input:focus { border-color: #7c3aed !important; box-shadow: 0 0 0 3px #f3e8ff !important; }
        .swal2-validation-message { background: none !important; color: #dc2626 !important; font-size: 12px !important; margin-top: 6px !important; }
        /* Preserve default SweetAlert2 icon animations - no custom sizing that breaks animations */
    </style>
</head>
<body class="bg-gray-50 text-gray-900" x-data="{ sidebarOpen: false }">

<!-- Mobile Overlay -->
<div class="mobile-overlay" 
     :class="sidebarOpen ? 'show' : ''"
     @click="sidebarOpen = false">
</div>

<!-- Layout Wrapper -->
<div class="layout-wrapper">
    
    <!-- Sidebar -->
    <aside class="sidebar" :class="sidebarOpen ? 'mobile-open' : ''">
        <?php echo $__env->make('layouts.partials.admin-sidebar-content', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
    </aside>

    <!-- Main Content -->
    <div class="main-content">
        <!-- Navbar -->
        <header class="sticky top-0 z-30 bg-white border-b border-gray-200 h-16 flex items-center justify-between px-4">
            <div class="flex items-center gap-3">
                <!-- Hamburger - Mobile Only -->
                <button @click="sidebarOpen = true" 
                        class="hamburger p-2 -ml-2 text-gray-500 hover:text-gray-700 rounded-lg hover:bg-gray-100 transition-colors">
                    <i class="fas fa-bars text-lg"></i>
                </button>
                <h1 class="text-sm font-semibold text-gray-900"><?php echo e($title ?? 'Dashboard'); ?></h1>
            </div>
            <?php echo $__env->make('layouts.partials.profile-dropdown', ['role' => 'admin'], array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?>
        </header>

        <!-- Page Content -->
        <main class="content-area bg-gray-50">
            <?php echo $__env->yieldContent('content'); ?>
        </main>
    </div>
</div>

<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>

<script>
    window.SwalUtils = {
        confirm(options) {
            return Swal.fire({
                title: options.title || 'Konfirmasi',
                text: options.text || 'Apakah Anda yakin?',
                icon: options.icon || 'question',
                showCancelButton: true,
                confirmButtonText: options.confirmText || 'Ya, Lanjutkan',
                cancelButtonText: options.cancelText || 'Batal',
                reverseButtons: true,
                confirmButtonColor: '#7c3aed',
            }).then(r => {
                if (r.isConfirmed && options.onConfirm) options.onConfirm();
                return r;
            });
        },

        delete(onConfirm) {
            return Swal.fire({
                title: 'Konfirmasi Hapus',
                html: 'Tindakan ini <strong>tidak dapat dibatalkan</strong>.<br>Ketik <code style="background:#f3f4f6;padding:2px 6px;border-radius:4px;font-size:13px">HAPUS</code> untuk melanjutkan.',
                icon: 'warning',
                input: 'text',
                inputPlaceholder: 'Ketik HAPUS',
                showCancelButton: true,
                confirmButtonText: 'Hapus',
                confirmButtonColor: '#dc2626',
                cancelButtonText: 'Batal',
                reverseButtons: true,
                preConfirm: val => {
                    if (val !== 'HAPUS') {
                        Swal.showValidationMessage('Teks tidak sesuai. Ketik HAPUS (huruf kapital semua).');
                        return false;
                    }
                    return true;
                }
            }).then(r => { 
                if (r.isConfirmed) onConfirm(); 
            });
        },

        success(message) {
            return Swal.fire({
                icon: 'success',
                title: 'Berhasil',
                text: message,
                confirmButtonText: 'Tutup',
                confirmButtonColor: '#7c3aed',
                timer: 3500,
                timerProgressBar: false,
            });
        },

        error(message) {
            return Swal.fire({
                icon: 'error',
                title: 'Terjadi Kesalahan',
                text: message,
                confirmButtonText: 'Tutup',
                confirmButtonColor: '#7c3aed',
            });
        },

        toast(message, icon = 'success') {
            Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 2500,
            }).fire({ icon, title: message });
        },

        approve(options) {
            return this.confirm({ icon: 'question', confirmButtonColor: '#7c3aed', ...options });
        }
    };

    document.addEventListener('DOMContentLoaded', () => {
        <?php if(session('success')): ?>
            SwalUtils.toast("<?php echo e(addslashes(session('success'))); ?>", 'success');
        <?php endif; ?>
        <?php if(session('error')): ?>
            SwalUtils.error("<?php echo e(addslashes(session('error'))); ?>");
        <?php endif; ?>
        <?php if(session('info')): ?>
            SwalUtils.toast("<?php echo e(addslashes(session('info'))); ?>", 'info');
        <?php endif; ?>
    });
</script>

<?php echo $__env->yieldPushContent('scripts'); ?>
</body>
</html>
<?php /**PATH D:\ngoding\sistem-cinta\resources\views/layouts/admin.blade.php ENDPATH**/ ?>