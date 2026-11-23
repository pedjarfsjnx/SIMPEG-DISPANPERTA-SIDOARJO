@extends('layouts.admin')

@section('title', 'Detail Pegawai Admin - '.$pegawai->nama)

@section('content')
<div class="max-w-5xl mx-auto space-y-6 text-xs">
    
    <!-- Top Action Bar -->
    <div class="flex items-center justify-between gap-3">
        <a href="{{ route('admin.pegawai.index') }}" class="inline-flex items-center space-x-2 px-3.5 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold text-xs rounded-xl transition border border-slate-200 shadow-2xs">
            <span>&larr;</span>
            <span>Kembali ke Daftar Pegawai</span>
        </a>
        <div class="flex items-center gap-2">
            <button onclick="window.print()" class="px-3.5 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold text-xs rounded-xl border border-slate-200 shadow-2xs transition flex items-center space-x-1.5">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                <span>Cetak Profil</span>
            </button>
            <a href="{{ route('admin.pegawai.edit', $pegawai->id) }}" class="px-4 py-2 bg-emerald-800 hover:bg-emerald-900 text-white font-bold text-xs rounded-xl shadow-sm transition flex items-center space-x-1.5">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                <span>Edit Data Pegawai</span>
            </a>
        </div>
    </div>

    <!-- Executive Profile Banner Card -->
    <div class="bg-white rounded-2xl border border-slate-200/90 p-6 sm:p-8 shadow-sm relative overflow-hidden">
        <div class="absolute top-0 right-0 w-64 h-64 bg-emerald-50 rounded-full blur-3xl -z-10 opacity-70"></div>
        
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-6">
            <div class="flex items-start sm:items-center space-x-5">
                <!-- Monogram Avatar -->
                <div class="w-16 h-16 sm:w-20 sm:h-20 rounded-2xl bg-gradient-to-br from-emerald-800 to-teal-900 text-white flex items-center justify-center font-black text-2xl sm:text-3xl shadow-md border-2 border-emerald-700/20 flex-shrink-0">
                    {{ strtoupper(substr($pegawai->nama, 0, 1)) }}
                </div>

                <div class="space-y-1.5">
                    <div class="flex flex-wrap items-center gap-2">
                        @if($pegawai->is_pns)
                            <span class="px-2.5 py-0.5 bg-emerald-100 text-emerald-800 font-bold rounded-lg text-[10px] uppercase tracking-wider border border-emerald-200">
                                PNS (Pegawai Negeri Sipil)
                            </span>
                        @elseif($pegawai->is_pppk)
                            <span class="px-2.5 py-0.5 bg-amber-100 text-amber-900 font-bold rounded-lg text-[10px] uppercase tracking-wider border border-amber-200">
                                PPPK
                            </span>
                        @elseif($pegawai->is_pppk_paruh_waktu)
                            <span class="px-2.5 py-0.5 bg-blue-100 text-blue-900 font-bold rounded-lg text-[10px] uppercase tracking-wider border border-blue-200">
                                PPPK Paruh Waktu
                            </span>
                        @elseif($pegawai->is_swakelola)
                            <span class="px-2.5 py-0.5 bg-purple-100 text-purple-900 font-bold rounded-lg text-[10px] uppercase tracking-wider border border-purple-200">
                                Swakelola
                            </span>
                        @elseif($pegawai->is_outsourcing)
                            <span class="px-2.5 py-0.5 bg-slate-100 text-slate-800 font-bold rounded-lg text-[10px] uppercase tracking-wider border border-slate-200">
                                Outsourcing
                            </span>
                        @else
                            <span class="px-2.5 py-0.5 bg-slate-100 text-slate-700 font-bold rounded-lg text-[10px] uppercase tracking-wider border border-slate-200">
                                {{ $pegawai->kategori?->nama ?? 'Pegawai' }}
                            </span>
                        @endif

                        <span class="inline-flex items-center space-x-1 px-2.5 py-0.5 bg-emerald-50 text-emerald-800 font-semibold rounded-lg text-[10px] border border-emerald-200">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                            <span>{{ $pegawai->status?->nama ?? 'Aktif' }}</span>
                        </span>
                    </div>

                    <h2 class="text-2xl font-black text-slate-900 tracking-tight">{{ $pegawai->nama }}</h2>

                    <div class="flex flex-wrap items-center gap-x-4 gap-y-1 text-slate-500 text-xs">
                        @if($pegawai->nip)
                            <span class="font-mono font-medium">NIP. {{ $pegawai->nip }}</span>
                        @endif

                        @if($pegawai->nik)
                            <span class="font-mono font-medium">NIK. {{ $pegawai->nik }}</span>
                        @endif

                        @if($pegawai->is_pns && $pegawai->golongan)
                            <span class="text-slate-300">&bull;</span>
                            <span class="font-bold text-emerald-900">Golongan {{ $pegawai->golongan }}</span>
                        @elseif($pegawai->is_pppk && $pegawai->golongan)
                            <span class="text-slate-300">&bull;</span>
                            <span class="font-bold text-amber-900">Golongan Gaji {{ $pegawai->golongan }}</span>
                        @endif
                    </div>
                </div>
            </div>

            <div class="sm:text-right border-t sm:border-t-0 pt-3 sm:pt-0 border-slate-100 w-full sm:w-auto">
                <div class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Unit Kerja Induk</div>
                <div class="text-sm font-extrabold text-slate-800 mt-0.5">{{ $pegawai->unitKerja?->nama ?? '-' }}</div>
                <div class="text-xs text-slate-400 mt-0.5">{{ $pegawai->bidang?->nama ?? 'Non-Bidang / Fungsional' }}</div>
            </div>
        </div>
    </div>

    <!-- 2 Column Details Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        
        <!-- Column 1: Jabatan & Kedinasan -->
        <div class="bg-white rounded-2xl border border-slate-200/90 p-6 space-y-4 shadow-sm">
            <div class="flex items-center space-x-2.5 border-b border-slate-100 pb-3">
                <span class="w-2 h-2 rounded-full bg-emerald-700"></span>
                <h3 class="text-xs font-bold uppercase tracking-wider text-slate-800">Informasi Jabatan & Penempatan</h3>
            </div>

            <div class="space-y-3.5">
                <div>
                    <label class="text-[11px] font-semibold text-slate-400 block uppercase tracking-wider">Unit Kerja</label>
                    <p class="font-bold text-slate-900 text-sm mt-0.5">{{ $pegawai->unitKerja?->nama ?? '-' }}</p>
                </div>

                <div>
                    <label class="text-[11px] font-semibold text-slate-400 block uppercase tracking-wider">Bidang / Sub-Unit</label>
                    <p class="font-semibold text-slate-800 text-sm mt-0.5">
                        {{ $pegawai->bidang?->nama ?: 'Non-Struktural (Pelaksana Unit Kerja)' }}
                    </p>
                </div>

                <div>
                    <label class="text-[11px] font-semibold text-slate-400 block uppercase tracking-wider">
                        @if($pegawai->is_pppk_paruh_waktu)
                            Kebutuhan Jabatan
                        @elseif($pegawai->is_swakelola || $pegawai->is_outsourcing)
                            Jabatan / Pekerjaan
                        @else
                            Nama Jabatan
                        @endif
                    </label>
                    <p class="font-bold text-slate-900 text-sm mt-0.5">{{ $pegawai->formasiJabatan?->nama_jabatan ?? $pegawai->jabatan ?? '-' }}</p>
                </div>

                {{-- Golongan & Kelas Jabatan (Hanya PNS & PPPK Penuh jika ada di database) --}}
                @if($pegawai->is_pns)
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-2 border-t border-slate-100">
                        @if($pegawai->golongan)
                            <div>
                                <label class="text-[11px] font-semibold text-slate-400 block uppercase tracking-wider">Golongan / Ruang</label>
                                <p class="font-mono font-bold text-slate-900 text-sm mt-0.5">{{ $pegawai->golongan }}</p>
                            </div>
                        @endif

                        @if($pegawai->formasiJabatan?->kelas_jabatan)
                            <div>
                                <label class="text-[11px] font-semibold text-slate-400 block uppercase tracking-wider">Kelas Jabatan</label>
                                <p class="font-mono font-bold text-slate-900 text-sm mt-0.5">Kelas {{ $pegawai->formasiJabatan->kelas_jabatan }}</p>
                            </div>
                        @endif
                    </div>
                @elseif($pegawai->is_pppk)
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-2 border-t border-slate-100">
                        @if($pegawai->golongan)
                            <div>
                                <label class="text-[11px] font-semibold text-slate-400 block uppercase tracking-wider">Golongan Gaji</label>
                                <p class="font-mono font-bold text-amber-900 text-sm mt-0.5">{{ $pegawai->golongan }}</p>
                            </div>
                        @endif

                        @if($pegawai->formasiJabatan?->kelas_jabatan)
                            <div>
                                <label class="text-[11px] font-semibold text-slate-400 block uppercase tracking-wider">Kelas Jabatan</label>
                                <p class="font-mono font-bold text-slate-900 text-sm mt-0.5">Kelas {{ $pegawai->formasiJabatan->kelas_jabatan }}</p>
                            </div>
                        @endif
                    </div>
                @endif

                {{-- TMT Jabatan & TMT Pangkat (Hanya jika benar-benar ada di database) --}}
                @if($pegawai->is_pns)
                    @if($pegawai->tmt_jabatan || $pegawai->tmt_pangkat)
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-2 border-t border-slate-100">
                            @if($pegawai->tmt_jabatan)
                                <div>
                                    <label class="text-[11px] font-semibold text-slate-400 block uppercase tracking-wider">TMT Jabatan</label>
                                    <p class="font-mono font-bold text-emerald-900 text-sm mt-0.5">
                                        {{ $pegawai->tmt_jabatan->translatedFormat('d F Y') }}
                                    </p>
                                </div>
                            @endif

                            @if($pegawai->tmt_pangkat)
                                <div>
                                    <label class="text-[11px] font-semibold text-slate-400 block uppercase tracking-wider">TMT Pangkat</label>
                                    <p class="font-mono font-bold text-teal-900 text-sm mt-0.5">
                                        {{ $pegawai->tmt_pangkat->translatedFormat('d F Y') }}
                                    </p>
                                </div>
                            @endif
                        </div>
                    @endif
                @elseif($pegawai->is_pppk)
                    @if($pegawai->tmt_jabatan)
                        <div class="pt-2 border-t border-slate-100">
                            <label class="text-[11px] font-semibold text-slate-400 block uppercase tracking-wider">TMT Jabatan</label>
                            <p class="font-mono font-bold text-emerald-900 text-sm mt-0.5">
                                {{ $pegawai->tmt_jabatan->translatedFormat('d F Y') }}
                            </p>
                        </div>
                    @endif
                @endif

            </div>
        </div>

        <!-- Column 2: Biodata & Kontak -->
        <div class="bg-white rounded-2xl border border-slate-200/90 p-6 space-y-4 shadow-sm">
            <div class="flex items-center space-x-2.5 border-b border-slate-100 pb-3">
                <span class="w-2 h-2 rounded-full bg-emerald-700"></span>
                <h3 class="text-xs font-bold uppercase tracking-wider text-slate-800">Biodata Pribadi & Kontak</h3>
            </div>

            <div class="space-y-3.5">
                @if($pegawai->pendidikan)
                    <div>
                        <label class="text-[11px] font-semibold text-slate-400 block uppercase tracking-wider">Pendidikan Terakhir</label>
                        <p class="font-bold text-slate-900 text-sm mt-0.5">{{ $pegawai->pendidikan }}</p>
                    </div>
                @endif

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    @if($pegawai->tanggal_lahir)
                        <div>
                            <label class="text-[11px] font-semibold text-slate-400 block uppercase tracking-wider">Tanggal Lahir</label>
                            <p class="font-mono font-semibold text-slate-900 text-sm mt-0.5">
                                {{ $pegawai->tanggal_lahir->translatedFormat('d F Y') }}
                            </p>
                        </div>

                        <div>
                            <label class="text-[11px] font-semibold text-slate-400 block uppercase tracking-wider">Usia Saat Ini</label>
                            <p class="font-bold text-slate-900 text-sm mt-0.5">{{ $pegawai->tanggal_lahir->age }} Tahun</p>
                        </div>
                    @endif

                    @if($pegawai->tempat_lahir)
                        <div>
                            <label class="text-[11px] font-semibold text-slate-400 block uppercase tracking-wider">Tempat Lahir</label>
                            <p class="font-semibold text-slate-900 text-sm mt-0.5">{{ $pegawai->tempat_lahir }}</p>
                        </div>
                    @endif
                </div>

                @if($pegawai->nik)
                    <div>
                        <label class="text-[11px] font-semibold text-slate-400 block uppercase tracking-wider">Nomor Induk Kependudukan (NIK)</label>
                        <p class="font-mono font-semibold text-slate-900 text-sm mt-0.5">{{ $pegawai->nik }}</p>
                    </div>
                @endif

                @if($pegawai->no_hp)
                    <div class="pt-2 border-t border-slate-100">
                        <label class="text-[11px] font-semibold text-slate-400 block uppercase tracking-wider mb-1">Nomor Telepon / WhatsApp</label>
                        <div class="flex items-center justify-between gap-2">
                            <p class="font-mono font-bold text-slate-900 text-sm">{{ $pegawai->no_hp }}</p>
                            @php
                                $cleanWa = preg_replace('/[^0-9]/', '', $pegawai->no_hp);
                                if (str_starts_with($cleanWa, '0')) { $cleanWa = '62' . substr($cleanWa, 1); }
                                elseif (str_starts_with($cleanWa, '8')) { $cleanWa = '62' . $cleanWa; }
                            @endphp
                            <a href="https://wa.me/{{ $cleanWa }}" target="_blank" class="inline-flex items-center space-x-1.5 px-3 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white text-[11px] font-bold rounded-xl shadow-xs transition">
                                <span>Hubungi WA</span>
                            </a>
                        </div>
                    </div>
                @endif

                @if($pegawai->email)
                    <div>
                        <label class="text-[11px] font-semibold text-slate-400 block uppercase tracking-wider mb-1">Alamat Email</label>
                        <div class="flex items-center justify-between gap-2">
                            <p class="font-mono font-semibold text-slate-900 text-sm break-all">{{ $pegawai->email }}</p>
                            <a href="mailto:{{ $pegawai->email }}" class="inline-flex items-center space-x-1.5 px-3 py-1.5 bg-sky-600 hover:bg-sky-700 text-white text-[11px] font-bold rounded-xl shadow-xs transition">
                                <span>Kirim Email</span>
                            </a>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Official Pension & KP Records (Hanya jika tercatat resmi di database SIMPEG) -->
    @php
        $hasUsulanKp = $pegawai->is_pns && $pegawai->usulanKpResmi;
        $hasPensiun = ($pegawai->is_pns || $pegawai->is_pppk) && $pegawai->riwayatPensiunResmi;
    @endphp

    @if($hasUsulanKp || $hasPensiun)
        <div class="bg-white rounded-2xl border border-slate-200/90 p-6 sm:p-8 shadow-sm space-y-5">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2 border-b border-slate-100 pb-4">
                <div>
                    <h3 class="text-sm font-extrabold uppercase tracking-wider text-slate-900">
                        Catatan Resmi Kepegawaian
                    </h3>
                    <p class="text-xs text-slate-500 mt-0.5">Data resmi yang tercatat langsung pada sistem database kepegawaian DISPANPERTA.</p>
                </div>
                <span class="px-3 py-1 bg-emerald-100 text-emerald-900 font-bold rounded-lg text-[10px] tracking-wider self-start sm:self-auto border border-emerald-200">
                    Database SIMPEG
                </span>
            </div>

            <div class="grid grid-cols-1 {{ ($hasUsulanKp && $hasPensiun) ? 'md:grid-cols-2' : '' }} gap-5">
                
                {{-- Kartu Usulan Kenaikan Pangkat (Hanya jika ada usulan resmi di database) --}}
                @if($hasUsulanKp)
                    @php $usulan = $pegawai->usulanKpResmi; @endphp
                    <div class="bg-gradient-to-br from-emerald-50/70 to-teal-50/40 border border-emerald-200/80 rounded-2xl p-5 space-y-2">
                        <div class="flex items-center justify-between">
                            <span class="text-[11px] font-bold text-emerald-900 uppercase tracking-wider">Usulan Kenaikan Pangkat</span>
                            <span class="px-2 py-0.5 bg-emerald-200/70 text-emerald-950 font-bold rounded text-[10px]">
                                Usulan Terdaftar
                            </span>
                        </div>

                        <div class="text-xl font-extrabold text-emerald-950 font-mono">
                            {{ $usulan->tmt_diusulkan ? $usulan->tmt_diusulkan->translatedFormat('d F Y') : '-' }}
                        </div>

                        <div class="text-xs font-semibold text-emerald-900">
                            Usulan Jenjang: {{ $usulan->golongan_lama ?: '-' }} &rarr; {{ $usulan->golongan_baru ?: '-' }}
                        </div>

                        <p class="text-[11px] text-emerald-800/80 leading-relaxed">
                            {{ $usulan->keterangan ?: 'Usulan kenaikan pangkat dalam proses verifikasi administrasi.' }}
                        </p>
                        <p class="text-[10px] text-slate-500 italic">
                            *Merupakan usulan resmi dan bukan merupakan keputusan final.
                        </p>
                    </div>
                @endif

                {{-- Kartu Pensiun Resmi (Hanya jika ada data pensiun resmi di database) --}}
                @if($hasPensiun)
                    @php $pensiun = $pegawai->riwayatPensiunResmi; @endphp
                    <div class="bg-gradient-to-br from-amber-50/70 to-orange-50/40 border border-amber-200/80 rounded-2xl p-5 space-y-2">
                        <div class="flex items-center justify-between">
                            <span class="text-[11px] font-bold text-amber-900 uppercase tracking-wider">Pencatatan Pensiun Resmi</span>
                            <span class="px-2 py-0.5 bg-amber-200/70 text-amber-950 font-bold rounded text-[10px]">
                                SK / Pengajuan
                            </span>
                        </div>
                        
                        <div class="text-xl font-extrabold text-slate-900 font-mono">
                            {{ $pensiun->tmt_pensiun ? $pensiun->tmt_pensiun->translatedFormat('d F Y') : '-' }}
                        </div>

                        @if($pensiun->tanggal_pengajuan)
                            <div class="text-xs text-amber-950 font-medium">
                                Tanggal Pengajuan: {{ $pensiun->tanggal_pengajuan->translatedFormat('d F Y') }}
                            </div>
                        @endif

                        <p class="text-xs text-amber-900/80 leading-relaxed">
                            {{ $pensiun->keterangan ?: 'Tercatat dalam data riwayat pensiun resmi instansi.' }}
                        </p>
                    </div>
                @endif

            </div>
        </div>
    @endif

</div>
@endsection
