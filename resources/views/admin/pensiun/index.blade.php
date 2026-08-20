@extends('layouts.admin')

@section('title', 'Rekapitulasi Pensiun Pegawai - Admin SIMPEG')

@section('content')
<div class="space-y-6 text-xs">
    <!-- Header & Actions -->
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <div>
            <div class="flex items-center space-x-2">
                <span class="px-2.5 py-0.5 bg-emerald-100 text-emerald-800 font-bold rounded-lg text-[10px] uppercase tracking-wider">Modul Purna Tugas</span>
                <span class="text-slate-400">&bull;</span>
                <span class="text-xs text-slate-500 font-medium">BUP Otomatis NIP & Rekapitulasi</span>
            </div>
            <h2 class="text-2xl font-extrabold text-slate-900 tracking-tight mt-1">Rekapitulasi Batas Usia Pensiun (BUP) Pegawai</h2>
            <p class="text-sm text-slate-500">Perhitungan otomatis TMT pensiun berdasarkan tanggal lahir NIP (BUP 60 Thn untuk Kepala & Fungsional, 58 Thn untuk Pelaksana).</p>
        </div>
        <div class="flex gap-2">
            <button onclick="window.print()" class="px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-bold text-xs rounded-xl border border-slate-200 shadow-2xs transition flex items-center space-x-2">
                <svg class="w-4 h-4 text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                <span>Cetak Rekap Pensiun</span>
            </button>
            <a href="{{ route('admin.pensiun.create') }}" class="px-4 py-2.5 bg-emerald-800 hover:bg-emerald-900 text-white font-bold text-xs rounded-xl shadow-sm transition flex items-center space-x-1.5">
                <span>+ Catat Berkas Pensiun</span>
            </a>
        </div>
    </div>

    <!-- 4 Highlight Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-2xs flex items-center space-x-3.5">
            <div class="w-10 h-10 rounded-xl bg-slate-100 text-slate-700 flex items-center justify-center">
    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
</div>
            <div>
                <div class="text-[10px] font-bold text-slate-500 uppercase tracking-wider">Total Pegawai NIP</div>
                <div class="text-2xl font-extrabold text-slate-900">{{ number_format($totalPNS) }} <span class="text-xs font-normal text-slate-500">Personel</span></div>
            </div>
        </div>

        <a href="{{ route('admin.pensiun.index', ['tahun' => date('Y')]) }}" class="bg-white p-5 rounded-2xl border border-slate-200 shadow-2xs border-l-4 border-l-amber-500 flex items-center space-x-3.5 hover:shadow-md transition">
            <div class="w-10 h-10 rounded-xl bg-amber-100 text-amber-800 flex items-center justify-center">
    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
</div>
            <div>
                <div class="text-[10px] font-bold text-amber-700 uppercase tracking-wider">Pensiun Tahun {{ date('Y') }}</div>
                <div class="text-2xl font-extrabold text-amber-900">{{ number_format($pensiunTahunIni) }} <span class="text-xs font-normal text-slate-500">Pegawai</span></div>
            </div>
        </a>

        <a href="{{ route('admin.pensiun.index', ['tahun' => date('Y') + 1]) }}" class="bg-white p-5 rounded-2xl border border-slate-200 shadow-2xs border-l-4 border-l-emerald-700 flex items-center space-x-3.5 hover:shadow-md transition">
            <div class="w-10 h-10 rounded-xl bg-emerald-100 text-emerald-800 flex items-center justify-center">
    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
</div>
            <div>
                <div class="text-[10px] font-bold text-emerald-700 uppercase tracking-wider">Pensiun Tahun {{ date('Y') + 1 }}</div>
                <div class="text-2xl font-extrabold text-emerald-900">{{ number_format($pensiunTahunDepan) }} <span class="text-xs font-normal text-slate-500">Pegawai</span></div>
            </div>
        </a>

        <div class="bg-white p-5 rounded-2xl border border-slate-200 shadow-2xs border-l-4 border-l-sky-600 flex items-center space-x-3.5">
            <div class="w-10 h-10 rounded-xl bg-sky-100 text-sky-800 flex items-center justify-center">
    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
