@extends('layouts.admin')

@section('title', 'Rekapitulasi Pensiun Pegawai - Admin SIMPEG')

@section('content')
<div class="space-y-6 text-xs">
    <!-- Header & Actions -->
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3 pb-2">
        <div>
            <div class="flex items-center space-x-2">
                <span class="px-2 py-0.5 bg-emerald-50 text-emerald-800 font-semibold rounded text-[10px] uppercase tracking-wider border border-emerald-200">Modul Purna Tugas</span>
                <span class="text-slate-300">&bull;</span>
                <span class="text-xs text-slate-500 font-medium">BUP Otomatis NIP & Rekapitulasi</span>
            </div>
            <h2 class="text-xl font-bold text-slate-900 mt-1">Rekapitulasi Batas Usia Pensiun (BUP) Pegawai</h2>
            <p class="text-xs text-slate-500">Perhitungan otomatis TMT pensiun berdasarkan tanggal lahir NIP (BUP 60 Thn untuk Kepala & Fungsional, 58 Thn untuk Pelaksana).</p>
        </div>
        <div class="flex flex-wrap items-center gap-2">
            <a href="{{ route('admin.pensiun.cetak', request()->query()) }}" target="_blank" class="px-3.5 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold text-xs rounded-xl border border-slate-200 shadow-2xs transition flex items-center space-x-1.5" title="Buka Pratinjau Cetak Resmi">
                <svg class="w-3.5 h-3.5 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                <span>Cetak Rekap</span>
            </a>
            <a href="{{ route('admin.pensiun.create') }}" class="px-3.5 py-2 bg-emerald-800 hover:bg-emerald-900 text-white font-semibold text-xs rounded-xl shadow-xs transition flex items-center space-x-1.5">
                <span>+ Catat Berkas Pensiun</span>
            </a>
        </div>
    </div>

    <!-- Unified 4-Metrics Summary Strip -->
    <div class="bg-white rounded-2xl border border-slate-200/90 p-4 shadow-sm">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
            
            <!-- 1. Total Pegawai NIP -->
            <div class="p-3.5 rounded-xl bg-slate-50/80 border border-slate-200/80 flex items-center justify-between">
                <div>
                    <div class="text-[11px] font-semibold text-slate-500 uppercase tracking-wider">Total Pegawai NIP</div>
                    <div class="text-2xl font-bold text-slate-900 mt-0.5">
                        {{ number_format($totalPNS) }} <span class="text-xs font-normal text-slate-500">Personel</span>
                    </div>
                </div>
                <div class="w-8 h-8 rounded-lg bg-slate-200/70 text-slate-600 flex items-center justify-center">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                </div>
            </div>

            <!-- 2. Pensiun Tahun Ini -->
            @php $thIni = (int)date('Y'); @endphp
            <a href="{{ route('admin.pensiun.index', ['tahun_mulai' => $thIni, 'tahun_selesai' => $thIni]) }}" 
               class="group p-3.5 rounded-xl bg-amber-50/50 hover:bg-amber-50/90 border border-amber-200/80 transition flex items-center justify-between">
                <div>
                    <div class="text-[11px] font-semibold text-amber-800 uppercase tracking-wider flex items-center gap-1">
                        <span>Pensiun {{ $thIni }}</span>
                        <span class="text-[10px] text-amber-600 font-normal">(Tahun Ini)</span>
                    </div>
                    <div class="text-2xl font-bold text-amber-900 mt-0.5">
                        {{ number_format($pensiunTahunIni) }} <span class="text-xs font-normal text-amber-700">Pegawai</span>
                    </div>
                </div>
                <span class="text-[11px] text-amber-700 group-hover:text-amber-900 font-semibold">Filter &rarr;</span>
            </a>

            <!-- 3. Pensiun Tahun Depan -->
            <a href="{{ route('admin.pensiun.index', ['tahun_mulai' => $thIni + 1, 'tahun_selesai' => $thIni + 1]) }}" 
               class="group p-3.5 rounded-xl bg-emerald-50/50 hover:bg-emerald-50/90 border border-emerald-200/80 transition flex items-center justify-between">
                <div>
                    <div class="text-[11px] font-semibold text-emerald-800 uppercase tracking-wider flex items-center gap-1">
                        <span>Pensiun {{ $thIni + 1 }}</span>
                        <span class="text-[10px] text-emerald-600 font-normal">(Thn Depan)</span>
                    </div>
                    <div class="text-2xl font-bold text-emerald-900 mt-0.5">
                        {{ number_format($pensiunTahunDepan) }} <span class="text-xs font-normal text-emerald-700">Pegawai</span>
                    </div>
                </div>
                <span class="text-[11px] text-emerald-700 group-hover:text-emerald-900 font-semibold">Filter &rarr;</span>
            </a>

            <!-- 4. Proyeksi 5 Tahun Ke Depan -->
            <a href="{{ route('admin.pensiun.index', ['tahun_mulai' => $thIni, 'tahun_selesai' => $thIni + 4]) }}" 
               class="group p-3.5 rounded-xl bg-sky-50/50 hover:bg-sky-50/90 border border-sky-200/80 transition flex items-center justify-between">
                <div>
                    <div class="text-[11px] font-semibold text-sky-800 uppercase tracking-wider flex items-center gap-1">
                        <span>Proyeksi 5 Thn</span>
                        <span class="text-[10px] text-sky-600 font-normal">({{ $thIni }}-{{ $thIni + 4 }})</span>
                    </div>
                    <div class="text-2xl font-bold text-sky-900 mt-0.5">
                        {{ number_format($pensiun5Tahun) }} <span class="text-xs font-normal text-sky-700">Pegawai</span>
                    </div>
                </div>
                <span class="text-[11px] text-sky-700 group-hover:text-sky-900 font-semibold">Filter &rarr;</span>
            </a>

        </div>
    </div>

    <!-- Filter Card: Pencarian, Unit Kerja, Bulan, & Rentang Tahun -->
    <div class="bg-white p-5 rounded-2xl border border-slate-200/90 shadow-sm space-y-3.5">
        <form method="GET" action="{{ route('admin.pensiun.index') }}" class="space-y-3.5">
            <!-- Baris 1: Search, Unit Kerja, Bulan -->
            <div class="grid grid-cols-1 md:grid-cols-12 gap-3 items-center">
                <!-- Search -->
                <div class="md:col-span-5 relative">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    </div>
                    <input type="text" 
                           name="search" 
                           value="{{ request('search') }}" 
                           placeholder="Cari Nama Pegawai atau 18 digit NIP..." 
                           class="w-full text-xs pl-10 pr-3.5 py-2.5 rounded-xl border-slate-300 focus:border-emerald-700 focus:ring-2 focus:ring-emerald-700/20 bg-slate-50/50 shadow-2xs transition">
                </div>

                <!-- Filter Unit Kerja -->
                <div class="md:col-span-4">
                    <select name="unit_kerja_id" class="w-full text-xs py-2.5 px-3 rounded-xl border-slate-300 focus:border-emerald-700 focus:ring-2 focus:ring-emerald-700/20 bg-white shadow-2xs transition">
                        <option value="">-- Semua Unit Kerja --</option>
                        @foreach($unitKerjaList as $unit)
                            <option value="{{ $unit->id }}" {{ request('unit_kerja_id') == $unit->id ? 'selected' : '' }}>
                                {{ $unit->nama }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Filter Bulan -->
                <div class="md:col-span-3">
                    <select name="bulan" class="w-full text-xs py-2.5 px-3 rounded-xl border-slate-300 focus:border-emerald-700 focus:ring-2 focus:ring-emerald-700/20 bg-white shadow-2xs transition">
                        <option value="">-- Semua Bulan Pensiun --</option>
                        @foreach($bulanOptions as $num => $namaBulan)
                            <option value="{{ $num }}" {{ request('bulan') == $num ? 'selected' : '' }}>
                                Bulan {{ $namaBulan }} ({{ str_pad($num, 2, '0', STR_PAD_LEFT) }})
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- Baris 2: Filter Rentang Tahun & Preset Cepat -->
            <div class="bg-slate-50/80 p-3.5 rounded-xl border border-slate-200/80 flex flex-col md:flex-row md:items-center justify-between gap-3">
                <div class="flex flex-wrap items-center gap-2 text-xs">
                    <span class="font-bold text-slate-700 flex items-center gap-1.5 mr-1">
                        <svg class="w-4 h-4 text-emerald-700" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        Rentang Tahun:
                    </span>
                    <div class="flex items-center gap-1.5">
                        <select name="tahun_mulai" id="tahun_mulai" class="text-xs py-1.5 px-2.5 rounded-lg border-slate-300 bg-white focus:ring-2 focus:ring-emerald-700/20 focus:border-emerald-700 shadow-2xs font-semibold text-slate-800">
                            <option value="">Dari Tahun...</option>
                            @foreach($tahunOptions as $th)
                                <option value="{{ $th }}" {{ ($tahunMulai == $th) ? 'selected' : '' }}>
                                    Tahun {{ $th }}
                                </option>
                            @endforeach
                        </select>
                        <span class="text-slate-400 font-bold text-xs">s.d.</span>
                        <select name="tahun_selesai" id="tahun_selesai" class="text-xs py-1.5 px-2.5 rounded-lg border-slate-300 bg-white focus:ring-2 focus:ring-emerald-700/20 focus:border-emerald-700 shadow-2xs font-semibold text-slate-800">
                            <option value="">Sampai Tahun...</option>
                            @foreach($tahunOptions as $th)
                                <option value="{{ $th }}" {{ ($tahunSelesai == $th) ? 'selected' : '' }}>
                                    Tahun {{ $th }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Preset Badges -->
                    <div class="flex flex-wrap items-center gap-1.5 md:ml-2 md:pl-2 md:border-l border-slate-200">
                        <span class="text-[11px] text-slate-400">Pilihan Cepat:</span>
                        <button type="button" onclick="setRentang({{ $thIni }}, {{ $thIni }})" 
                                class="px-2 py-0.5 rounded text-[11px] font-medium bg-white hover:bg-emerald-50 text-slate-600 hover:text-emerald-700 border border-slate-200 hover:border-emerald-300 transition cursor-pointer">
                            Tahun Ini ({{ $thIni }})
                        </button>
                        <button type="button" onclick="setRentang({{ $thIni }}, {{ $thIni + 1 }})" 
                                class="px-2 py-0.5 rounded text-[11px] font-medium bg-white hover:bg-emerald-50 text-slate-600 hover:text-emerald-700 border border-slate-200 hover:border-emerald-300 transition cursor-pointer">
                            2 Tahun ({{ $thIni }} - {{ $thIni + 1 }})
                        </button>
                        <button type="button" onclick="setRentang({{ $thIni }}, {{ $thIni + 2 }})" 
                                class="px-2 py-0.5 rounded text-[11px] font-medium bg-white hover:bg-emerald-50 text-slate-600 hover:text-emerald-700 border border-slate-200 hover:border-emerald-300 transition cursor-pointer">
                            3 Tahun ({{ $thIni }} - {{ $thIni + 2 }})
                        </button>
                        <button type="button" onclick="setRentang({{ $thIni }}, {{ $thIni + 4 }})" 
                                class="px-2 py-0.5 rounded text-[11px] font-medium bg-white hover:bg-emerald-50 text-slate-600 hover:text-emerald-700 border border-slate-200 hover:border-emerald-300 transition cursor-pointer">
                            5 Tahun ({{ $thIni }} - {{ $thIni + 4 }})
                        </button>
                    </div>
                </div>

                <div class="flex items-center gap-2 self-end md:self-auto">
                    <button type="submit" class="py-1.5 px-4 bg-emerald-800 hover:bg-emerald-900 text-white font-bold text-xs rounded-xl shadow-sm transition flex items-center space-x-1.5 cursor-pointer">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/></svg>
                        <span>Terapkan Rekap</span>
                    </button>
                    @if(request()->hasAny(['search', 'bulan', 'tahun', 'tahun_mulai', 'tahun_selesai', 'unit_kerja_id', 'kategori_id']))
                        <a href="{{ route('admin.pensiun.index') }}" title="Reset Filter" class="py-1.5 px-3 bg-slate-200 hover:bg-slate-300 text-slate-700 font-semibold text-xs rounded-xl transition">
                            Reset
                        </a>
                    @endif
                </div>
            </div>

            <!-- Status Filter Aktif -->
            <div class="pt-2 border-t border-slate-100 flex items-center justify-between text-[11px] text-slate-600">
                <div>
                    @if(request()->filled('bulan') || $tahunMulai || $tahunSelesai || request()->filled('search') || request()->filled('unit_kerja_id'))
                        <span class="font-bold text-emerald-800">
                            Filter Aktif: 
                            @if($tahunMulai && $tahunSelesai && $tahunMulai == $tahunSelesai)
                                Tahun {{ $tahunMulai }}
                            @elseif($tahunMulai && $tahunSelesai)
                                Rentang Tahun {{ min($tahunMulai, $tahunSelesai) }} s.d. {{ max($tahunMulai, $tahunSelesai) }}
                            @elseif($tahunMulai)
                                Mulai Tahun {{ $tahunMulai }}
                            @elseif($tahunSelesai)
                                Sampai Tahun {{ $tahunSelesai }}
                            @endif
                            {{ request('bulan') ? ' • Bulan '.$bulanOptions[(int)request('bulan')] : '' }} 
                            {{ request('search') ? ' • Kata Kunci: "'.request('search').'"' : '' }}
                            @if(request('unit_kerja_id'))
                                • Unit: {{ $unitKerjaList->firstWhere('id', request('unit_kerja_id'))?->nama }}
                            @endif
                        </span>
                        <span class="text-slate-400">({{ $pensiunList->total() }} pegawai ditemukan)</span>
                    @else
                        <span>Menampilkan seluruh daftar rekapitulasi proyeksi pensiun pegawai instansi.</span>
                    @endif
                </div>
            </div>
        </form>
    </div>

    <!-- Data Table -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse min-w-[800px]">
                <thead>
                    <tr class="bg-slate-100 border-b border-slate-200 text-slate-700 font-semibold uppercase text-[11px]">
                        <th class="py-3 px-4" width="40">No</th>
                        <th class="py-3 px-4">Nama Pegawai & NIP</th>
                        <th class="py-3 px-4">Unit Kerja & Jabatan</th>
                        <th class="py-3 px-4 text-center">Tgl Lahir (NIP)</th>
                        <th class="py-3 px-4 text-center">BUP</th>
                        <th class="py-3 px-4 text-center">TMT Pensiun</th>
                        <th class="py-3 px-4 text-center">Sisa Masa Kerja</th>
                        <th class="py-3 px-4 text-center" width="100">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    @forelse($pensiunList as $idx => $p)
                    <tr class="hover:bg-slate-50/80 transition">
                        <td class="py-3 px-4 text-slate-400 font-mono text-center">
                            {{ ($pensiunList->currentPage() - 1) * $pensiunList->perPage() + $idx + 1 }}
                        </td>
                        <td class="py-3 px-4">
                            <a href="{{ route('admin.pegawai.show', $p->pegawai_id) }}" class="font-bold text-slate-900 hover:text-emerald-800 hover:underline">
                                {{ $p->nama }}
                            </a>
                            <div class="text-[11px] text-slate-400 font-mono">NIP. {{ $p->nip }}</div>
                        </td>
                        <td class="py-3 px-4">
                            <div class="text-slate-800 font-medium">{{ $p->jabatan }}</div>
                            <div class="text-slate-400 text-[11px]">{{ $p->unit_kerja }}</div>
                        </td>
                        <td class="py-3 px-4 font-mono text-center text-slate-600">
                            {{ $p->tanggal_lahir ? $p->tanggal_lahir->format('d/m/Y') : '-' }}
                        </td>
                        <td class="py-3 px-4 text-center">
                            @if($p->bup === 60)
                                <span class="px-2 py-0.5 bg-emerald-100 text-emerald-800 font-bold rounded-lg text-[10px] border border-emerald-200">60 Tahun</span>
                            @else
                                <span class="px-2 py-0.5 bg-slate-100 text-slate-700 font-bold rounded-lg text-[10px]">58 Tahun</span>
                            @endif
                        </td>
                        <td class="py-3 px-4 font-mono font-bold text-center text-amber-800">
                            <span class="px-2.5 py-1 bg-amber-50 text-amber-800 border border-amber-200 rounded-lg inline-block">
                                {{ $p->tmt_pensiun->format('d/m/Y') }}
                            </span>
                        </td>
                        <td class="py-3 px-4 text-center font-semibold">
                            @if(str_contains($p->sisa_waktu, 'Purna'))
                                <span class="px-2 py-0.5 bg-slate-100 text-slate-500 rounded text-[10px]">Purna Tugas</span>
                            @elseif(str_contains($p->sisa_waktu, 'Mendesak'))
                                <span class="px-2 py-0.5 bg-rose-100 text-rose-800 border border-rose-200 rounded-lg text-[10px] font-bold animate-pulse">{{ $p->sisa_waktu }}</span>
                            @else
                                <span class="text-slate-700 text-[11px]">{{ $p->sisa_waktu }}</span>
                            @endif
                        </td>
                        <td class="py-3 px-4 text-center">
                            <a href="{{ route('admin.pegawai.show', $p->pegawai_id) }}" class="px-2.5 py-1 bg-emerald-50 hover:bg-emerald-100 text-emerald-800 font-semibold rounded-lg text-[11px] transition inline-block">
                                Profil &rarr;
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="py-12 text-center text-slate-500">
                            <div class="flex flex-col items-center justify-center space-y-2">
                                <div class="w-12 h-12 rounded-full bg-slate-100 flex items-center justify-center text-slate-400">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                </div>
                                <div class="font-semibold text-slate-700 text-sm">Tidak Ada Pegawai Yang Pensiun Pada Periode Terpilih</div>
                                <div class="text-xs text-slate-400">Coba ubah filter bulan atau rentang tahun untuk melihat rekapitulasi periode lain.</div>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($pensiunList->hasPages())
        <div class="p-4 border-t border-slate-200 bg-slate-50">
            {{ $pensiunList->links() }}
        </div>
        @endif
    </div>
</div>

<script>
    function setRentang(mulai, selesai) {
        document.getElementById('tahun_mulai').value = mulai;
        document.getElementById('tahun_selesai').value = selesai;
        document.getElementById('tahun_mulai').closest('form').submit();
    }
</script>
@endsection
