@extends('layouts.auth')

@section('content')
<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="mb-6">
        <a href="/" class="inline-flex items-center text-purple-600 hover:text-purple-800 font-medium transition" aria-label="Kembali">
            <i class="fas fa-arrow-left"></i>
        </a>
    </div>

    <section class="bg-gradient-to-br from-purple-50 to-white p-6 sm:p-8 rounded-xl shadow-sm mb-8">
        <div class="flex flex-col md:flex-row items-start md:items-center gap-6">
            <div class="flex-1 min-w-0">
                <h1 class="text-2xl sm:text-3xl md:text-4xl font-bold text-purple-800 leading-tight mb-2">Buat Laporan BK</h1>
                <p class="text-sm sm:text-base text-gray-700">Isi form dengan detail agar laporan cepat ditangani oleh guru BK.</p>
            </div>
            <div class="hidden md:flex w-20 h-20 flex-shrink-0 rounded-2xl bg-white shadow-inner border border-purple-100 items-center justify-center text-3xl text-purple-600">
                <i class="fas fa-file-circle-plus"></i>
            </div>
        </div>
    </section>

    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-8">
        <div class="bg-white rounded-lg p-4 sm:p-5 border border-purple-100 shadow-sm">
            <div class="text-xl sm:text-2xl mb-3 text-purple-600"><i class="fas fa-user-shield"></i></div>
            <h3 class="font-semibold text-gray-900 text-sm sm:text-base">Anonim & Aman</h3>
            <p class="text-xs sm:text-sm text-gray-600 mt-2">Identitas siswa tetap terlindungi.</p>
        </div>
        <div class="bg-white rounded-lg p-4 sm:p-5 border border-purple-100 shadow-sm">
            <div class="text-xl sm:text-2xl mb-3 text-purple-600"><i class="fas fa-bolt"></i></div>
            <h3 class="font-semibold text-gray-900 text-sm sm:text-base">Proses Cepat</h3>
            <p class="text-xs sm:text-sm text-gray-600 mt-2">Laporan langsung diteruskan.</p>
        </div>
        <div class="bg-white rounded-lg p-4 sm:p-5 border border-purple-100 shadow-sm">
            <div class="text-xl sm:text-2xl mb-3 text-purple-600"><i class="fas fa-clipboard-check"></i></div>
            <h3 class="font-semibold text-gray-900 text-sm sm:text-base">Kode Pelacakan</h3>
            <p class="text-xs sm:text-sm text-gray-600 mt-2">Pantau status melalui kode unik.</p>
        </div>
    </div>

    <div class="bg-white rounded-xl shadow-sm p-6 sm:p-8 border border-purple-100">

        <!-- Form -->
        <form id="reportForm">
            @csrf
            <div class="space-y-5">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-school mr-2 text-purple-600"></i>Sekolah *
                </label>
                <div class="relative">
                    <select name="school_id" required
                            class="w-full px-4 py-3 rounded-xl border border-purple-200 bg-gradient-to-r from-white to-purple-50 focus:ring-2 focus:ring-purple-300 focus:border-transparent outline-none transition appearance-none">
                        <option value="">Pilih sekolah</option>
                        @foreach($schools as $school)
                            <option value="{{ $school->id }}">{{ $school->name }}@if($school->city) - {{ $school->city }}@endif</option>
                        @endforeach
                    </select>
                    <div class="pointer-events-none absolute inset-y-0 right-4 flex items-center text-purple-500">
                        <i class="fas fa-chevron-down"></i>
                    </div>
                </div>
                <p class="text-xs text-gray-500 mt-2">Pilih sekolah yang sudah terdaftar oleh admin.</p>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-chalkboard mr-2 text-purple-600"></i>Kelas *
                </label>
                <input type="text" name="kelas" required
                       class="w-full px-4 py-3 rounded-lg border border-purple-200 focus:ring-2 focus:ring-purple-300 focus:border-transparent outline-none transition"
                       placeholder="Contoh: IX-A">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-user mr-2 text-purple-600"></i>Nama Murid *
                </label>
                <input type="text" name="nama_murid" required
                       class="w-full px-4 py-3 rounded-lg border border-purple-200 focus:ring-2 focus:ring-purple-300 focus:border-transparent outline-none transition"
                       placeholder="Nama lengkap murid">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-heading mr-2 text-purple-600"></i>Judul Laporan *
                </label>
                <input type="text" name="title" required
                       class="w-full px-4 py-3 rounded-lg border border-purple-200 focus:ring-2 focus:ring-purple-300 focus:border-transparent outline-none transition"
                       placeholder="Ringkas masalah Anda">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-3">
                    <i class="fas fa-layer-group mr-2 text-purple-600"></i>Pilih Jenis Masalah *
                </label>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    @php
                        $jenisOptions = [
                            ['value' => 'akademik', 'label' => 'Akademik', 'icon' => 'fas fa-book-open', 'desc' => 'Tugas, nilai, fokus belajar.'],
                            ['value' => 'bullying', 'label' => 'Bullying', 'icon' => 'fas fa-shield-halved', 'desc' => 'Perlakuan kasar atau menghina.'],
                            ['value' => 'perundungan', 'label' => 'Perundungan', 'icon' => 'fas fa-hand-fist', 'desc' => 'Kekerasan fisik/mental.'],
                            ['value' => 'kedisiplinan', 'label' => 'Kedisiplinan', 'icon' => 'fas fa-user-check', 'desc' => 'Terlambat, aturan sekolah.'],
                            ['value' => 'keluarga', 'label' => 'Masalah Keluarga', 'icon' => 'fas fa-house', 'desc' => 'Kondisi di rumah atau keluarga.'],
                            ['value' => 'pertemanan', 'label' => 'Pertemanan', 'icon' => 'fas fa-user-friends', 'desc' => 'Konflik dengan teman.'],
                            ['value' => 'kesehatan', 'label' => 'Kesehatan Mental', 'icon' => 'fas fa-heart-pulse', 'desc' => 'Stres, cemas, tekanan batin.'],
                            ['value' => 'lainnya', 'label' => 'Lainnya', 'icon' => 'fas fa-ellipsis', 'desc' => 'Masalah lainnya.'],
                        ];
                    @endphp

                    @foreach($jenisOptions as $option)
                        <label class="jenis-card group border border-purple-100 rounded-xl p-4 cursor-pointer bg-white hover:border-purple-300 hover:shadow-sm transition relative">
                            <input type="checkbox" name="jenis_laporan" value="{{ $option['value'] }}" class="jenis-input hidden"
                                   {{ old('jenis_laporan') === $option['value'] ? 'checked' : '' }}>
                            <span class="absolute top-3 right-3 w-6 h-6 rounded-full bg-purple-600 text-white text-xs flex items-center justify-center opacity-0 transition jenis-check">
                                <i class="fas fa-check"></i>
                            </span>
                            <div class="flex items-start gap-3">
                                <div class="w-10 h-10 rounded-lg bg-purple-100 text-purple-600 flex items-center justify-center text-lg group-hover:bg-purple-200">
                                    <i class="{{ $option['icon'] }}"></i>
                                </div>
                                <div>
                                    <p class="font-semibold text-gray-900">{{ $option['label'] }}</p>
                                    <p class="text-xs text-gray-500 mt-1">{{ $option['desc'] }}</p>
                                </div>
                            </div>
                        </label>
                    @endforeach
                </div>
                <p class="text-xs text-gray-500 mt-2">Pilih salah satu jenis masalah untuk membantu tim BK.</p>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-align-left mr-2 text-purple-600"></i>Isi Laporan *
                </label>
                <textarea name="isi_laporan" rows="5" required
                          class="w-full px-4 py-3 rounded-lg border border-purple-200 focus:ring-2 focus:ring-purple-300 focus:border-transparent outline-none transition"
                          placeholder="Ceritakan masalah Anda secara detail..."></textarea>
            </div>

            <!-- Kode Akses Field -->
            <div class="bg-gradient-to-r from-amber-50 to-orange-50 rounded-xl p-5 border border-orange-200">
                <div class="flex items-start gap-3 mb-4">
                    <div class="w-8 h-8 rounded-lg bg-orange-200 text-orange-700 flex items-center justify-center flex-shrink-0 text-lg">
                        <i class="fas fa-key"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                        <label class="block text-sm font-semibold text-gray-800 mb-1">
                            Kode Akses *
                        </label>
                        <p class="text-xs text-gray-600 mb-3">
                            Guru Anda akan memberikan kode 4 digit untuk memverifikasi Anda adalah murid sekolah yang sebenarnya.
                        </p>
                        <input type="text" name="secret_code" required
                               maxlength="4"
                               inputmode="numeric"
                               class="w-full px-4 py-3 rounded-lg border border-orange-300 bg-white focus:ring-2 focus:ring-orange-400 focus:border-transparent outline-none transition font-mono text-lg tracking-widest"
                               placeholder="0000"
                               autocomplete="off">
                        <p class="text-xs text-gray-500 mt-2">
                            <i class="fas fa-shield-halved mr-1"></i>Kode ini akan diverifikasi otomatis sebelum laporan dikirim.
                        </p>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 gap-4 pt-2 border-t border-purple-100">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fas fa-envelope mr-2 text-purple-600"></i>Email (opsional)
                    </label>
                    <input type="email" name="email_murid"
                           class="w-full px-4 py-3 rounded-lg border border-purple-200 focus:ring-2 focus:ring-purple-300 focus:border-transparent outline-none transition"
                           placeholder="untuk notifikasi update">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        <i class="fab fa-whatsapp mr-2 text-green-600"></i>WhatsApp (opsional)
                    </label>
                    <input type="tel" name="phone"
                           class="w-full px-4 py-3 rounded-lg border border-purple-200 focus:ring-2 focus:ring-purple-300 focus:border-transparent outline-none transition"
                           placeholder="contoh: 621234567890">
                </div>
            </div>

            <button type="submit"
                    class="w-full bg-purple-600 hover:bg-purple-700 active:scale-95 text-white font-bold py-3 px-4 rounded-lg transition-all duration-300 flex items-center justify-center"
                    id="submitBtn">
                <i class="fas fa-paper-plane mr-2"></i> <span id="submitText">Kirim Laporan</span>
            </button>
        </div>
    </form>
    
    <!-- Loading Modal untuk form submission -->
    <div id="loadingModal" class="hidden fixed inset-0 bg-white z-50" style="display: none;">
        <div class="flex items-center justify-center h-full">
            <x-loading message="Memproses laporan Anda..." />
        </div>
    </div>
    </div>