</div>
            <div>
                <div class="text-[10px] font-bold text-sky-700 uppercase tracking-wider">Pensiun 5 Thn Ke Depan</div>
                <div class="text-2xl font-extrabold text-sky-900">{{ number_format($pensiun5Tahun) }} <span class="text-xs font-normal text-slate-500">Pegawai</span></div>
            </div>
        </div>
    </div>

    <!-- Filter Card: Bulan, Tahun, Unit Kerja, & Search -->
    <div class="bg-white p-5 rounded-2xl border border-slate-200/90 shadow-sm space-y-3">
        <form method="GET" action="{{ route('admin.pensiun.index') }}" class="space-y-3">
            <div class="grid grid-cols-1 md:grid-cols-12 gap-3 items-center">
                <!-- Search -->
                <div class="md:col-span-4 relative">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    </div>
                    <input type="text" 
                           name="search" 
                           value="{{ request('search') }}" 
                           placeholder="Cari Nama Pegawai atau 18 digit NIP..." 
                           class="w-full text-xs pl-10 pr-3.5 py-2.5 rounded-xl border-slate-300 focus:border-emerald-700 focus:ring-2 focus:ring-emerald-700/20 bg-slate-50/50 shadow-2xs transition">
                </div>

                <!-- Filter Bulan -->
                <div class="md:col-span-3">
                    <select name="bulan" class="w-full text-xs py-2.5 px-3 rounded-xl border-slate-300 focus:border-emerald-700 focus:ring-2 focus:ring-emerald-700/20 bg-white shadow-2xs transition">
                        <option value="">-- Semua Bulan Pensiun --</option>
                        @foreach($bulanOptions as $num => $namaBulan)
                            <option value="{{ $num }}" {{ request('bulan') == $num ? 'selected' : '' }}>
                                Bulan {{ $namaBulan }} (Bulan {{ str_pad($num, 2, '0', STR_PAD_LEFT) }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Filter Tahun -->
                <div class="md:col-span-2">
                    <select name="tahun" class="w-full text-xs py-2.5 px-3 rounded-xl border-slate-300 focus:border-emerald-700 focus:ring-2 focus:ring-emerald-700/20 bg-white shadow-2xs transition">
                        <option value="">-- Semua Tahun --</option>
                        @foreach($tahunOptions as $th)
                            <option value="{{ $th }}" {{ request('tahun') == $th ? 'selected' : '' }}>
                                Tahun {{ $th }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Filter Unit Kerja -->
                <div class="md:col-span-3">
                    <select name="unit_kerja_id" class="w-full text-xs py-2.5 px-3 rounded-xl border-slate-300 focus:border-emerald-700 focus:ring-2 focus:ring-emerald-700/20 bg-white shadow-2xs transition">
                        <option value="">-- Semua Unit Kerja --</option>
                        @foreach($unitKerjaList as $unit)
                            <option value="{{ $unit->id }}" {{ request('unit_kerja_id') == $unit->id ? 'selected' : '' }}>
                                {{ $unit->nama }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pt-2 border-t border-slate-100">
                <div class="text-[11px] text-slate-600">
                    @if(request()->filled('bulan') || request()->filled('tahun') || request()->filled('search') || request()->filled('unit_kerja_id'))
                        <span class="font-bold text-emerald-800">
                            Filter Aktif: 
                            {{ request('bulan') ? 'Bulan '.$bulanOptions[(int)request('bulan')] : '' }} 
                            {{ request('tahun') ? 'Tahun '.request('tahun') : '' }}
                            {{ request('search') ? 'Kata Kunci: "'.request('search').'"' : '' }}
                        </span>
                        <span class="text-slate-400">({{ $pensiunList->total() }} pegawai ditemukan)</span>
                    @else
                        <span>Menampilkan seluruh daftar rekapitulasi proyeksi pensiun pegawai instansi.</span>
                    @endif
                </div>

                <div class="flex items-center gap-2">
                    <button type="submit" class="py-2.5 px-5 bg-emerald-800 hover:bg-emerald-900 text-white font-bold text-xs rounded-xl shadow-sm transition flex items-center space-x-1.5">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/></svg>
                        <span>Terapkan Rekap</span>
                    </button>
                    @if(request()->hasAny(['search', 'bulan', 'tahun', 'unit_kerja_id', 'kategori_id']))
                        <a href="{{ route('admin.pensiun.index') }}" title="Reset Filter" class="py-2.5 px-3 bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold text-xs rounded-xl border border-slate-200 transition">
                            Reset
                        </a>
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
                                <div class="text-xs text-slate-400">Coba ubah filter bulan atau tahun untuk melihat rekapitulasi periode lain.</div>
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
@endsection