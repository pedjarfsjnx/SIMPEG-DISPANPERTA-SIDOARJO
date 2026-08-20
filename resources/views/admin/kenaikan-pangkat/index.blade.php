@extends('layouts.admin')

@section('title', 'Kelola Kenaikan Pangkat - Admin SIMPEG')

@section('content')
<div class="space-y-6 text-xs">
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <div>
            <h2 class="text-xl font-bold text-slate-900">Kelola Usulan Kenaikan Pangkat</h2>
            <p class="text-slate-500">Daftar usulan kenaikan jenjang pangkat/golongan pegawai aktif (terintegrasi validasi batas masa pensiun).</p>
        </div>
        <div class="flex gap-2">
            <button onclick="window.print()" class="px-3.5 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold text-xs rounded-xl border border-slate-200 shadow-2xs transition flex items-center space-x-1.5">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/></svg>
                <span>Cetak Usulan</span>
            </button>
            <a href="{{ route('admin.kenaikan-pangkat.create') }}" class="px-4 py-2 bg-emerald-800 hover:bg-emerald-900 text-white font-semibold text-xs rounded-xl shadow-sm transition flex items-center space-x-1.5">
                <span>+ Tambah Usulan Pangkat</span>
            </a>
        </div>
    </div>

    <!-- Filter & Search Card -->
    <div class="bg-white p-5 rounded-2xl border border-slate-200/90 shadow-sm">
        <form method="GET" action="{{ route('admin.kenaikan-pangkat.index') }}" class="flex flex-col sm:flex-row gap-3 items-center justify-between">
            <div class="relative flex-1 w-full">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </div>
                <input type="text" 
                       name="search" 
                       value="{{ request('search') }}" 
                       placeholder="Cari Nama Pegawai atau NIP yang diusulkan..." 
                       class="w-full text-xs pl-10 pr-3.5 py-2.5 rounded-xl border-slate-300 focus:border-emerald-700 focus:ring-2 focus:ring-emerald-700/20 bg-slate-50/50 shadow-2xs transition">
            </div>

            <div class="flex items-center gap-2 w-full sm:w-auto">
                <button type="submit" class="py-2.5 px-4 bg-emerald-800 hover:bg-emerald-900 text-white font-bold text-xs rounded-xl shadow-sm transition flex items-center justify-center space-x-1.5">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    <span>Cari</span>
                </button>
                @if(request()->filled('search'))
                    <a href="{{ route('admin.kenaikan-pangkat.index') }}" class="py-2.5 px-3 bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold text-xs rounded-xl border border-slate-200 transition">
                        Reset
                    </a>
                @endif
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
                        <th class="py-3 px-4">Unit Kerja & Bidang</th>
                        <th class="py-3 px-4">Golongan Lama</th>
                        <th class="py-3 px-4">Golongan Usulan</th>
                        <th class="py-3 px-4">TMT Diusulkan</th>
                        <th class="py-3 px-4">Batas Pensiun</th>
                        <th class="py-3 px-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    @forelse($kpList as $kp)
                    <tr class="hover:bg-slate-50/80 transition">
                        <td class="py-3 px-4">
                            <a href="{{ route('admin.pegawai.show', $kp->pegawai_id) }}" class="font-bold text-slate-900 hover:text-emerald-800 hover:underline">
                                {{ $kp->pegawai?->nama }}
                            </a>
                            <div class="text-[11px] text-slate-400 font-mono">NIP. {{ $kp->pegawai?->nip ?? '-' }}</div>
                        </td>
                        <td class="py-3 px-4">
                            <div class="text-slate-800 font-medium">{{ $kp->pegawai?->unitKerja?->nama ?? '-' }}</div>
                            <div class="text-slate-400 text-[11px]">{{ $kp->pegawai?->bidang?->nama ?? '-' }}</div>
                        </td>
                        <td class="py-3 px-4 font-mono text-slate-500">{{ $kp->golongan_lama ?? '-' }}</td>
                        <td class="py-3 px-4 font-mono font-bold text-emerald-800">
                            <span class="px-2.5 py-1 bg-emerald-50 text-emerald-800 border border-emerald-200 rounded-lg font-semibold inline-block">
                                {{ $kp->golongan_baru ?? '-' }}
                            </span>
                        </td>
                        <td class="py-3 px-4 font-mono font-bold text-slate-700">{{ $kp->tmt_diusulkan?->format('d/m/Y') ?? '-' }}</td>
                        <td class="py-3 px-4 font-mono text-[11px] text-slate-500">
                            {{ $kp->pegawai?->estimasi_pensiun['tanggal'] ? $kp->pegawai->estimasi_pensiun['tanggal']->format('d/m/Y') : '-' }}
                        </td>
                        <td class="py-3 px-4 text-center">
                            <form method="POST" action="{{ route('admin.kenaikan-pangkat.destroy', $kp->id) }}" onsubmit="return confirm('Hapus data usulan kenaikan pangkat ini?')">
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
                                    📄
                                </div>
                                <div class="font-semibold text-slate-700 text-sm">Belum Ada Usulan Kenaikan Pangkat</div>
                                <div class="text-xs text-slate-400">Klik tombol "+ Tambah Usulan Pangkat" untuk mendaftarkan data baru.</div>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($kpList->hasPages())
        <div class="p-4 border-t border-slate-200 bg-slate-50">
            {{ $kpList->links() }}
        </div>
        @endif
    </div>
</div>
@endsection