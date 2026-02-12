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
        @if(session('success'))
            showToast("{{ session('success') }}", 'success');
        @endif

        @if(session('error'))
            showToast("{{ session('error') }}", 'error');
        @endif

        @if(session('warning'))
            showToast("{{ session('warning') }}", 'warning');
        @endif

        @if(session('info'))
            showToast("{{ session('info') }}", 'info');
        @endif
        
        @if(session('status'))
            showToast("{{ session('status') }}", 'info');
        @endif
    })();
</script>