</div>

<!-- MODAL POPUP -->
<div id="codeModal" class="hidden fixed inset-0 bg-white z-50" style="display: none;">
    <div class="flex items-center justify-center h-full">
        <div class="bg-white rounded-2xl p-6 w-11/12 max-w-md mx-4 animate-fade-in">
            <div class="text-center">
            <div class="w-14 h-14 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-check text-green-600 text-xl"></i>
            </div>
            <h2 class="text-xl font-bold text-purple-800 mb-2">Laporan Terkirim!</h2>
            <p class="text-purple-600 text-sm mb-4">Simpan kode ini untuk melacak laporan Anda</p>
            
            <div class="bg-purple-50 rounded-lg p-4 mb-4">
                <div class="font-mono text-lg font-bold text-purple-700" id="modalCode">XXXXXX</div>
            </div>
            
            <div class="space-y-3">
                <x-button type="button" variant="outline" class="w-full justify-center" onclick="copyCode()">
                    <i class="fas fa-copy mr-2"></i> Salin Kode
                </x-button>

                <x-button type="button" variant="primary" class="w-full justify-center" onclick="goHome()">
                    <i class="fas fa-home mr-2"></i> Kembali ke Beranda
                </x-button>
            </div>
        </div>
    </div>
</div>

<style>
@keyframes fadeIn {
    from { opacity: 0; transform: scale(0.9); }
    to { opacity: 1; transform: scale(1); }
}
.animate-fade-in {
    animation: fadeIn 0.3s ease-out;
}
</style>

