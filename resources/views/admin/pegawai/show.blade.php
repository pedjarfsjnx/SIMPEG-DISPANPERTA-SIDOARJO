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

            <div>
                <label class="text-slate-400 block text-[11px]">TMT Jabatan / Pengangkatan</label>
                <p class="font-mono font-semibold text-emerald-900 text-sm">
                    {{ $pegawai->tmt_jabatan ? \Carbon\Carbon::parse($pegawai->tmt_jabatan)->translatedFormat('d F Y') : '-' }}
                </p>
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
                <label class="text-slate-400 block text-[11px] mb-1">Nomor Telepon / WhatsApp</label>
                <div class="flex items-center justify-between gap-2">
                    <p class="font-mono font-semibold text-slate-900 text-sm">{{ $pegawai->no_hp ?: '-' }}</p>
                    @if(!empty($pegawai->no_hp))
                        @php
                            $cleanWa = preg_replace('/[^0-9]/', '', $pegawai->no_hp);
                            if (str_starts_with($cleanWa, '0')) { $cleanWa = '62' . substr($cleanWa, 1); }
                            elseif (str_starts_with($cleanWa, '8')) { $cleanWa = '62' . $cleanWa; }
                        @endphp
                        <a href="https://wa.me/{{ $cleanWa }}" target="_blank" class="inline-flex items-center space-x-1.5 px-3 py-1 bg-emerald-600 hover:bg-emerald-700 text-white text-xs font-bold rounded-lg shadow-xs transition flex-shrink-0">
                            <svg class="w-3.5 h-3.5 fill-current" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/></svg>
                            <span>WhatsApp</span>
                        </a>
                    @endif
                </div>
            </div>

            <div>
                <label class="text-slate-400 block text-[11px] mb-1">Alamat Email</label>
                <div class="flex items-center justify-between gap-2">
                    <p class="font-mono font-semibold text-slate-900 text-sm break-all">{{ $pegawai->email ?: '-' }}</p>
                    @if(!empty($pegawai->email))
                        <a href="mailto:{{ $pegawai->email }}" class="inline-flex items-center space-x-1.5 px-3 py-1 bg-sky-600 hover:bg-sky-700 text-white text-xs font-bold rounded-lg shadow-xs transition flex-shrink-0">
                            <svg class="w-3.5 h-3.5 fill-none stroke-current" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75" /></svg>
                            <span>Email</span>
                        </a>
                    @endif
                </div>
            </div>

            <div>
                <label class="text-slate-400 block text-[11px]">Usia Pegawai Saat Ini</label>
                <p class="font-semibold text-slate-900 text-sm">{{ $pegawai->usia ? $pegawai->usia.' Tahun' : '-' }}</p>
            </div>
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
