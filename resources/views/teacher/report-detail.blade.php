@extends('layouts.teacher', ['title' => 'Detail Laporan'])

@section('content')
<div class="w-full px-4 sm:px-6 lg:px-8 space-y-5">

    {{-- Back + Title + Status --}}
    <div class="flex flex-col sm:flex-row sm:items-start gap-3">
        <div class="flex items-start gap-3 flex-1 min-w-0">
            <a href="{{ route('teacher.reports.index') }}"
               class="w-9 h-9 border border-gray-200 rounded-lg bg-white flex items-center justify-center text-gray-500 flex-shrink-0 mt-0.5 hover:border-purple-300 hover:text-purple-600 transition-colors duration-150">
                <i class="fas fa-arrow-left text-sm"></i>
            </a>
            <div class="min-w-0">
                <div class="flex items-center gap-2 flex-wrap mb-1">
                    <h2 class="text-lg font-bold text-gray-900">{{ $report->title }}</h2>
                    <code class="text-xs bg-purple-50 text-purple-700 px-2 py-0.5 rounded border border-purple-200 font-mono whitespace-nowrap">
                        #{{ $report->tracking_code }}
                    </code>
                </div>
                <p class="text-sm text-gray-500">
                    <i class="fas fa-clock text-xs mr-1"></i>
                    Diterima {{ $report->created_at->translatedFormat('d F Y, H:i') }}
                </p>
            </div>
        </div>

        {{-- Status Selector --}}
        <form action="{{ route('report.update-status', $report->id) }}" method="POST"
              class="flex items-center gap-2 bg-white border border-gray-200 rounded-lg px-3 py-2 flex-shrink-0">
            @csrf
            @method('PATCH')
            @php
                $dotColor = match($report->status) {
                    'selesai'  => 'bg-emerald-400',
                    'diproses' => 'bg-purple-500',
                    default    => 'bg-amber-400',
                };
            @endphp
            <span class="w-2 h-2 rounded-full {{ $dotColor }} flex-shrink-0"></span>
            <select name="status" onchange="this.form.submit()"
                    class="bg-transparent border-none text-sm font-semibold text-gray-700 cursor-pointer focus:ring-0 p-0 pr-6 appearance-none">
                <option value="baru"     {{ $report->status === 'baru'     ? 'selected' : '' }}>Baru</option>
                <option value="diproses" {{ $report->status === 'diproses' ? 'selected' : '' }}>Diproses</option>
                <option value="selesai"  {{ $report->status === 'selesai'  ? 'selected' : '' }}>Selesai</option>
            </select>
            <i class="fas fa-chevron-down text-[9px] text-gray-400 pointer-events-none -ml-4"></i>
        </form>
    </div>

    {{-- Card Detail Laporan - BESAR --}}
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="px-5 py-4 border-b border-gray-100 flex items-center gap-3 bg-gradient-to-r from-purple-50 to-white">
            <div class="w-10 h-10 bg-purple-100 rounded-xl flex items-center justify-center">
                <i class="fas fa-file-lines text-purple-600"></i>
            </div>
            <div>
                <h3 class="font-bold text-gray-900">Detail Laporan</h3>
                <p class="text-xs text-gray-500">Informasi lengkap laporan siswa</p>
            </div>
        </div>

        <div class="p-5">
            {{-- Grid Info --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-5 mb-6">
                {{-- Pelapor --}}
                <div class="bg-gray-50 rounded-xl p-4">
                    <div class="flex items-center gap-3 mb-2">
                        <div class="w-8 h-8 bg-purple-200 rounded-lg flex items-center justify-center">
                            <i class="fas fa-user text-purple-600 text-sm"></i>
                        </div>
                        <span class="text-xs font-semibold text-gray-500 uppercase">Pelapor</span>
                    </div>
                    <p class="font-bold text-gray-900">{{ $report->nama_murid }}</p>
                    <p class="text-sm text-gray-500">{{ $report->kelas }}</p>
                </div>

                {{-- Sekolah --}}
                <div class="bg-gray-50 rounded-xl p-4">
                    <div class="flex items-center gap-3 mb-2">
                        <div class="w-8 h-8 bg-blue-200 rounded-lg flex items-center justify-center">
                            <i class="fas fa-school text-blue-600 text-sm"></i>
                        </div>
                        <span class="text-xs font-semibold text-gray-500 uppercase">Sekolah</span>
                    </div>
                    <p class="font-bold text-gray-900">{{ $report->nama_sekolah }}</p>
                </div>

                {{-- Kategori --}}
                <div class="bg-gray-50 rounded-xl p-4">
                    <div class="flex items-center gap-3 mb-2">
                        <div class="w-8 h-8 bg-amber-200 rounded-lg flex items-center justify-center">
                            <i class="fas fa-tag text-amber-600 text-sm"></i>
                        </div>
                        <span class="text-xs font-semibold text-gray-500 uppercase">Kategori</span>
                    </div>
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-purple-100 text-purple-700">
                        {{ ucfirst($report->jenis_laporan ?? 'Umum') }}
                    </span>
                </div>

                {{-- Kontak --}}
                <div class="bg-gray-50 rounded-xl p-4">
                    <div class="flex items-center gap-3 mb-2">
                        <div class="w-8 h-8 bg-emerald-200 rounded-lg flex items-center justify-center">
                            <i class="fas fa-phone text-emerald-600 text-sm"></i>
                        </div>
                        <span class="text-xs font-semibold text-gray-500 uppercase">Kontak</span>
                    </div>
                    @if($report->phone)
                        <p class="font-bold text-gray-900">{{ $report->phone }}</p>
                    @else
                        <p class="text-sm text-gray-400 italic">Tidak tersedia</p>
                    @endif
                </div>
            </div>

            {{-- Deskripsi Laporan --}}
            <div class="mb-6">
                <h4 class="text-sm font-semibold text-gray-700 mb-3 flex items-center gap-2">
                    <i class="fas fa-align-left text-purple-600"></i>
                    Deskripsi Laporan
                </h4>
                <div class="bg-gray-50 rounded-xl p-5">
                    <p class="text-gray-700 leading-relaxed whitespace-pre-line">{{ $report->isi_laporan }}</p>
                </div>
            </div>

            {{-- Lampiran --}}
            @if($report->detail && $report->detail->evidence)
            <div>
                <h4 class="text-sm font-semibold text-gray-700 mb-3 flex items-center gap-2">
                    <i class="fas fa-paperclip text-purple-600"></i>
                    Lampiran Bukti
                </h4>
                <a href="{{ asset('storage/' . $report->detail->evidence) }}" target="_blank" class="block max-w-md">
                    <img src="{{ asset('storage/' . $report->detail->evidence) }}"
                         class="w-full rounded-xl border border-gray-200 hover:border-purple-300 transition-colors shadow-sm" alt="Bukti laporan" loading="lazy">
                </a>
                <p class="text-xs text-gray-500 mt-2">
                    <i class="fas fa-magnifying-glass text-[10px]"></i> Klik gambar untuk perbesar
                </p>
            </div>
            @endif
        </div>
    </div>

    {{-- Card Chat & Kontak --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-5">

        {{-- Card Chat --}}
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-100 flex items-center gap-3 bg-gradient-to-r from-purple-50 to-white">
                <div class="w-10 h-10 bg-purple-100 rounded-xl flex items-center justify-center">
                    <i class="fas fa-comments text-purple-600"></i>
                </div>
                <div>
                    <h3 class="font-bold text-gray-900">Komunikasi</h3>
                    <p class="text-xs text-gray-500">Hubungi murid via chat atau WhatsApp</p>
                </div>
            </div>

            <div class="p-5">
                {{-- Profil Murid Mini --}}
                <div class="flex items-center gap-4 mb-5 p-4 bg-gray-50 rounded-xl">
                    @php
                        $studentName = $report->nama_murid ?? 'Anonim';
                        $initials = collect(explode(' ', $studentName))->map(fn($p) => strtoupper(substr($p, 0, 1)))->take(2)->join('');
                    @endphp
                    <div class="w-14 h-14 bg-purple-600 rounded-xl flex items-center justify-center text-white text-xl font-bold shadow-md">
                        {{ $initials }}
                    </div>
                    <div class="flex-1">
                        <h4 class="font-bold text-gray-900">{{ $studentName }}</h4>
                        <div class="flex items-center gap-2 mt-1">
                            <span class="relative flex h-2 w-2">
                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-slate-400 opacity-75" id="onlinePing"></span>
                                <span class="relative inline-flex rounded-full h-2 w-2 bg-purple-400/30" id="onlineStatus"></span>
                            </span>
                            <span id="onlineText" class="text-xs text-gray-500">Memeriksa status...</span>
                        </div>
                    </div>
                </div>

                {{-- Button Actions --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    {{-- Chat Langsung --}}
                    <a href="{{ route('chat.index', $report->tracking_code) }}" target="_blank"
                       class="flex items-center justify-center gap-3 px-5 py-4 bg-purple-600 text-white rounded-xl hover:bg-purple-700 transition-all shadow-lg shadow-purple-200 group">
                        <div class="w-10 h-10 bg-white/20 rounded-lg flex items-center justify-center group-hover:scale-110 transition-transform">
                            <i class="fas fa-comment-dots text-xl"></i>
                        </div>
                        <div class="text-left">
                            <p class="font-bold">Chat Langsung</p>
                            <p class="text-xs text-purple-200">Buka di halaman baru</p>
                        </div>
                    </a>

                    {{-- Via WA --}}
                    @if($report->phone)
                        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $report->phone) }}" target="_blank"
                           class="flex items-center justify-center gap-3 px-5 py-4 bg-emerald-500 text-white rounded-xl hover:bg-emerald-600 transition-all shadow-lg shadow-emerald-200 group">
                            <div class="w-10 h-10 bg-white/20 rounded-lg flex items-center justify-center group-hover:scale-110 transition-transform">
                                <i class="fab fa-whatsapp text-xl"></i>
                            </div>
                            <div class="text-left">
                                <p class="font-bold">Via WhatsApp</p>
                                <p class="text-xs text-emerald-100">{{ $report->phone }}</p>
                            </div>
                        </a>
                    @else
                        <div class="flex items-center justify-center gap-3 px-5 py-4 bg-gray-100 text-gray-400 rounded-xl cursor-not-allowed">
                            <div class="w-10 h-10 bg-gray-200 rounded-lg flex items-center justify-center">
                                <i class="fab fa-whatsapp text-xl"></i>
                            </div>
                            <div class="text-left">
                                <p class="font-bold">Via WhatsApp</p>
                                <p class="text-xs">Nomor tidak tersedia</p>
                            </div>
                        </div>
                    @endif
                </div>

                {{-- Info --}}
                <div class="mt-4 p-3 bg-amber-50 rounded-lg flex items-start gap-3">
                    <i class="fas fa-info-circle text-amber-500 mt-0.5"></i>
                    <p class="text-xs text-amber-700">
                        Chat internal akan dihapus otomatis dalam 3 hari. Gunakan WhatsApp untuk komunikasi penting.
                    </p>
                </div>
            </div>
        </div>

        {{-- Card Info Tambahan --}}
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-100 flex items-center gap-3 bg-gradient-to-r from-blue-50 to-white">
                <div class="w-10 h-10 bg-blue-100 rounded-xl flex items-center justify-center">
                    <i class="fas fa-info-circle text-blue-600"></i>
                </div>
                <div>
                    <h3 class="font-bold text-gray-900">Informasi</h3>
                    <p class="text-xs text-gray-500">Detail tambahan laporan</p>
                </div>
            </div>

            <div class="p-5 space-y-4">
                {{-- Email --}}
                <div class="flex items-start gap-3">
                    <div class="w-10 h-10 bg-gray-100 rounded-lg flex items-center justify-center flex-shrink-0">
                        <i class="fas fa-envelope text-gray-500"></i>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-xs text-gray-500 mb-1">Email Murid</p>
                        @if($report->email_murid)
                            <a href="mailto:{{ $report->email_murid }}" class="text-sm font-medium text-blue-600 hover:underline truncate block">
                                {{ $report->email_murid }}
                            </a>
                        @else
                            <p class="text-sm text-gray-400 italic">Tidak tersedia</p>
                        @endif
                    </div>
                </div>

                {{-- Tanggal --}}
                <div class="flex items-start gap-3">
                    <div class="w-10 h-10 bg-gray-100 rounded-lg flex items-center justify-center flex-shrink-0">
                        <i class="fas fa-calendar text-gray-500"></i>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 mb-1">Tanggal Laporan</p>
                        <p class="text-sm font-medium text-gray-900">{{ $report->created_at->translatedFormat('d F Y') }}</p>
                        <p class="text-xs text-gray-500">{{ $report->created_at->translatedFormat('H:i') }} WIB</p>
                    </div>
                </div>

                {{-- Status --}}
                <div class="flex items-start gap-3">
                    <div class="w-10 h-10 bg-gray-100 rounded-lg flex items-center justify-center flex-shrink-0">
                        <i class="fas fa-shield-halved text-gray-500"></i>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 mb-1">Status Data</p>
                        <p class="text-sm text-gray-700 leading-relaxed">
                            Data siswa bersifat rahasia. Tangani laporan secara profesional.
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    const trackingCode = '{{ $report->tracking_code }}';
    const onlineStatus = document.getElementById('onlineStatus');
    const onlinePing = document.getElementById('onlinePing');
    const onlineText = document.getElementById('onlineText');

    async function updatePresence() {
        try {
            // Track activity
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
            fetch('/api/chat/active', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken
                },
                body: JSON.stringify({
                    report_id: {{ $report->id }},
                    is_student: false
                })
            }).catch(() => {});

            // Get presence
            const presenceResponse = await fetch(`/api/presence/${trackingCode}`);
            const presenceData = await presenceResponse.json();

            const isStudentOnline = presenceData.student;

            if (isStudentOnline) {
                onlineStatus.classList.replace('bg-purple-400/30', 'bg-emerald-500');
                onlinePing.classList.replace('bg-slate-400', 'bg-emerald-400');
                onlineText.textContent = 'Online';
                onlineText.classList.remove('text-gray-500');
                onlineText.classList.add('text-emerald-600', 'font-medium');
            } else {
                onlineStatus.classList.replace('bg-emerald-500', 'bg-purple-400/30');
                onlinePing.classList.replace('bg-emerald-400', 'bg-slate-400');
                onlineText.textContent = 'Offline';
                onlineText.classList.remove('text-emerald-600', 'font-medium');
                onlineText.classList.add('text-gray-500');
            }
        } catch (error) {
            onlineText.textContent = 'Status tidak diketahui';
        }
    }

    // Initial load and polling
    updatePresence();
    setInterval(updatePresence, 5000);
</script>
@endpush
@endsection