<script>
// Kirim laporan via AJAX
document.getElementById('reportForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    // Manual validation for jenis_laporan
    const selected = document.querySelector('.jenis-input:checked');
    if (!selected) {
        Swal.fire({
            icon: 'warning',
            title: 'Pilih Jenis Masalah!',
            text: 'Sebelum mengirim, silakan pilih jenis masalah terlebih dahulu.',
            confirmButtonColor: '#9333ea'
        });
        return;
    }

    const formData = new FormData(this);
    const submitBtn = document.getElementById('submitBtn');
    const submitText = document.getElementById('submitText');
    const loadingModal = document.getElementById('loadingModal');
    
    // Show loading
    loadingModal.classList.remove('hidden');
    submitBtn.disabled = true;
    submitText.textContent = 'Memproses...';
    
    try {
        const response = await fetch('/report', {
            method: 'POST',
            body: formData,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json'
            }
        });
        
        const result = await response.json();

        // Handle 429 - Too Many Requests
        if (response.status === 429) {
            loadingModal.classList.add('hidden');
            submitBtn.disabled = true;
            submitText.textContent = 'Tunggu 1 menit...';
            
            Swal.fire({
                icon: 'error',
                title: 'Terlalu Banyak Permintaan!',
                html: `
                    <p class="mb-3">Anda sudah mengirim laporan berkali-kali dalam waktu singkat.</p>
                    <p class="text-sm text-gray-600">Silakan tunggu <strong>1 menit</strong> sebelum mengirim laporan lagi.</p>
                `,
                confirmButtonColor: '#9333ea',
                confirmButtonText: 'Baik, Saya Mengerti'
            });
            
            // Lock button untuk 60 detik
            let countdown = 60;
            const interval = setInterval(() => {
                countdown--;
                submitText.textContent = `Tunggu ${countdown}s...`;
                
                if (countdown <= 0) {
                    clearInterval(interval);
                    submitBtn.disabled = false;
                    submitText.textContent = 'Kirim Laporan';
                }
            }, 1000);
            
            return;
        }

        if (!response.ok) {
            // Handle other errors
            if (result.error) {
                Swal.fire({
                    icon: 'error',
                    title: 'Kesalahan!',
                    text: result.error,
                    confirmButtonColor: '#9333ea'
                });
            } else if (result.errors) {
                let errorMsg = '';
                for (let field in result.errors) {
                    errorMsg += result.errors[field][0] + '\n';
                }
                Swal.fire({
                    icon: 'error',
                    title: 'Validasi Gagal!',
                    text: errorMsg,
                    confirmButtonColor: '#9333ea'
                });
                console.error('Validation errors:', result.errors);
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Kesalahan Server!',
                    text: 'Terjadi kesalahan. Silakan coba lagi dalam beberapa saat.',
                    confirmButtonColor: '#9333ea'
                });
            }
            return;
        }

        if (result.tracking_code) {
            // Tampilkan modal
            document.getElementById('modalCode').innerText = result.tracking_code;
            document.getElementById('codeModal').classList.remove('hidden');
            
            Swal.fire({
                icon: 'success',
                title: 'Laporan Terkirim!',
                text: 'Kode tracking telah dikirim ke email Anda.',
                confirmButtonColor: '#9333ea'
            });
        } else {
            Swal.fire({
                icon: 'warning',
                title: 'Peringatan!',
                text: 'Laporan dibuat tapi kode tracking tidak diterima.',
                confirmButtonColor: '#9333ea'
            });
        }
    } catch (error) {
        console.error('Fetch error:', error);
        
        Swal.fire({
            icon: 'error',
            title: 'Koneksi Gagal!',
            text: 'Gagal mengirim laporan. Periksa koneksi internet Anda.',
            confirmButtonColor: '#9333ea'
        });
        
        // Izinkan retry setelah 3 detik
        let retryCountdown = 3;
        submitText.textContent = `Coba Lagi (${retryCountdown}s)`;
        const retryInterval = setInterval(() => {
            retryCountdown--;
            if (retryCountdown > 0) {
                submitText.textContent = `Coba Lagi (${retryCountdown}s)`;
            } else {
                clearInterval(retryInterval);
                submitBtn.disabled = false;
                submitText.textContent = 'Kirim Laporan';
            }
        }, 1000);
    } finally {
        // Hide loading
        loadingModal.classList.add('hidden');
    }
});

