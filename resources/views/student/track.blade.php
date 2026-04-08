@extends('layouts.auth')

@section('content')
<div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
    <section class="bg-gradient-to-br from-purple-50 to-white p-6 sm:p-8 rounded-xl shadow-sm mb-8">
        <div class="flex flex-col md:flex-row items-start md:items-center gap-6">
            <div class="flex-1 min-w-0">
                <h1 class="text-2xl sm:text-3xl md:text-4xl font-bold text-purple-800 leading-tight mb-2">Lacak Laporan Anda</h1>
                <p class="text-sm sm:text-base text-gray-700">Pantau status laporan BK Anda secara real-time.</p>
            </div>
            <div class="hidden md:flex w-20 h-20 flex-shrink-0 rounded-2xl bg-white shadow-inner border border-purple-100 items-center justify-center text-3xl text-blue-600">
                <i class="fas fa-magnifying-glass"></i>
            </div>
        </div>
    </section>

    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-8">
        <div class="bg-white rounded-lg p-4 sm:p-5 border border-purple-100 shadow-sm">
            <div class="text-xl sm:text-2xl mb-3 text-purple-600"><i class="fas fa-search"></i></div>
            <h3 class="font-semibold text-gray-900 text-sm sm:text-base">Cek Status</h3>
            <p class="text-xs sm:text-sm text-gray-600 mt-2">Update real-time laporan Anda.</p>
        </div>
        <div class="bg-white rounded-lg p-4 sm:p-5 border border-purple-100 shadow-sm">
            <div class="text-xl sm:text-2xl mb-3 text-purple-600"><i class="fas fa-lock"></i></div>
            <h3 class="font-semibold text-gray-900 text-sm sm:text-base">Privasi Terjaga</h3>
            <p class="text-xs sm:text-sm text-gray-600 mt-2">Identitas siswa tetap aman.</p>
        </div>
        <div class="bg-white rounded-lg p-4 sm:p-5 border border-purple-100 shadow-sm">
            <div class="text-xl sm:text-2xl mb-3 text-purple-600"><i class="fas fa-comments"></i></div>
            <h3 class="font-semibold text-gray-900 text-sm sm:text-base">Chat Langsung</h3>
            <p class="text-xs sm:text-sm text-gray-600 mt-2">Komunikasi dengan guru BK.</p>
        </div>
    </div>

    <!-- Status Timeline -->
    <div class="bg-white rounded-xl p-6 sm:p-8 border border-purple-100 mb-8 shadow-sm">
        <h2 class="text-lg font-semibold text-gray-900 mb-6">
            <i class="fas fa-hourglass-half mr-2 text-purple-600"></i>Status Laporan
        </h2>

        <div class="relative">
            <!-- Timeline line -->
            <div class="absolute left-6 top-0 bottom-0 w-1 bg-purple-200"></div>

            <!-- Timeline items -->
            <div class="space-y-6">
                <!-- Dibuat -->
                <div class="relative pl-20">
                    <div class="absolute left-0 w-14 h-14 flex items-center justify-center rounded-full {{ $report->status === 'baru' || in_array($report->status, ['diproses', 'selesai']) ? 'bg-purple-600' : 'bg-gray-300' }} text-white">
                        <i class="fas fa-check text-lg"></i>
                    </div>
                    <div>
                        <p class="font-semibold text-gray-900">Laporan Diterima</p>
                        <p class="text-sm text-gray-600 mt-1">{{ $report->created_at->format('d M Y H:i') }}</p>
                    </div>
                </div>

                <!-- Diproses -->
                <div class="relative pl-20">
                    <div class="absolute left-0 w-14 h-14 flex items-center justify-center rounded-full {{ in_array($report->status, ['diproses', 'selesai']) ? 'bg-purple-600' : 'bg-gray-300' }} text-white">
                        <i class="fas fa-{{ in_array($report->status, ['diproses', 'selesai']) ? 'check' : 'clock' }} text-lg"></i>
                    </div>
                    <div>
                        <p class="font-semibold text-gray-900">Sedang Diproses</p>
                        <p class="text-sm text-gray-600 mt-1">{{ $report->status === 'diproses' ? 'Sedang ditangani oleh guru BK' : 'Menunggu untuk diproses' }}</p>
                    </div>
                </div>

                <!-- Selesai -->
                <div class="relative pl-20">
                    <div class="absolute left-0 w-14 h-14 flex items-center justify-center rounded-full {{ $report->status === 'selesai' ? 'bg-green-600' : 'bg-gray-300' }} text-white">
                        <i class="fas fa-{{ $report->status === 'selesai' ? 'check-double' : 'hourglass-end' }} text-lg"></i>
                    </div>
                    <div>
                        <p class="font-semibold text-gray-900">Selesai</p>
                        <p class="text-sm text-gray-600 mt-1">{{ $report->status === 'selesai' ? 'Laporan telah diselesaikan' : 'Menunggu penyelesaian' }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Report Details -->
    <div class="bg-white rounded-xl p-6 sm:p-8 border border-purple-100 mb-8 shadow-sm">
        <h2 class="text-lg font-semibold text-gray-900 mb-6">
            <i class="fas fa-file-alt mr-2 text-purple-600"></i>Detail Laporan
        </h2>

        <div class="space-y-4 border-t border-gray-200 pt-4">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-xs font-semibold text-gray-500 mb-1">KODE PELACAKAN</p>
                    <p class="font-mono font-bold text-lg text-purple-700">{{ $report->tracking_code }}</p>
                </div>
                <button onclick="copyCode()" class="bg-purple-100 hover:bg-purple-200 text-purple-700 p-2 rounded-lg transition" title="Copy to clipboard">
                    <i class="fas fa-copy"></i>
                </button>
            </div>

            <hr class="my-4">

            <div>
                <p class="text-xs font-semibold text-gray-500 mb-1">NAMA PELAPOR</p>
                <p class="font-medium text-gray-900 text-sm sm:text-base">{{ $report->nama_murid }}</p>
            </div>

            <div>
                <p class="text-xs font-semibold text-gray-500 mb-1">SEKOLAH • KELAS</p>
                <p class="font-medium text-gray-900 text-sm sm:text-base">{{ $report->nama_sekolah }} • {{ $report->kelas }}</p>
            </div>

            <div>
                <p class="text-xs font-semibold text-gray-500 mb-1">JENIS LAPORAN</p>
                <p class="font-medium text-gray-900 text-sm sm:text-base">{{ ucfirst($report->jenis_laporan ?? '-') }}</p>
            </div>

            <div>
                <p class="text-xs font-semibold text-gray-500 mb-2">ISI LAPORAN</p>
                <p class="text-gray-700 whitespace-pre-line text-xs sm:text-sm leading-relaxed">{{ $report->isi_laporan }}</p>
            </div>

            <div>
                <p class="text-xs font-semibold text-gray-500 mb-1">TANGGAL LAPORAN</p>
                <p class="font-medium text-gray-900 text-sm sm:text-base">{{ $report->created_at->format('d M Y H:i') }}</p>
            </div>
        </div>
    </div>

    <!-- Actions -->
    <div class="space-y-3 fade-in" style="animation-delay: 0.3s">
        <!-- Testimonial Card (if status selesai) -->
        @if($report->status === 'selesai')
            <div class="bg-gradient-to-r from-amber-50 to-orange-50 rounded-xl p-6 sm:p-8 border border-amber-200 shadow-sm">
                <h3 class="font-semibold text-gray-900 text-sm sm:text-base mb-2">Bagaimana Pengalaman Anda?</h3>
                <p class="text-xs sm:text-sm text-gray-600 mb-4">Testimoni Anda membantu guru BK lebih baik melayani siswa lain.</p>
                <button onclick="openTestimonialModal()" 
                   class="w-full bg-amber-600 hover:bg-amber-700 active:scale-95 text-white font-semibold py-3 px-4 rounded-lg transition-all duration-300 flex items-center justify-center">
                    <i class="fas fa-star mr-2"></i> <span>Berikan Testimoni</span>
                </button>
            </div>
        @endif

        <div class="bg-white rounded-xl p-6 sm:p-8 border border-purple-100 shadow-sm">
            <h3 class="font-semibold text-gray-900 text-sm sm:text-base mb-4">Butuh Bantuan?</h3>
            <a href="{{ route('chat.murid', ['tracking_code' => $report->tracking_code]) }}"
               class="flex items-center justify-center w-full bg-purple-600 hover:bg-purple-700 active:scale-95 text-white font-semibold py-3 px-4 rounded-lg transition-all duration-300 mb-2">
                <i class="fas fa-comments mr-2"></i> <span>Chat Langsung</span>
            </a>
            <a href="{{ route('chat.whatsapp', ['tracking_code' => $report->tracking_code]) }}"
               class="flex items-center justify-center w-full bg-green-600 hover:bg-green-700 active:scale-95 text-white font-semibold py-3 px-4 rounded-lg transition-all duration-300">
                <i class="fab fa-whatsapp mr-2"></i> <span>Chat via WA</span>
            </a>
        </div>

        <a href="/" aria-label="Kembali ke Beranda"
           class="flex items-center justify-center w-full bg-gray-200 hover:bg-gray-300 active:scale-95 text-gray-700 font-semibold py-3 px-4 rounded-lg transition-all duration-300">
            <i class="fas fa-arrow-left mr-2"></i> <span>Kembali ke Beranda</span>
        </a>
    </div>
</div>

<script>
function copyCode() {
    const code = '{{ $report->tracking_code }}';
    navigator.clipboard.writeText(code).then(() => {
        showToast('Kode tracking berhasil disalin!', 'success', 2000);
    });
}

// Testimonial Modal Functions
let selectedRating = 0;

function openTestimonialModal() {
    document.getElementById('testimonialModal').classList.remove('hidden');
    selectedRating = 0;
}

function closeTestimonialModal() {
    document.getElementById('testimonialModal').classList.add('hidden');
    selectedRating = 0;
    document.getElementById('testimonialContent').value = '';
    updateStars(0);
}

function setRating(rating) {
    selectedRating = rating;
    updateStars(rating);
}

function updateStars(rating) {
    for (let i = 1; i <= 5; i++) {
        const star = document.getElementById(`star-${i}`);
        if (i <= rating) {
            star.classList.remove('text-gray-300');
            star.classList.add('text-amber-400');
        } else {
            star.classList.remove('text-amber-400');
            star.classList.add('text-gray-300');
        }
    }
}

async function submitTestimonial() {
    const content = document.getElementById('testimonialContent').value.trim();
    const isAnonymous = document.getElementById('anonymousCheckbox').checked;
    
    if (!content) {
        showNotification('Silakan isi testimoni Anda', 'error');
        return;
    }
    
    if (selectedRating === 0) {
        showNotification('Silakan pilih rating bintang', 'error');
        return;
    }
    
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
    
    try {
        const response = await fetch('{{ route("testimonial.store") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
            body: JSON.stringify({
                report_id: {{ $report->id }},
                content: content,
                rating: selectedRating,
                is_anonymous: isAnonymous
            })
        });
        
        if (response.ok) {
            showNotification('Terima kasih! Testimoni Anda akan ditinjau oleh guru BK sebelum ditampilkan.', 'success');
            closeTestimonialModal();
        } else {
            showNotification('Gagal mengirim testimoni. Silakan coba lagi.', 'error');
        }
    } catch (error) {
        showNotification('Terjadi kesalahan. Silakan coba lagi.', 'error');
    }
}

