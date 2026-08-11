@extends('layouts.public')

@section('title', 'Struktur Organisasi & Eselon - Dinas Pangan dan Pertanian Kabupaten Sidoarjo')

@section('content')
<div class="space-y-8">
    <!-- Header Banner -->
    <div class="bg-white p-6 rounded-lg border border-slate-200 shadow-sm flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <div>
            <span class="inline-block px-2.5 py-0.5 bg-emerald-100 text-emerald-800 font-bold rounded text-[11px] uppercase tracking-wider mb-1">
                Pemerintah Kabupaten Sidoarjo
            </span>
            <h2 class="text-xl font-bold text-slate-900">Struktur Organisasi & Tingkatan Eselon</h2>
            <p class="text-xs text-slate-500 mt-1">Bagan resmi struktur organisasi, rekapitulasi Eselon kepemimpinan, dan distribusi unit kerja Dinas Pangan dan Pertanian Kabupaten Sidoarjo.</p>
        </div>
        <a href="{{ route('public.pegawai.index') }}" class="px-4 py-2 bg-emerald-800 hover:bg-emerald-900 text-white text-xs font-bold rounded shadow-sm whitespace-nowrap">
            Cari Pegawai &rarr;
        </a>
    </div>

    <!-- Section 1: Foto Bagan Struktur Organisasi Resmi -->
    <div class="bg-white rounded-lg border border-slate-200 p-6 shadow-sm space-y-4">
        <div class="flex items-center justify-between border-b border-slate-100 pb-3">
            <div>
                <h3 class="text-base font-bold text-slate-900 uppercase tracking-wider">Bagan Gambar Struktur Organisasi Dinas</h3>
                <p class="text-xs text-slate-500">Bagan visual hierarki kepemimpinan Dinas Pangan dan Pertanian Kabupaten Sidoarjo.</p>
            </div>
            @if(file_exists(public_path('images/struktur-organisasi.png')) || file_exists(public_path('images/struktur-organisasi.jpg')))
            <a href="{{ file_exists(public_path('images/struktur-organisasi.png')) ? asset('images/struktur-organisasi.png') : asset('images/struktur-organisasi.jpg') }}" 
               target="_blank" 
               class="px-3 py-1.5 bg-slate-800 hover:bg-slate-900 text-white text-xs font-semibold rounded shadow-sm">
               Buka Foto Ukuran Penuh &rarr;
            </a>
            @endif
        </div>

        <div class="bg-slate-50 border-2 border-dashed border-slate-300 rounded-lg p-4 flex items-center justify-center min-h-[300px]">
            @if(file_exists(public_path('images/struktur-organisasi.png')))
                <img src="{{ asset('images/struktur-organisasi.png') }}" alt="Bagan Struktur Organisasi Dispanperta Sidoarjo" class="max-w-full h-auto rounded shadow-md border border-slate-200">
            @elseif(file_exists(public_path('images/struktur-organisasi.jpg')))
                <img src="{{ asset('images/struktur-organisasi.jpg') }}" alt="Bagan Struktur Organisasi Dispanperta Sidoarjo" class="max-w-full h-auto rounded shadow-md border border-slate-200">
            @else
                <div class="text-center space-y-2 max-w-md py-6">
                    <div class="w-12 h-12 bg-amber-100 text-amber-800 rounded-full flex items-center justify-center font-bold mx-auto text-xl">
                        📷
                    </div>
                    <h4 class="font-bold text-slate-800 text-sm">Simpan Foto Bagan Struktur Organisasi Di Sini</h4>
                    <p class="text-xs text-slate-500 leading-relaxed">
                        Simpan file foto bagan Anda dengan nama <code class="bg-slate-200 px-1.5 py-0.5 rounded font-mono text-slate-900 font-bold">struktur-organisasi.png</code> atau <code class="bg-slate-200 px-1.5 py-0.5 rounded font-mono text-slate-900 font-bold">struktur-organisasi.jpg</code> di folder:<br>
                        <span class="font-mono text-emerald-800 font-bold">c:\xampp\htdocs\dispanperta\public\images\</span>
                    </p>
                </div>
            @endif
        </div>
    </div>

    <!-- Section 2: Rekapitulasi Pejabat Berdasarkan Eselon -->
    <div class="space-y-6">
        <div class="border-b border-slate-200 pb-2">
            <h3 class="text-lg font-bold text-slate-900 uppercase tracking-wider">Daftar Pejabat Berdasarkan Tingkatan Eselon</h3>
            <p class="text-xs text-slate-500">Rincian nama pejabat, posisi jabatan, dan akses profil detail per tingkat kepemimpinan.</p>
        </div>

        <!-- Grid Eselon II.b & Eselon III.a -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            
            <!-- Card Eselon II.b (Pimpinan Utama) -->
            <div class="bg-white rounded-lg border-2 border-emerald-800 p-5 shadow-sm space-y-4">
                <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                    <div>
                        <span class="text-[10px] font-bold uppercase tracking-wider bg-emerald-800 text-white px-2 py-0.5 rounded">Pimpinan Utama</span>
                        <h4 class="text-sm font-bold text-slate-900 mt-1">Eselon II.b (Kelas 14)</h4>
                    </div>
                    <span class="text-xs font-bold text-emerald-800 bg-emerald-100 px-2.5 py-1 rounded">
                        {{ $eselon2b->count() }} Pejabat
                    </span>
                </div>

                <div class="space-y-3">
                    @forelse($eselon2b as $peg)
                    <div class="bg-slate-50 border border-slate-200 rounded-lg p-3.5 flex items-center justify-between gap-3">
                        <div>
                            <div class="font-bold text-slate-900 text-sm">{{ $peg->nama }}</div>
                            <div class="text-emerald-800 font-semibold text-xs mt-0.5">{{ $peg->formasiJabatan?->nama_jabatan }}</div>
                            <div class="text-slate-500 font-mono text-[11px] mt-0.5">NIP. {{ $peg->nip }} &bull; Gol. {{ $peg->golongan }}</div>
                        </div>
                        <a href="{{ route('public.pegawai.show', $peg->id) }}" class="px-3 py-1.5 bg-emerald-800 hover:bg-emerald-900 text-white font-semibold text-xs rounded transition flex-shrink-0">
                            Lihat Detail &rarr;
                        </a>
                    </div>
                    @empty
                    <p class="text-xs text-slate-400 italic">Data pejabat Eselon II.b belum terdata.</p>
                    @endforelse
                </div>
            </div>

            <!-- Card Eselon III.a (Administrator - Sekdin & Kabid) -->
            <div class="bg-white rounded-lg border border-slate-200 p-5 shadow-sm space-y-4">
                <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                    <div>
                        <span class="text-[10px] font-bold uppercase tracking-wider bg-amber-500 text-slate-950 px-2 py-0.5 rounded">Pejabat Administrator</span>
                        <h4 class="text-sm font-bold text-slate-900 mt-1">Eselon III.a (Kelas 11 - 13)</h4>
                    </div>
                    <span class="text-xs font-bold text-emerald-800 bg-emerald-100 px-2.5 py-1 rounded">
                        {{ $eselon3a->count() }} Pejabat
                    </span>
                </div>

                <div class="space-y-2.5 max-h-[350px] overflow-y-auto pr-1">
                    @forelse($eselon3a as $peg)
                    <div class="bg-slate-50 border border-slate-200 rounded p-3 flex items-center justify-between gap-3 text-xs">
                        <div>
                            <div class="font-bold text-slate-900">{{ $peg->nama }}</div>
                            <div class="text-emerald-800 font-semibold text-[11px] mt-0.5">{{ $peg->formasiJabatan?->nama_jabatan }}</div>
                            <div class="text-slate-500 text-[11px] mt-0.5">{{ $peg->bidang?->nama ?: $peg->unitKerja?->nama }}</div>
                        </div>
                        <a href="{{ route('public.pegawai.show', $peg->id) }}" class="px-2.5 py-1 bg-slate-100 hover:bg-emerald-800 hover:text-white font-medium text-slate-800 border border-slate-300 rounded transition flex-shrink-0">
                            Detail &rarr;
                        </a>
                    </div>
                    @empty
                    <p class="text-xs text-slate-400 italic">Data pejabat Eselon III.a belum terdata.</p>
                    @endforelse
                </div>
            </div>

        </div>

        <!-- Eselon III.b (Kasubbag & Kepala UPTD) -->
        <div class="bg-white rounded-lg border border-slate-200 p-5 shadow-sm space-y-4">
            <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                <div>
                    <span class="text-[10px] font-bold uppercase tracking-wider bg-slate-200 text-slate-800 px-2 py-0.5 rounded">Pejabat Pengawas</span>
                    <h4 class="text-sm font-bold text-slate-900 mt-1">Eselon III.b (Kelas 9 - 10: Kasubbag & Kepala UPTD)</h4>
                </div>
                <span class="text-xs font-bold text-emerald-800 bg-emerald-100 px-2.5 py-1 rounded">
                    {{ $eselon3b->count() }} Pejabat
                </span>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3 text-xs">
                @forelse($eselon3b as $peg)
                <div class="bg-slate-50 border border-slate-200 rounded p-3 flex flex-col justify-between space-y-2">
                    <div>
                        <div class="font-bold text-slate-900">{{ $peg->nama }}</div>
                        <div class="text-emerald-800 font-semibold text-[11px] mt-0.5">{{ $peg->formasiJabatan?->nama_jabatan }}</div>
                        <div class="text-slate-500 text-[11px] mt-0.5 font-mono">NIP. {{ $peg->nip ?: '-' }}</div>
                    </div>
                    <div class="pt-2 border-t border-slate-200 flex items-center justify-between">
                        <span class="text-[10px] text-slate-500">{{ $peg->unitKerja?->nama }}</span>
                        <a href="{{ route('public.pegawai.show', $peg->id) }}" class="text-emerald-800 font-bold hover:underline text-[11px]">Detail &rarr;</a>
                    </div>
                </div>
                @empty
                <div class="col-span-3 text-xs text-slate-400 italic">Data pejabat Eselon III.b belum terdata.</div>
                @endforelse
            </div>
        </div>

    </div>

    <!-- Section 3: Hirarki Unit Kerja & Bidang Struktural -->
    <div class="space-y-6">
        <div class="border-b border-slate-200 pb-2">
            <h3 class="text-lg font-bold text-slate-900 uppercase tracking-wider">Distribusi Pegawai Per Unit Kerja & Bidang</h3>
            <p class="text-xs text-slate-500">Pemetaan alokasi pegawai di Dinas Induk dan UPTD.</p>
        </div>

        <div class="space-y-6">
            @foreach($unitKerjaWithBidang as $unit)
            <div class="bg-white rounded-lg border border-slate-200 shadow-sm overflow-hidden text-xs">
                <div class="bg-emerald-900 text-white p-4 flex flex-col sm:flex-row sm:items-center justify-between gap-2 border-b-2 border-amber-500">
                    <div>
                        <span class="text-[10px] font-semibold uppercase tracking-wider text-emerald-300">[{{ $unit->tipe }}]</span>
                        <h3 class="text-base font-bold">{{ $unit->nama }}</h3>
                    </div>
                    <div class="flex items-center gap-2 text-xs">
                        <span class="px-2.5 py-1 bg-emerald-800 text-emerald-100 rounded font-semibold">
                            {{ $unit->pegawai->count() }} Total Pegawai
                        </span>
                    </div>
                </div>

                <div class="p-5 grid grid-cols-1 md:grid-cols-2 gap-5">
                    @forelse($unit->bidang as $bidang)
                    <div class="bg-slate-50 border border-slate-200 rounded-lg p-4 space-y-3">
                        <div class="flex items-center justify-between border-b border-slate-200 pb-2">
                            <h4 class="font-bold text-slate-900 text-sm leading-snug">{{ $bidang->nama }}</h4>
                            <span class="text-xs text-emerald-800 font-bold bg-emerald-100 px-2 py-0.5 rounded whitespace-nowrap">
                                {{ $bidang->pegawai->count() }} Pegawai
                            </span>
                        </div>

                        @if($bidang->pegawai->count() > 0)
                        <ul class="text-xs text-slate-700 divide-y divide-slate-200 max-h-60 overflow-y-auto pr-1">
                            @foreach($bidang->pegawai as $peg)
                            <li class="py-1.5 flex justify-between items-center">
                                <div>
                                    <a href="{{ route('public.pegawai.show', $peg->id) }}" class="font-semibold text-slate-900 hover:text-emerald-800 hover:underline block">{{ $peg->nama }}</a>
                                    <span class="text-slate-500 text-[11px] block">{{ $peg->formasiJabatan?->nama_jabatan ?: 'Staff / Pelaksana' }}</span>
                                </div>
                                <span class="text-emerald-800 bg-emerald-50 px-2 py-0.5 rounded font-mono text-[10px] font-semibold border border-emerald-200 whitespace-nowrap ml-2">
                                    {{ $peg->kategori?->nama }}
                                </span>
                            </li>
                            @endforeach
                        </ul>
                        @else
                        <div class="text-xs text-slate-500 italic py-3 text-center bg-white rounded border border-dashed border-slate-300">
                            Belum ada pegawai yang dialokasikan di bidang ini.
                        </div>
                        @endif
                    </div>
                    @empty
                    <div class="col-span-2 text-xs text-slate-600 bg-slate-50 p-4 rounded border border-slate-200">
                        <p class="font-bold text-slate-800">Unit Operasional Khusus (UPTD)</p>
                        <p class="text-slate-500 mt-0.5">Beroperasi langsung di bawah Kepala UPTD tanpa bidang struktural terpisah. Total {{ $unit->pegawai->count() }} personel aktif.</p>
                    </div>
                    @endforelse
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endsection
