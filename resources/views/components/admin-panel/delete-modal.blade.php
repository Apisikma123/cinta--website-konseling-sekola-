<div x-data="deleteModal()" 
     x-show="isOpen" 
     x-cloak
     class="fixed inset-0 z-50 flex items-center justify-center bg-black/20 transition-opacity duration-300"
     @keydown.escape="close()">
    
    <div class="bg-white rounded-lg shadow-lg max-w-md w-full mx-4 p-6 space-y-6"
         @click.stop>
        
        <!-- Header -->
        <div>
            <h2 class="text-lg font-semibold text-gray-900">Hapus Item</h2>
            <p class="text-sm text-gray-600 mt-1">Tindakan ini tidak dapat dibatalkan. Silakan ketik "HAPUS" untuk mengkonfirmasi.</p>
        </div>

        <!-- Input -->
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-2">Ketik "HAPUS" untuk mengkonfirmasi</label>
            <input type="text" 
                   x-model="confirmText" 
                   @input="updateButtonState()"
                   placeholder="HAPUS"
                   class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-transparent text-base font-medium">
        </div>

        <!-- Actions -->
        <div class="flex gap-3 pt-4">
            <button @click="close()" 
                    class="flex-1 px-4 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors duration-200">
                Batal
            </button>
            <button @click="confirm()" 
                    :disabled="!isConfirmed"
                    :class="isConfirmed ? 'bg-red-600 hover:bg-red-700 text-white' : 'bg-gray-100 text-gray-400 cursor-not-allowed'"
                    class="flex-1 px-4 py-2 text-sm font-medium rounded-lg transition-colors duration-200">
                Hapus
            </button>
        </div>
    </div>
</div>

<script>
function deleteModal() {
    return {
        isOpen: false,
        confirmText: '',
        isConfirmed: false,
        onConfirm: null,

        open(callback) {
            this.isOpen = true;
            this.confirmText = '';
            this.isConfirmed = false;
            this.onConfirm = callback;
        },

        close() {
            this.isOpen = false;
            this.confirmText = '';
            this.isConfirmed = false;
            this.onConfirm = null;
        },

        updateButtonState() {
            this.isConfirmed = this.confirmText === 'HAPUS';
        },

        confirm() {
            if (this.isConfirmed && this.onConfirm) {
                this.onConfirm();
                this.close();
            }
        }
    };
}
</script>