// Close modal on background click
document.addEventListener('click', function(event) {
    const modal = document.getElementById('testimonialModal');
    if (event.target === modal) {
        closeTestimonialModal();
    }
});
</script>

<!-- Testimonial Modal -->
<div id="testimonialModal" class="hidden fixed inset-0 bg-black/50 z-50 p-4" style="display: none;">
    <div class="flex items-center justify-center h-full">
        <div class="bg-white rounded-xl shadow-lg max-w-md w-full">
            <div class="p-6 border-b border-gray-200 flex items-center justify-between">
                <h2 class="text-lg font-bold text-gray-900">Berikan Testimoni</h2>
                <button onclick="closeTestimonialModal()" class="text-gray-500 hover:text-gray-700 text-xl">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        
        <div class="p-6 space-y-4">
            <!-- Rating Stars -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-3">Rating Anda</label>
                <div class="flex gap-2 text-2xl">
                    <button onclick="setRating(1)" id="star-1" class="text-gray-300 hover:text-amber-400 transition">
                        <i class="fas fa-star"></i>
                    </button>
                    <button onclick="setRating(2)" id="star-2" class="text-gray-300 hover:text-amber-400 transition">
                        <i class="fas fa-star"></i>
                    </button>
                    <button onclick="setRating(3)" id="star-3" class="text-gray-300 hover:text-amber-400 transition">
                        <i class="fas fa-star"></i>
                    </button>
                    <button onclick="setRating(4)" id="star-4" class="text-gray-300 hover:text-amber-400 transition">
                        <i class="fas fa-star"></i>
                    </button>
                    <button onclick="setRating(5)" id="star-5" class="text-gray-300 hover:text-amber-400 transition">
                        <i class="fas fa-star"></i>
                    </button>
                </div>
            </div>
            
            <!-- Testimonial Content -->
            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Testimoni Anda</label>
                <textarea id="testimonialContent" 
                    class="w-full border border-gray-300 rounded-lg p-3 text-sm focus:ring-2 focus:ring-amber-500 focus:border-transparent resize-none" 
                    rows="4" 
                    placeholder="Bagikan pengalaman Anda dengan guru BK..."></textarea>
                <p class="text-xs text-gray-500 mt-1">Minimal 10 karakter</p>
            </div>
            
            <!-- Anonymous Checkbox -->
            <div class="flex items-center gap-2">
                <input type="checkbox" id="anonymousCheckbox" class="rounded">
                <label for="anonymousCheckbox" class="text-sm text-gray-700">Kirim testimoni secara anonim</label>
            </div>
        </div>
        
        <div class="p-6 border-t border-gray-200 flex gap-3">
            <button onclick="closeTestimonialModal()" 
                class="flex-1 bg-gray-200 hover:bg-gray-300 text-gray-800 font-semibold py-2 rounded-lg transition">
                Batal
            </button>
            <button onclick="submitTestimonial()" 
                class="flex-1 bg-amber-600 hover:bg-amber-700 text-white font-semibold py-2 rounded-lg transition">
                Kirim
            </button>
        </div>
    </div>
</div>

@endsection
