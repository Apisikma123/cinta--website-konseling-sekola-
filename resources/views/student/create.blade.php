@extends('layouts.auth')
@section('title', 'Buat Laporan BK - CINTA')
@section('page_heading', 'Formulir Pengaduan BK')

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
        <form id="reportForm" data-no-loading>
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
                            ['value' => 'perundungan(bullying)', 'label' => 'Perundungan(bullying)', 'icon' => 'fas fa-hand-fist', 'desc' => 'Kekerasan fisik/mental.'],
                            ['value' => 'kedisiplinan', 'label' => 'Kedisiplinan', 'icon' => 'fas fa-user-check', 'desc' => 'Terlambat, aturan sekolah.'],
                            ['value' => 'keluarga', 'label' => 'Keluarga', 'icon' => 'fas fa-house', 'desc' => 'Kondisi di rumah.'],
                            ['value' => 'pertemanan', 'label' => 'Pertemanan', 'icon' => 'fas fa-user-friends', 'desc' => 'Konflik dengan teman.'],
                            ['value' => 'kesehatan', 'label' => 'Kesehatan Mental', 'icon' => 'fas fa-heart-pulse', 'desc' => 'Stres, cemas, tekanan.'],
                            ['value' => 'lainnya', 'label' => 'Lainnya', 'icon' => 'fas fa-ellipsis', 'desc' => 'Masalah lainnya.'],
                        ];
                    @endphp

                    @foreach($jenisOptions as $option)
                        <label class="jenis-card group border border-purple-100 rounded-xl p-4 cursor-pointer bg-white hover:border-purple-300 hover:shadow-sm transition relative overflow-hidden">
                            <input type="checkbox" name="jenis_laporan" value="{{ $option['value'] }}" class="jenis-input hidden"
                                   {{ old('jenis_laporan') === $option['value'] ? 'checked' : '' }}>
                            <span class="absolute top-3 right-3 w-6 h-6 rounded-full bg-purple-600 text-white text-xs flex items-center justify-center opacity-0 transition jenis-check">
                                <i class="fas fa-check"></i>
                            </span>
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-lg bg-purple-100 text-purple-600 flex items-center justify-center text-lg group-hover:bg-purple-200 flex-shrink-0">
                                    <i class="{{ $option['icon'] }}"></i>
                                </div>
                                <div class="min-w-0">
                                    <p class="font-semibold text-gray-900 text-sm">{{ $option['label'] }}</p>
                                    <p class="text-xs text-gray-500 mt-0.5">{{ $option['desc'] }}</p>
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
                        <i class="fas fa-envelope mr-2 text-purple-600"></i>Email *
                    </label>
                    <input type="email" name="email_murid" required
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
    </div>
</div>

@push('modals')

<!-- MODAL POPUP: Laporan tanpa email -->
<div id="codeModal" class="fixed inset-0 z-[9999] hidden items-center justify-center bg-black/60 backdrop-blur-lg p-4 overflow-y-auto">
    <div class="bg-white rounded-2xl p-6 w-full max-w-md shadow-2xl animate-fade-in relative mx-auto my-auto">
        <div class="text-center">
            <div class="w-14 h-14 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-check text-green-600 text-xl"></i>
            </div>
            <h2 class="text-xl font-bold text-purple-800 mb-2">Laporan Terkirim!</h2>
            <p class="text-purple-600 text-sm mb-4">Simpan kode ini untuk melacak laporan Anda</p>

            <div class="bg-purple-50 rounded-lg p-4 mb-6">
                <div class="font-mono text-2xl font-bold text-purple-700 tracking-widest" id="modalCode">XXXXXX</div>
            </div>

            <div class="space-y-3">
                <button type="button" onclick="copyCode()"
                    class="w-full border-2 border-purple-300 text-purple-700 font-semibold py-3 rounded-xl hover:bg-purple-50 transition flex items-center justify-center gap-2">
                    <i class="fas fa-copy"></i> Salin Kode
                </button>
                <button type="button" onclick="goHome()"
                    class="w-full bg-purple-600 hover:bg-purple-700 text-white font-semibold py-3 rounded-xl transition flex items-center justify-center gap-2">
                    <i class="fas fa-home"></i> Kembali ke Beranda
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Email Sent Modal -->
<div id="emailSentModal" class="fixed inset-0 z-[9999] hidden items-center justify-center bg-black/60 backdrop-blur-lg p-4 overflow-y-auto">
    <div class="bg-white rounded-2xl p-8 w-full max-w-lg shadow-2xl animate-fade-in relative mx-auto my-auto">
        <div class="text-center">
            <!-- Animated envelope icon -->
            <div class="w-20 h-20 bg-gradient-to-br from-purple-100 to-purple-200 rounded-full flex items-center justify-center mx-auto mb-5 shadow-inner">
                <i class="fas fa-envelope-open-text text-purple-600 text-3xl"></i>
            </div>
            <h2 class="text-2xl font-bold text-purple-800 mb-2">Cek Email Kamu! 📬</h2>
            <p class="text-gray-500 text-sm leading-relaxed mb-5">
                Laporan berhasil dikirim! Kami sudah mengirimkan tautan magic link ke email kamu.
                Klik tautan tersebut untuk melihat kode unik laporan.
            </p>

            <!-- Email hint box -->
            <div class="bg-purple-50 border border-purple-200 rounded-xl p-4 mb-5 flex items-center gap-3">
                <div class="w-9 h-9 bg-purple-100 rounded-lg flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-envelope text-purple-500"></i>
                </div>
                <div class="text-left">
                    <p class="text-xs text-gray-400 mb-0.5">Dikirim ke</p>
                    <p class="text-purple-700 font-semibold text-sm" id="sentEmailHint">email kamu</p>
                </div>
            </div>

            <!-- Info note -->
            <div class="bg-amber-50 border border-amber-200 rounded-xl p-3 mb-5 flex items-start gap-2 text-left">
                <i class="fas fa-triangle-exclamation text-amber-500 mt-0.5 text-sm"></i>
                <p class="text-xs text-amber-700">Jika tidak ada di kotak masuk, cek folder <strong>spam/sampah</strong>. Tautan berlaku selama <strong>24 jam</strong>.</p>
            </div>

            <!-- Resend button with countdown -->
            <button id="resendBtn"
                    disabled
                    class="w-full bg-purple-600 hover:bg-purple-700 active:scale-95 disabled:bg-gray-200 disabled:text-gray-400 disabled:cursor-not-allowed text-white font-semibold py-3 rounded-xl transition-all duration-200 mb-3 flex items-center justify-center gap-2">
                <i class="fas fa-paper-plane"></i>
                <span id="resendBtnText">Kirim Ulang (2:00)</span>
            </button>

            <button onclick="window.location.href='/'"
                    class="w-full border-2 border-purple-200 text-purple-700 font-semibold py-3 rounded-xl hover:bg-purple-50 transition flex items-center justify-center gap-2">
                <i class="fas fa-home"></i> Kembali ke Beranda
            </button>
        </div>
    </div>
