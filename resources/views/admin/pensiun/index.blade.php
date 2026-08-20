@extends('layouts.admin')

@section('title', 'Kelola Pensiun - Admin SIMPEG')

@section('content')
<div class="space-y-6 text-xs">
    <!-- Header & Actions -->
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <div>
            <h2 class="text-xl font-bold text-slate-900">Kelola Data & Rekap Pensiun</h2>
            <p class="text-slate-500">Monitoring jadwal batas usia pensiun (BUP), rekapitulasi bulanan, dan pengajuan masa purna tugas.</p>
        </div>
        <div class="flex gap-2">
            <button onclick="window.print()" class="px-3.5 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold text-xs rounded-xl border border-slate-200 shadow-2xs transition flex items-center space-x-1.5">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                <span>Cetak Rekap</span>
            </button>
            <a href="{{ route('admin.pensiun.create') }}" class="px-4 py-2 bg-emerald-800 hover:bg-emerald-900 text-white font-semibold text-xs rounded-xl shadow-sm transition flex items-center space-x-1.5">
                <span>+ Catat Usulan Pensiun</span>
            </a>
        </div>
    </div>

    <!-- 3 Stat Highlight Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
        <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-2xs flex items-center space-x-3.5">
            <div class="w-10 h-10 rounded-xl bg-slate-100 text-slate-700 flex items-center justify-center font-bold text-sm">
                📋
            </div>
            <div>
                <div class="text-[11px] font-semibold text-slate-500 uppercase tracking-wider">Total Usulan Terdata</div>
                <div class="text-xl font-extrabold text-slate-900">{{ number_format($totalPensiun) }} <span class="text-xs font-normal text-slate-500">Berkas</span></div>
            </div>
        </div>

        <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-2xs flex items-center space-x-3.5">
            <div class="w-10 h-10 rounded-xl bg-amber-100 text-amber-800 flex items-center justify-center font-bold text-sm">
                📅
            </div>
            <div>
                <div class="text-[11px] font-semibold text-amber-700 uppercase tracking-wider">Pensiun Tahun {{ date('Y') }}</div>
                <div class="text-xl font-extrabold text-amber-900">{{ number_format($pensiunTahunIni) }} <span class="text-xs font-normal text-slate-500">Pegawai</span></div>
            </div>
        </div>

        <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-2xs flex items-center space-x-3.5">
            <div class="w-10 h-10 rounded-xl bg-emerald-100 text-emerald-800 flex items-center justify-center font-bold text-sm">
                🌴
            </div>
            <div>
                <div class="text-[11px] font-semibold text-emerald-700 uppercase tracking-wider">Masa Purna Mendatang</div>
                <div class="text-xl font-extrabold text-emerald-900">{{ number_format($pensiunMendatang) }} <span class="text-xs font-normal text-slate-500">Personel</span></div>
            </div>
        </div>
    </div>

    <!-- Filter Card: Bulan, Tahun, Unit Kerja, & Search -->
    <div class="bg-white p-5 rounded-2xl border border-slate-200/90 shadow-sm">
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
                           placeholder="Cari Nama Pegawai atau NIP..." 
                           class="w-full text-xs pl-10 pr-3.5 py-2.5 rounded-xl border-slate-300 focus:border-emerald-700 focus:ring-2 focus:ring-emerald-700/20 bg-slate-50/50 shadow-2xs transition">
                </div>

                <!-- Filter Bulan -->
                <div class="md:col-span-3">
                    <select name="bulan" class="w-full text-xs py-2.5 px-3 rounded-xl border-slate-300 focus:border-emerald-700 focus:ring-2 focus:ring-emerald-700/20 bg-white shadow-2xs transition">
                        <option value="">-- Semua Bulan Pensiun --</option>
                        @foreach($bulanOptions as $num => $namaBulan)
                            <option value="{{ $num }}" {{ request('bulan') == $num ? 'selected' : '' }}>
                                Bulan {{ $namaBulan }}
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

            <div class="flex items-center justify-between pt-1 border-t border-slate-100">
                <div class="text-[11px] text-slate-500">
                    @if(request()->filled('bulan') || request()->filled('tahun'))
                        <span class="font-semibold text-emerald-800">
                            🔍 Filter Aktif: 
                            {{ request('bulan') ? 'Bulan '.$bulanOptions[(int)request('bulan')] : '' }} 
                            {{ request('tahun') ? 'Tahun '.request('tahun') : '' }}
                        </span>
                    @else
                        <span>Menampilkan seluruh jadwal pensiun terdaftar.</span>
                    @endif
                </div>
                <div class="flex items-center gap-2">
                    <button type="submit" class="py-2 px-4 bg-emerald-800 hover:bg-emerald-900 text-white font-bold text-xs rounded-xl shadow-sm transition flex items-center space-x-1.5">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/></svg>
                        <span>Filter & Rekap</span>
                    </button>
                    @if(request()->hasAny(['search', 'bulan', 'tahun', 'unit_kerja_id']))
                        <a href="{{ route('admin.pensiun.index') }}" title="Reset Filter" class="py-2 px-3 bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold text-xs rounded-xl border border-slate-200 transition">
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
            <table class="w-full text-left border-collapse min-w-[700px]">
                <thead>
                    <tr class="bg-slate-100 border-b border-slate-200 text-slate-700 font-semibold uppercase text-[11px]">
                        <th class="py-3 px-4">Nama Pegawai & NIP</th>
                        <th class="py-3 px-4">Unit Kerja & Jabatan</th>
                        <th class="py-3 px-4">BUP</th>
                        <th class="py-3 px-4">Tgl Pengajuan</th>
                        <th class="py-3 px-4">TMT Pensiun</th>
                        <th class="py-3 px-4">Keterangan</th>
                        <th class="py-3 px-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    @forelse($pensiunList as $p)
                    <tr class="hover:bg-slate-50/80 transition">
                        <td class="py-3 px-4">
                            <a href="{{ route('admin.pegawai.show', $p->pegawai_id) }}" class="font-bold text-slate-900 hover:text-emerald-800 hover:underline">
                                {{ $p->pegawai?->nama }}
                            </a>
                            <div class="text-[11px] text-slate-400 font-mono">NIP. {{ $p->pegawai?->nip ?? '-' }}</div>
                        </td>
                        <td class="py-3 px-4">
                            <div class="text-slate-800 font-medium">{{ $p->pegawai?->formasiJabatan?->nama_jabatan ?? $p->pegawai?->jabatan ?? '-' }}</div>
                            <div class="text-slate-400 text-[11px]">{{ $p->pegawai?->unitKerja?->nama ?? '-' }}</div>
                        </td>
                        <td class="py-3 px-4 font-mono font-bold text-slate-700">
                            <span class="px-2 py-0.5 bg-slate-100 rounded text-[11px]">{{ $p->pegawai?->batas_usia_pensiun ?? 58 }} Thn</span>
                        </td>
                        <td class="py-3 px-4 font-mono text-slate-500">{{ $p->tanggal_pengajuan?->format('d/m/Y') ?? '-' }}</td>
                        <td class="py-3 px-4 font-mono font-bold text-amber-800">
                            <span class="px-2.5 py-1 bg-amber-50 text-amber-800 border border-amber-200 rounded-lg font-semibold inline-block">
                                {{ $p->tmt_pensiun?->format('d/m/Y') ?? '-' }}
                            </span>
                        </td>
                        <td class="py-3 px-4 text-slate-600">{{ $p->keterangan ?? 'Masa Purna Tugas (BUP)' }}</td>
                        <td class="py-3 px-4 text-center">
                            <form method="POST" action="{{ route('admin.pensiun.destroy', $p->id) }}" onsubmit="return confirm('Hapus data pengajuan pensiun ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="px-2.5 py-1 bg-rose-50 hover:bg-rose-100 text-rose-700 font-semibold rounded-lg transition text-[11px]">Hapus</button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="py-12 text-center text-slate-500">
                            <div class="flex flex-col items-center justify-center space-y-2">
                                <div class="w-12 h-12 rounded-full bg-slate-100 flex items-center justify-center text-slate-400 text-xl">
                                    🌴
                                </div>
                                <div class="font-semibold text-slate-700 text-sm">Tidak Ada Data Pensiun Pada Periode Ini</div>
                                <div class="text-xs text-slate-400">Coba ubah filter bulan / tahun atau klik "+ Catat Usulan Pensiun" untuk menambahkan data.</div>
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