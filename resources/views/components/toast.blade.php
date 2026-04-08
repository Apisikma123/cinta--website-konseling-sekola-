<div id="toast-container" style="position:fixed;right:1rem;top:1rem;z-index:9999"></div>
<script>
    (function () {
        if (window.showToast) return; // don't override if already present
        window.showToast = function (message, type = 'info') {
            const container = document.getElementById('toast-container');
            const el = document.createElement('div');
            el.className = 'px-4 py-2 rounded shadow mb-2 text-sm text-white';
            el.style.minWidth = '220px';
            el.style.opacity = '0.98';
            el.style.transition = 'transform 0.25s ease, opacity 0.25s ease';
            if (type === 'success') el.style.background = '#059669';
            else if (type === 'warning') el.style.background = '#D97706';
            else if (type === 'error') el.style.background = '#DC2626';
            else el.style.background = '#111827';
            el.textContent = message;
            container.appendChild(el);
            requestAnimationFrame(() => el.style.transform = 'translateY(0)');
            setTimeout(() => { el.style.opacity = '0'; el.style.transform = 'translateY(-10px)'; setTimeout(() => el.remove(), 400); }, 3500);
        }
    })();
</script>