@extends('layouts.teacher', ['title' => 'Profil Saya'])

@section('content')
{{-- Cropper.js CDN --}}
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.2/cropper.min.css">

<div class="w-full px-4 sm:px-6 lg:px-8 space-y-6" x-data="profileUpload()">

    {{-- Header --}}
    <div>
        <h2 class="text-xl font-bold text-gray-900">Profil Saya</h2>
        <p class="text-sm text-gray-500 mt-1">Informasi akun dan statistik Anda</p>
    </div>

    @if($errors->any())
    <div class="bg-red-50 border border-red-200 rounded-lg px-4 py-3">
        @foreach($errors->all() as $error)
            <p class="text-sm text-red-700">• {{ $error }}</p>
        @endforeach
    </div>
    @endif

    {{-- Success Message --}}
    @if(session('success'))
    <div class="bg-green-50 border border-green-200 rounded-lg px-4 py-3">
        <p class="text-sm text-green-700">{{ session('success') }}</p>
    </div>
    @endif

    {{-- Cards Flex Side by Side --}}
    <div class="flex flex-col lg:flex-row gap-6 items-start">
        
        {{-- Profile Card --}}
        <form method="POST" action="{{ route('teacher.settings.update') }}" enctype="multipart/form-data"
              class="w-full lg:flex-1 bg-white rounded-xl border border-gray-200 shadow-sm overflow-hidden">

            @csrf

            <div class="p-6">
                <div class="flex flex-col sm:flex-row gap-6 items-start">

                    {{-- Photo --}}
                    <div class="flex flex-col items-center gap-3 flex-shrink-0">
                        <div class="relative group cursor-pointer" @click="$refs.photoInput.click()">
                            {{-- Preview dari cropper --}}
                            <template x-if="preview">
                                <img :src="preview" class="w-20 h-20 rounded-full object-cover border-2 border-purple-200" alt="Preview">
                            </template>
                            {{-- Foto yang sudah tersimpan --}}
                            <template x-if="!preview && hasExistingPhoto">
                                <img src="{{ $user->profile_photo ? asset('storage/' . $user->profile_photo) : '' }}"
                                     class="w-20 h-20 rounded-full object-cover border-2 border-purple-200" alt="{{ $user->name }}">
                            </template>
                            {{-- Initials jika tidak ada foto --}}
                            <template x-if="!preview && !hasExistingPhoto">
                                <div class="w-20 h-20 rounded-full bg-purple-600 text-white flex items-center justify-center text-3xl font-bold border-2 border-purple-200">
                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                </div>
                            </template>
                            <div class="absolute inset-0 bg-black/40 rounded-full opacity-0 group-hover:opacity-100 transition-opacity duration-200 flex items-center justify-center">
                                <i class="fas fa-camera text-white text-lg"></i>
                            </div>
                        </div>
                        
                        {{-- Input file hidden --}}
                        <input type="file" x-ref="photoInput" accept="image/jpeg,image/jpg,image/png" class="hidden" @change="handleFile($event)">
                        <input type="file" x-ref="hiddenFile" name="profile_photo" class="hidden">
                        
                        {{-- Info --}}
                        <div class="text-center">
                            <p class="text-xs text-gray-500">Klik untuk ubah foto</p>
                            <p class="text-[10px] text-gray-400 mt-1">Maks 10MB, auto-kompres (JPG, PNG)</p>
                        </div>
                        
                        {{-- Error validasi --}}
                        <div x-show="errorMessage" x-text="errorMessage" class="text-xs text-red-500 text-center max-w-[150px]"></div>
                    </div>

                    {{-- Name --}}
                    <div class="flex-1 w-full space-y-4">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Nama Lengkap</label>
                            <input type="text" name="name" value="{{ old('name', $user->name) }}"
                                   class="w-full py-2 px-3 border border-gray-300 rounded-lg text-base focus:ring-2 focus:ring-purple-500 focus:border-transparent outline-none">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Email</label>
                            <p class="text-base text-gray-600 py-2">{{ $user->email }}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Sekolah</label>
                            <p class="text-base text-gray-600 py-2">{{ $user->school ?? '—' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="px-6 py-4 bg-gray-50 border-t border-gray-100 flex justify-end">
                <button type="submit" class="bg-primary hover:bg-primary/90 text-white rounded-lg px-6 py-2 font-medium text-sm transition-colors duration-150">
                    Simpan Perubahan
                </button>
            </div>
        </form>

        {{-- Stats Card --}}
        <div class="w-full lg:w-80 bg-white rounded-xl border border-gray-200 shadow-sm p-6 flex-shrink-0">
            <h3 class="text-sm font-semibold text-gray-900 mb-4">Statistik Akun</h3>
            <div class="space-y-4">
                <div class="bg-gray-50 rounded-lg p-4 border border-gray-100">
                    <p class="text-xs font-medium text-gray-500 uppercase">Total Laporan</p>
                    <p class="text-2xl font-semibold text-gray-900 mt-1">{{ $reportsCount ?? 0 }}</p>
                </div>
                <div class="bg-gray-50 rounded-lg p-4 border border-gray-100">
                    <p class="text-xs font-medium text-gray-500 uppercase">Bergabung</p>
                    <p class="text-base font-semibold text-gray-900 mt-1">{{ $user->created_at->translatedFormat('d M Y') }}</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Cropper Modal --}}
    <div x-show="cropperModalOpen" x-cloak 
         class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-gray-900/50"
         x-transition:enter="transition-opacity ease-out duration-200" 
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition-opacity ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0">
        <div class="bg-white rounded-xl shadow-xl w-full max-w-lg overflow-hidden" @click.away="cancelCrop()">
            <div class="px-5 py-4 border-b border-gray-100 flex items-center justify-between">
                <div>
                    <h3 class="text-base font-semibold text-gray-900">Potong Foto</h3>
                    <p class="text-xs text-gray-500">Seret untuk memposisikan, scroll untuk zoom</p>
                </div>
                <button @click="cancelCrop()" class="text-gray-400 hover:text-gray-600 p-1">
                    <i class="fas fa-xmark text-lg"></i>
                </button>
            </div>
            <div class="p-4">
                <div class="bg-gray-100 rounded-lg overflow-hidden" style="max-height: 400px;">
                    <img x-ref="cropImage" class="block max-w-full">
                </div>
                {{-- Preview hasil crop --}}
                <div class="mt-4 flex items-center gap-4">
                    <div class="text-xs text-gray-500">Preview:</div>
                    <div x-ref="previewContainer" class="w-16 h-16 rounded-full overflow-hidden border-2 border-gray-200">
                        <img x-ref="previewImage" class="w-full h-full object-cover">
                    </div>
                </div>
            </div>
            <div class="px-5 py-3 bg-gray-50 border-t border-gray-100 flex justify-end gap-2">
                <button @click="cancelCrop()" type="button" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors">
                    Batal
                </button>
                <button @click="applyCrop()" type="button" 
                        class="px-4 py-2 text-sm font-medium text-white bg-primary rounded-lg hover:bg-primary/90 transition-colors flex items-center gap-2"
                        :disabled="isProcessing"
                        :class="{ 'opacity-75 cursor-wait': isProcessing }">
                    <i x-show="isProcessing" class="fas fa-circle-notch fa-spin"></i>
                    <span x-text="isProcessing ? 'Memproses...' : 'Terapkan'"></span>
                </button>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.2/cropper.min.js"></script>
