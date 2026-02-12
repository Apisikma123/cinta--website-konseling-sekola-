<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    (function () {
        // Initialize SweetAlert Toast mixin
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3500,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer)
                toast.addEventListener('mouseleave', Swal.resumeTimer)
            }
        });

        // Bridge function to match existing showToast signature
        window.showToast = function (message, type = 'info') {
            Toast.fire({
                icon: type,
                title: message
            });
        }

        // Bridge function for legacy showNotification calls
        window.showNotification = function (message, type = 'info') {
            window.showToast(message, type);
        }

        // Auto-show session flashes if standard keys are present
        <?php if(session('success')): ?>
            showToast("<?php echo e(session('success')); ?>", 'success');
        <?php endif; ?>

        <?php if(session('error')): ?>
            showToast("<?php echo e(session('error')); ?>", 'error');
        <?php endif; ?>

        <?php if(session('warning')): ?>
            showToast("<?php echo e(session('warning')); ?>", 'warning');
        <?php endif; ?>

        <?php if(session('info')): ?>
            showToast("<?php echo e(session('info')); ?>", 'info');
        <?php endif; ?>
        
        <?php if(session('status')): ?>
            showToast("<?php echo e(session('status')); ?>", 'info');
        <?php endif; ?>
    })();
</script><?php /**PATH D:\ngoding\sistem-cinta\resources\views/components/toast.blade.php ENDPATH**/ ?>