</div>
@endpush

<style>
@keyframes fadeIn {
    from { opacity: 0; transform: scale(0.95) translateY(10px); }
    to { opacity: 1; transform: scale(1) translateY(0); }
}
.animate-fade-in {
    animation: fadeIn 0.35s cubic-bezier(0.34, 1.56, 0.64, 1);
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
    
    // Show loading
    if (window.PageLoading) window.PageLoading.show('Mengirim laporan Anda...', true);
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
            if (window.PageLoading) window.PageLoading.hide();
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
            if (window.PageLoading) window.PageLoading.hide();
            return;
        }

        if (result.tracking_code) {
            if (result.email_sent) {
                // Email was provided: show professional "check email" overlay
                _currentTrackingCode = result.tracking_code;
                document.getElementById('sentEmailHint').textContent = result.email || 'email kamu';
                openModal(document.getElementById('emailSentModal'));
                // Start 2-min frontend countdown
                _startResendCooldown(120);
            } else {
                // No email: show tracking code modal directly
                document.getElementById('modalCode').innerText = result.tracking_code;
                openModal(document.getElementById('codeModal'));
            }
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
        if (window.PageLoading) window.PageLoading.hide();
        submitBtn.disabled = false;
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

// Buka modal & kunci scroll background
function openModal(el) {
    el.classList.remove('hidden');
    el.classList.add('flex');
    document.body.style.overflow = 'hidden';
    document.body.style.touchAction = 'none';
    // Scroll modal ke atas supaya isi kelihatan dari awal
    el.scrollTop = 0;
}

// Tutup modal & unlock scroll
function closeModal(el) {
    el.classList.add('hidden');
    el.classList.remove('flex');
    document.body.style.overflow = '';
    document.body.style.touchAction = '';
}

function goHome() {
    document.body.style.overflow = '';
    document.body.style.touchAction = '';
    window.location.href = '/';
}

// ===================== RESEND MAGIC LINK (Fitur Baru 1) =====================
let _currentTrackingCode = null;
let _resendInterval      = null;

function _startResendCooldown(seconds) {
    const btn = document.getElementById('resendBtn');
    btn.disabled = true;
    let remaining = seconds;
    _updateResendLabel(remaining);

    if (_resendInterval) clearInterval(_resendInterval);
    _resendInterval = setInterval(() => {
        remaining--;
        _updateResendLabel(remaining);
        if (remaining <= 0) {
            clearInterval(_resendInterval);
            btn.disabled = false;
            document.getElementById('resendBtnText').textContent = 'Kirim Ulang';
        }
    }, 1000);
}

function _updateResendLabel(seconds) {
    const m = Math.floor(seconds / 60);
    const s = seconds % 60;
    document.getElementById('resendBtnText').textContent =
        `Kirim Ulang (${m}:${String(s).padStart(2, '0')})`;
}

document.getElementById('resendBtn').addEventListener('click', async function () {
    if (!_currentTrackingCode) return;
    this.disabled = true;
    document.getElementById('resendBtnText').textContent = 'Mengirim...';

    try {
        const res = await fetch(`/report/${_currentTrackingCode}/resend`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept'      : 'application/json',
                'Content-Type': 'application/json',
            },
        });
        const data = await res.json();

        if (res.ok) {
            Swal.fire({
                icon            : 'success',
                title           : 'Email Terkirim!',
                text            : data.message,
                confirmButtonColor: '#9333ea',
                timer           : 4000,
                timerProgressBar: true,
            });
            _startResendCooldown(data.retry_after ?? 120);
        } else if (res.status === 429) {
            Swal.fire({
                icon   : 'warning',
                title  : 'Mohon Tunggu!',
                text   : data.error || 'Mohon tunggu sebelum mengirim ulang kode.',
                confirmButtonColor: '#9333ea',
            });
            _startResendCooldown(data.retry_after ?? 120);
        } else {
            document.getElementById('resendBtnText').textContent = 'Kirim Ulang';
            this.disabled = false;
            Swal.fire({
                icon : 'error',
                title: 'Gagal!',
                text : data.error || 'Gagal mengirim ulang. Coba lagi.',
                confirmButtonColor: '#9333ea',
            });
        }
    } catch (err) {
        document.getElementById('resendBtnText').textContent = 'Kirim Ulang';
        this.disabled = false;
    }
});

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