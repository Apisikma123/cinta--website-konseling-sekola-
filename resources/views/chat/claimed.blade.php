@extends('layouts.teacher')

@section('content')
<div class="max-w-3xl mx-auto flex flex-col items-center justify-center min-h-[60vh] text-center mt-10">
    <div class="w-28 h-28 bg-rose-100 text-rose-500 rounded-[2rem] flex items-center justify-center mb-8 shadow-inner border border-rose-200">
        <i class="fas fa-lock text-5xl"></i>
    </div>
    
    <h1 class="text-4xl font-black text-slate-800 tracking-tight mb-4">Akses Ditolak</h1>
    
    <div class="flex items-center gap-2 bg-slate-100 px-4 py-2 rounded-full mb-6">
        <span class="text-slate-500 text-sm font-medium">Kode Tracking:</span>
        <span class="text-slate-800 text-sm font-bold uppercase tracking-wider">{{ $report->tracking_code }}</span>
    </div>

    <p class="text-slate-500 text-base max-w-lg mb-10 leading-relaxed font-medium">
        Laporan perlindungan anak ini telah diambil dan sedang ditangani oleh guru BK lain. Untuk menjaga kerahasiaan murid, Anda tidak memiliki akses ke percakapan ini.
    </p>
    
    <a href="{{ route('teacher.reports.index') }}" class="group px-7 py-3.5 bg-slate-900 hover:bg-slate-800 text-white text-sm font-bold rounded-2xl transition-all shadow-xl shadow-slate-900/20 active:scale-95 flex items-center gap-3">
        <i class="fas fa-arrow-left text-slate-400 group-hover:-translate-x-1 transition-transform"></i> Kembali ke Daftar Laporan
    </a>
</div>
@endsection
