@extends('layouts.admin')

@section('title', 'Detail Pegawai Admin - '.$pegawai->nama)

@section('content')
<div class="max-w-4xl mx-auto space-y-5 text-xs">
    
    <div class="flex items-center justify-between">
        <a href="{{ route('admin.pegawai.index') }}" class="font-medium text-emerald-800 hover:text-emerald-950 transition">
            &larr; Kembali ke Daftar Pegawai
        </a>
        <a href="{{ route('admin.pegawai.edit', $pegawai->id) }}" class="px-3 py-1.5 bg-emerald-800 text-white font-semibold rounded hover:bg-emerald-900 text-xs">
            Edit Data Pegawai
        </a>
    </div>

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
                <label class="text-slate-400 block text-[11px]">Bidang / Sub-Unit Structural</label>
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

        <!-- Column 2: Data Privasi & Admin -->
        <div class="bg-white rounded-lg border border-slate-200 p-5 space-y-4 shadow-sm">
            <h3 class="text-xs font-bold uppercase tracking-wider text-slate-500 border-b border-slate-100 pb-2">Informasi Identitas & Kontak Privasi</h3>

            <div>
                <label class="text-slate-400 block text-[11px]">Pendidikan Terakhir</label>
                <p class="font-semibold text-slate-900 text-sm">{{ $pegawai->pendidikan ?: '-' }}</p>
            </div>

            <div>
                <label class="text-slate-400 block text-[11px]">Nomor KTP (NIK)</label>
                <p class="font-mono font-semibold text-slate-900 text-sm">{{ $pegawai->nik ?: '-' }}</p>
            </div>

            <div>
                <label class="text-slate-400 block text-[11px]">Nomor Telepon / WhatsApp</label>
                <p class="font-mono font-semibold text-slate-900 text-sm">{{ $pegawai->no_hp ?: '-' }}</p>
            </div>

            <div>
                <label class="text-slate-400 block text-[11px]">Alamat Email</label>
                <p class="font-mono font-semibold text-slate-900 text-sm">{{ $pegawai->email ?: '-' }}</p>
            </div>
        </div>

    </div>
</div>
@endsection
