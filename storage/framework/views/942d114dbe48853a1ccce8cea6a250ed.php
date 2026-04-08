<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title><?php echo e($title ?? 'Cinta Login/Register'); ?></title>
    
    <!-- Font Poppins -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href=" https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    
    <!-- SweetAlert2 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    
    <?php echo app('Illuminate\Foundation\Vite')(['resources/css/app.css']); ?>
    <link rel="icon" href="<?php echo e(asset('img/icon.png')); ?>" type="image/png">
    
    <style>
        body { font-family: 'Poppins', sans-serif; }
        .fade-in { opacity: 0; animation: fadeIn 0.5s ease forwards; }
        @keyframes fadeIn { to { opacity: 1; } }

        /* Toast Notification */
        .toast {
            position: fixed;
            top: 20px;
            right: 20px;
            padding: 16px 20px;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            font-weight: 500;
            z-index: 9999;
            animation: slideIn 0.3s ease forwards;
            max-width: 400px;
        }

        @keyframes slideIn {
            from {
                transform: translateX(400px);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }

        .toast.success {
            background-color: #10b981;
            color: white;
            border-left: 4px solid #059669;
        }

        .toast.error {
            background-color: #ef4444;
            color: white;
            border-left: 4px solid #dc2626;
        }

        .toast.warning {
            background-color: #f59e0b;
            color: white;
            border-left: 4px solid #d97706;
        }

        .toast.info {
            background-color: #3b82f6;
            color: white;
            border-left: 4px solid #1d4ed8;
        }

        .toast-close {
            margin-left: 16px;
            cursor: pointer;
            font-size: 18px;
            opacity: 0.8;
        }

        .toast-close:hover {
            opacity: 1;
        }
    </style>
</head>
<body class="bg-purple-50 min-h-screen p-4 sm:p-6">
    <div class="w-full max-w-6xl mx-auto">
        <div class="text-center mb-6 sm:mb-8 fade-in">
            <img src="<?php echo e(asset('img/icon.png')); ?>" alt="Logo" class="w-16 h-16 sm:w-20 sm:h-20 mx-auto mb-4">
            <h1 class="text-xl sm:text-2xl font-bold text-purple-800"><?php echo e($title ?? 'Cinta Login/Register'); ?></h1>
        </div>
        
        <main class="bg-white rounded-2xl shadow-lg p-6 sm:p-8 border border-purple-100 fade-in">
            <?php echo $__env->yieldContent('content'); ?>
        </main>
        
        <footer class="text-center mt-4 sm:mt-6 text-xs text-purple-600">
            © <?php echo e(date('Y')); ?> Cinta Login/Register
        </footer>
    </div>

    <script>
        // Toast notification system
        function showToast(message, type = 'info', duration = 4000) {
            const toastContainer = document.createElement('div');
            toastContainer.className = `toast ${type}`;
            toastContainer.innerHTML = `
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <span>${message}</span>
                    <span class="toast-close" onclick="this.parentElement.parentElement.remove()">&times;</span>
                </div>
            `;
            document.body.appendChild(toastContainer);

            if (duration > 0) {
                setTimeout(() => {
                    toastContainer.style.animation = 'slideOut 0.3s ease forwards';
                    setTimeout(() => toastContainer.remove(), 300);
                }, duration);
            }

            return toastContainer;
        }
    </script>

    <!-- SweetAlert2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>

    <style>
        @keyframes slideOut {
            from {
                transform: translateX(0);
                opacity: 1;
            }
            to {
                transform: translateX(400px);
                opacity: 0;
            }
        }
    </style>
</body>
</html><?php /**PATH D:\ngoding\sistem-cinta\resources\views/layouts/auth.blade.php ENDPATH**/ ?>