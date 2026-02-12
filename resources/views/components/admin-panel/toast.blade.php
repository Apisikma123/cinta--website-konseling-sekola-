<div x-data="toastManager()" class="fixed bottom-4 right-4 z-50 space-y-3 max-w-md">
    <template x-for="toast in toasts" :key="toast.id">
        <div x-show="toast.visible"
             x-transition:enter="transition-fade duration-300"
             x-transition:enter-start="opacity-0 translate-y-2"
             x-transition:enter-end="opacity-100 translate-y-0"
             x-transition:leave="transition-fade duration-300"
             x-transition:leave-start="opacity-100 translate-y-0"
             x-transition:leave-end="opacity-0 translate-y-2"
             :class="getToastClass(toast.type)"
             class="p-4 rounded-lg shadow-lg flex items-start gap-3">
            
            <div class="flex-shrink-0 mt-0.5">
                <template x-if="toast.type === 'success'">
                    <i data-feather="check-circle" class="w-5 h-5"></i>
                </template>
                <template x-if="toast.type === 'error'">
                    <i data-feather="alert-circle" class="w-5 h-5"></i>
                </template>
                <template x-if="toast.type === 'info'">
                    <i data-feather="info" class="w-5 h-5"></i>
                </template>
            </div>

            <div class="flex-1">
                <p class="text-sm font-medium" x-text="toast.message"></p>
            </div>

            <button @click="removeToast(toast.id)" class="flex-shrink-0 text-current opacity-70 hover:opacity-100">
                <i data-feather="x" class="w-4 h-4"></i>
            </button>
        </div>
    </template>
</div>

<script>
function toastManager() {
    return {
        toasts: [],
        nextId: 0,

        show(message, type = 'info', duration = 3000) {
            const id = this.nextId++;
            const toast = {
                id,
                message,
                type,
                visible: true
            };

            this.toasts.push(toast);

            if (duration > 0) {
                setTimeout(() => {
                    this.removeToast(id);
                }, duration);
            }

            return id;
        },

        removeToast(id) {
            const index = this.toasts.findIndex(t => t.id === id);
            if (index !== -1) {
                this.toasts[index].visible = false;
                setTimeout(() => {
                    this.toasts.splice(index, 1);
                }, 300);
            }
        },

        getToastClass(type) {
            const classes = {
                success: 'bg-emerald-50 text-emerald-700 border border-emerald-200',
                error: 'bg-red-50 text-red-700 border border-red-200',
                info: 'bg-blue-50 text-blue-700 border border-blue-200'
            };
            return classes[type] || classes.info;
        }
    };
}

// Global toast function
window.showToast = function(message, type = 'info', duration = 3000) {
    const manager = document.querySelector('[x-data*="toastManager"]')?.__x;
    if (manager) {
        manager.show(message, type, duration);
    }
};
</script>