function copyCode() {
    const code = document.getElementById('modalCode').innerText;
    navigator.clipboard.writeText(code).then(() => {
        const btn = event.target;
        const originalHTML = btn.innerHTML;
        btn.innerHTML = '<i class="fas fa-check mr-2"></i> Disalin!';
        
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: 'Kode tracking berhasil disalin ke clipboard.',
            timer: 2000,
            confirmButtonColor: '#9333ea'
        });
        
        setTimeout(() => {
            btn.innerHTML = originalHTML;
        }, 2000);
    });
}

function goHome() {
    window.location.href = '/';
}

</script>

<script>
    const jenisCards = document.querySelectorAll('.jenis-card');
    jenisCards.forEach(card => {
        const input = card.querySelector('.jenis-input');
        const check = card.querySelector('.jenis-check');
        const updateState = () => {
            if (input.checked) {
                card.classList.add('ring-2', 'ring-purple-400', 'bg-purple-50', 'border-purple-300');
                check.classList.remove('opacity-0');
            } else {
                card.classList.remove('ring-2', 'ring-purple-400', 'bg-purple-50', 'border-purple-300');
                check.classList.add('opacity-0');
            }
        };
        updateState();

        card.addEventListener('click', (e) => {
            if (e.target.tagName === 'A' || e.target.closest('a')) return;
            e.preventDefault();
            const wasChecked = input.checked;
            document.querySelectorAll('.jenis-input').forEach(el => el.checked = false);
            input.checked = !wasChecked;
            jenisCards.forEach(item => {
                const itemInput = item.querySelector('.jenis-input');
                const itemCheck = item.querySelector('.jenis-check');
                if (itemInput.checked) {
                    item.classList.add('ring-2', 'ring-purple-400', 'bg-purple-50', 'border-purple-300');
                    itemCheck.classList.remove('opacity-0');
                } else {
                    item.classList.remove('ring-2', 'ring-purple-400', 'bg-purple-50', 'border-purple-300');
                    itemCheck.classList.add('opacity-0');
                }
            });
        });
    });
</script>

<!-- CSRF Token untuk AJAX -->
<meta name="csrf-token" content="{{ csrf_token() }}">
    </div>
</div>
@endsection