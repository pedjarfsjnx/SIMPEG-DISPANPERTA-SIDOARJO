@extends('layouts.admin')

@section('title', 'Admin Dashboard - SIMPEG Dispanperta Sidoarjo')

@section('content')
<div class="space-y-6">
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <div>
            <h2 class="text-xl font-bold text-slate-900">Dashboard Pengelola Admin</h2>
            <p class="text-xs text-slate-500">Ringkasan status data kepegawaian, formasi kosong, dan usulan pensiun/KP.</p>
        </div>
        <a href="{{ route('admin.pegawai.create') }}" class="px-4 py-2 bg-emerald-800 hover:bg-emerald-900 text-white font-semibold text-xs rounded transition shadow-sm">
            + Tambah Pegawai Baru
        </a>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white p-5 rounded-lg border border-slate-200 shadow-sm">
            <div class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Total Pegawai</div>
            <div class="text-3xl font-bold text-slate-900 mt-1">{{ number_format($totalPegawai) }}</div>
            <div class="text-xs text-slate-500 mt-1">Aktif di database</div>
        </div>

        <div class="bg-white p-5 rounded-lg border border-slate-200 shadow-sm">
            <div class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Pegawai PNS</div>
            <div class="text-3xl font-bold text-emerald-800 mt-1">{{ number_format($totalPns) }}</div>
            <div class="text-xs text-emerald-600 mt-1">Aparatur Sipil Negara</div>
        </div>

        <div class="bg-white p-5 rounded-lg border border-slate-200 shadow-sm">
            <div class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Pegawai PPPK</div>
            <div class="text-3xl font-bold text-emerald-800 mt-1">{{ number_format($totalPppk) }}</div>
            <div class="text-xs text-emerald-600 mt-1">PPPK & Paruh Waktu</div>
        </div>

        <div class="bg-white p-5 rounded-lg border border-slate-200 shadow-sm">
            <div class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Formasi Kosong</div>
            <div class="text-3xl font-bold text-amber-600 mt-1">{{ number_format($formasiKosong) }}</div>
            <div class="text-xs text-amber-600 mt-1">Belum ada pejabat</div>
        </div>
    </div>

    <!-- Main Content Split -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <div class="lg:col-span-2 bg-white rounded-lg border border-slate-200 p-5 shadow-sm space-y-4">
            <div class="flex items-center justify-between border-b border-slate-100 pb-3">
                <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wider">Data Pegawai Terbaru</h3>
                <a href="{{ route('admin.pegawai.index') }}" class="text-xs text-emerald-800 font-semibold hover:underline">Lihat Semua &rarr;</a>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-xs border-collapse">
                    <thead>
                        <tr class="bg-slate-50 border-b border-slate-200 text-slate-500 font-semibold uppercase">
                            <th class="py-2.5 px-3">Nama</th>
                            <th class="py-2.5 px-3">Kategori</th>
                            <th class="py-2.5 px-3">Unit</th>
                            <th class="py-2.5 px-3 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @foreach($recentPegawai as $peg)
                        <tr>
                            <td class="py-2.5 px-3 font-semibold text-slate-900">{{ $peg->nama }}</td>
                            <td class="py-2.5 px-3"><span class="px-2 py-0.5 bg-emerald-100 text-emerald-800 rounded font-medium text-[11px]">{{ $peg->kategori?->nama }}</span></td>
                            <td class="py-2.5 px-3 text-slate-600">{{ $peg->unitKerja?->nama }}</td>
                            <td class="py-2.5 px-3 text-right">
                                <a href="{{ route('admin.pegawai.edit', $peg->id) }}" class="text-emerald-800 font-semibold hover:underline">Edit</a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="space-y-6">
            
            <div class="bg-white rounded-lg border border-slate-200 p-5 shadow-sm space-y-3">
                <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wider border-b border-slate-100 pb-2">Usulan Pensiun</h3>
                @forelse($pensiunMendatang as $pen)
                <div class="text-xs border-b border-slate-100 pb-2 last:border-b-0 last:pb-0">
                    <div class="font-bold text-slate-900">{{ $pen->pegawai?->nama }}</div>
                    <div class="text-amber-700 font-semibold mt-0.5 text-[11px]">TMT: {{ \Carbon\Carbon::parse($pen->tmt_pensiun)->format('d/m/Y') }}</div>
                </div>
                @empty
                <p class="text-xs text-slate-400 italic">Tidak ada pengajuan pensiun.</p>
                @endforelse
            </div>

            <div class="bg-white rounded-lg border border-slate-200 p-5 shadow-sm space-y-3">
                <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wider border-b border-slate-100 pb-2">Usulan Naik Pangkat</h3>
                @forelse($kpMendatang as $kp)
                <div class="text-xs border-b border-slate-100 pb-2 last:border-b-0 last:pb-0">
                    <div class="font-bold text-slate-900">{{ $kp->pegawai?->nama }}</div>
                    <div class="text-emerald-800 font-semibold mt-0.5 text-[11px]">TMT: {{ \Carbon\Carbon::parse($kp->tmt_diusulkan)->format('d/m/Y') }}</div>
                </div>
                @empty
                <p class="text-xs text-slate-400 italic">Tidak ada pengajuan KP.</p>
                @endforelse
            </div>

        </div>

    </div>
</div>
@endsection