<script>
function profileUpload() {
    return {
        preview: null,
        cropperModalOpen: false,
        cropper: null,
        isProcessing: false,
        errorMessage: '',
        hasExistingPhoto: {{ $user->profile_photo ? 'true' : 'false' }},
        maxFileSize: 10 * 1024 * 1024, // 10MB - akan dikompres ke ~50-150KB
        allowedTypes: ['image/jpeg', 'image/jpg', 'image/png'],

        handleFile(e) {
            const file = e.target.files[0];
            if (!file) return;

            // Validasi tipe file
            if (!this.allowedTypes.includes(file.type)) {
                this.errorMessage = 'Format file harus JPG, JPEG, atau PNG';
                this.$refs.photoInput.value = '';
                return;
            }

            // Validasi ukuran file (max 10MB)
            if (file.size > this.maxFileSize) {
                this.errorMessage = 'Ukuran file maksimal 10MB';
                this.$refs.photoInput.value = '';
                return;
            }

            this.errorMessage = '';
            
            const reader = new FileReader();
            reader.onload = (ev) => {
                this.$refs.cropImage.src = ev.target.result;
                this.cropperModalOpen = true;
                this.$nextTick(() => {
                    if (this.cropper) this.cropper.destroy();
                    this.cropper = new Cropper(this.$refs.cropImage, {
                        aspectRatio: 1,
                        viewMode: 1,
                        dragMode: 'move',
                        autoCropArea: 0.9,
                        background: false,
                        responsive: true,
                        guides: true,
                        center: true,
                        highlight: true,
                        cropBoxMovable: true,
                        cropBoxResizable: true,
                        toggleDragModeOnDblclick: false,
                        preview: this.$refs.previewImage, // Live preview
                    });
                });
            };
            reader.readAsDataURL(file);
        },

        cancelCrop() {
            this.cropperModalOpen = false;
            if (this.cropper) { 
                this.cropper.destroy(); 
                this.cropper = null; 
            }
            this.$refs.photoInput.value = '';
            this.isProcessing = false;
        },

        async applyCrop() {
            if (!this.cropper) return;
            
            this.isProcessing = true;
            
            try {
                // Get cropped canvas dengan ukuran 300x300
                const canvas = this.cropper.getCroppedCanvas({
                    width: 300,
                    height: 300,
                    fillColor: '#fff',
                    imageSmoothingEnabled: true,
                    imageSmoothingQuality: 'high',
                });

                if (!canvas) {
                    throw new Error('Gagal memproses gambar');
                }

                // Convert ke blob dengan quality 0.8
                const blob = await new Promise((resolve, reject) => {
                    canvas.toBlob(
                        (blob) => {
                            if (blob) resolve(blob);
                            else reject(new Error('Gagal membuat blob'));
                        },
                        'image/jpeg',
                        0.8 // Quality 80%
                    );
                });

                // Update preview
                this.preview = URL.createObjectURL(blob);

                // Create File object untuk form submission
                const file = new File([blob], 'profile_' + Date.now() + '.jpg', { 
                    type: 'image/jpeg',
                    lastModified: Date.now()
                });

                // Set ke hidden file input
                const dt = new DataTransfer();
                dt.items.add(file);
                this.$refs.hiddenFile.files = dt.files;

                // Tutup modal
                this.cropperModalOpen = false;
                this.cropper.destroy();
                this.cropper = null;
                this.isProcessing = false;
                
            } catch (error) {
                console.error('Crop error:', error);
                this.errorMessage = 'Gagal memproses gambar. Silakan coba lagi.';
                this.isProcessing = false;
            }
        }
    };
}
</script>
@endpush
@endsection
