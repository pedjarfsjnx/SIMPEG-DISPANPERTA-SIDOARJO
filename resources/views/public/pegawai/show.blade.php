@extends('layouts.public')

@section('title', 'Detail Pegawai - '.$pegawai->nama)

@section('content')
<div class="max-w-4xl mx-auto space-y-5 text-xs">
    
    <a href="{{ route('public.pegawai.index') }}" class="inline-flex items-center font-medium text-emerald-800 hover:text-emerald-950 transition">
        &larr; Kembali ke Direktori Pegawai
    </a>

    <!-- Header Card -->
    <div class="bg-white rounded-lg border border-slate-200 p-6 shadow-sm flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <div>
            <span class="inline-block px-2.5 py-0.5 bg-emerald-100 text-emerald-800 font-semibold rounded mb-2">
                {{ $pegawai->kategori?->nama ?? 'Pegawai' }}
            </span>
            <h2 class="text-2xl font-bold text-slate-900">{{ $pegawai->nama }}</h2>
            <p class="text-xs text-slate-500 font-mono mt-1">
                {{ $pegawai->nip ? 'NIP. '.$pegawai->nip : ($pegawai->nik ? 'NIK. '.$pegawai->nik : 'NIP / NIK tidak tertera di Excel master') }}
            </p>
        </div>
        <div class="text-left sm:text-right border-t sm:border-t-0 pt-2 sm:pt-0 border-slate-100">
            <div class="text-[11px] text-slate-400 font-medium uppercase">Status Kepegawaian</div>
            <div class="text-sm font-bold text-slate-900 mt-0.5">{{ $pegawai->status?->nama ?? 'Aktif' }}</div>
        </div>
    </div>

    <!-- Details Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
        
        <!-- Column 1: Jabatan & Unit -->
        <div class="bg-white rounded-lg border border-slate-200 p-5 space-y-4 shadow-sm">
            <h3 class="text-xs font-bold uppercase tracking-wider text-slate-500 border-b border-slate-100 pb-2">Informasi Jabatan & Unit Kerja</h3>

            <div>
                <label class="text-slate-400 block text-[11px]">Unit Kerja Utama</label>
                <p class="font-semibold text-slate-900 text-sm">{{ $pegawai->unitKerja?->nama ?? '-' }}</p>
            </div>

            <div>
                <label class="text-slate-400 block text-[11px]">Bidang / Sub-Unit</label>
                <p class="font-semibold text-slate-900 text-sm">
                    {{ $pegawai->bidang?->nama ?: 'Non-Struktural (Pelaksana Unit Kerja)' }}
                </p>
            </div>

            <div>
                <label class="text-slate-400 block text-[11px]">Jabatan / Kebutuhan Posisi</label>
                <p class="font-semibold text-slate-900 text-sm">{{ $pegawai->formasiJabatan?->nama_jabatan ?? '-' }}</p>
            </div>

            <div>
                <label class="text-slate-400 block text-[11px]">Golongan / Ruang</label>
                <p class="font-mono font-semibold text-slate-900 text-sm">{{ $pegawai->golongan ?: '-' }}</p>
            </div>
        </div>

        <!-- Column 2: Umum & TMT -->
        <div class="bg-white rounded-lg border border-slate-200 p-5 space-y-4 shadow-sm">
            <h3 class="text-xs font-bold uppercase tracking-wider text-slate-500 border-b border-slate-100 pb-2">Informasi Umum & TMT</h3>

            <div>
                <label class="text-slate-400 block text-[11px]">Pendidikan Terakhir</label>
                <p class="font-semibold text-slate-900 text-sm">{{ $pegawai->pendidikan ?: '-' }}</p>
            </div>

            <div>
                <label class="text-slate-400 block text-[11px]">TMT Jabatan / Pengangkatan (Terhitung Mulai Tanggal)</label>
                <p class="font-semibold text-emerald-900 text-sm font-mono">
                    {{ $pegawai->tmt_jabatan ? \Carbon\Carbon::parse($pegawai->tmt_jabatan)->translatedFormat('d F Y') : '-' }}
                </p>
            </div>

            <div>
                <label class="text-slate-400 block text-[11px]">Usia Pegawai Saat Ini</label>
                <p class="font-semibold text-slate-900 text-sm">
                    {{ $pegawai->usia ? $pegawai->usia.' Tahun' : '-' }}
                </p>
            </div>

            <div class="bg-slate-50 border border-slate-200 p-3 rounded text-[11px] text-slate-600">
                Informasi Kontak Pribadi (NIK, Telepon, Email) dibatasi aksesnya hanya untuk pengelola administrasi kepegawaian.
            </div>
        </div>

    </div>

    <!-- Smart Auto-Calculated Career & Pension Section -->
    <div class="bg-white rounded-lg border border-slate-200 p-5 shadow-sm space-y-4">
        <h3 class="text-xs font-bold uppercase tracking-wider text-emerald-800 border-b border-slate-100 pb-2 flex items-center justify-between">
            <span>Estimasi Karir & Usia Pensiun (Otomatis Terhitung dari NIP/TMT)</span>
            <span class="text-[10px] bg-emerald-100 text-emerald-800 px-2 py-0.5 rounded font-bold">Otomatis BKN</span>
        </h3>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            
            <!-- Estimasi Pensiun -->
            <div class="bg-amber-50/60 border border-amber-200 rounded p-4 space-y-1">
                <div class="text-[11px] font-bold text-amber-900 uppercase">Jadwal Batas Usia Pensiun (BUP {{ $pegawai->estimasi_pensiun['usia'] }} Thn)</div>
                <div class="text-base font-bold text-slate-900 font-mono">
                    {{ $pegawai->estimasi_pensiun['tanggal'] ? $pegawai->estimasi_pensiun['tanggal']->translatedFormat('d F Y') : 'Membutuhkan Tanggal Lahir / NIP' }}
                </div>
                <p class="text-[10px] text-amber-800">
                    Dihitung otomatis berdasarkan usia pensiun resmi ASN ({{ $pegawai->estimasi_pensiun['usia'] }} tahun).
                </p>
            </div>

            <!-- Estimasi Kenaikan Pangkat -->
            <div class="bg-emerald-50/60 border border-emerald-200 rounded p-4 space-y-1">
                <div class="text-[11px] font-bold text-emerald-900 uppercase">Perkiraan Kenaikan Pangkat (KP 4 Thn Sekali)</div>
                <div class="text-base font-bold text-slate-900 font-mono">
                    {{ $pegawai->estimasi_kp_berikutnya ? $pegawai->estimasi_kp_berikutnya->translatedFormat('d F Y') : 'Membutuhkan TMT Jabatan / NIP' }}
                </div>
                <p class="text-[10px] text-emerald-800">
                    Dihitung otomatis per siklus 4 tahunan dari TMT Pengangkatan/Jabatan.
                </p>
            </div>

        </div>
    </div>

</div>
@endsection
