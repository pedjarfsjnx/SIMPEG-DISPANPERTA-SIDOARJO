@extends('layouts.admin')

@section('title', 'Kelola Kenaikan Pangkat - Admin SIMPEG')

@section('content')
<div class="space-y-6 text-xs">
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <div>
            <h2 class="text-xl font-bold text-slate-900">Kelola Usulan Kenaikan Pangkat</h2>
            <p class="text-slate-500">Daftar usulan kenaikan pangkat pegawai periode mendatang.</p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('admin.kenaikan-pangkat.create') }}" class="px-4 py-2 bg-emerald-800 hover:bg-emerald-900 text-white font-semibold text-xs rounded-xl shadow-sm transition flex items-center space-x-1.5">
                <span>+ Tambah Usulan Pangkat</span>
            </a>
        </div>
    </div>

    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse min-w-[600px]">
                <thead>
                    <tr class="bg-slate-100 border-b border-slate-200 text-slate-700 font-semibold uppercase text-[11px]">
                        <th class="py-3 px-4">Nama Pegawai & NIP</th>
                        <th class="py-3 px-4">Unit Kerja & Bidang</th>
                        <th class="py-3 px-4">Golongan Lama</th>
                        <th class="py-3 px-4">Golongan Baru Usulan</th>
                        <th class="py-3 px-4">TMT Diusulkan</th>
                        <th class="py-3 px-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    @forelse($kpList as $kp)
                    <tr class="hover:bg-slate-50/80 transition">
                        <td class="py-3 px-4">
                            <div class="font-bold text-slate-900">{{ $kp->pegawai?->nama }}</div>
                            <div class="text-[11px] text-slate-400 font-mono">NIP. {{ $kp->pegawai?->nip ?? '-' }}</div>
                        </td>
                        <td class="py-3 px-4">
                            <div class="text-slate-800 font-medium">{{ $kp->pegawai?->unitKerja?->nama ?? '-' }}</div>
                            <div class="text-slate-400 text-[11px]">{{ $kp->pegawai?->bidang?->nama ?? '-' }}</div>
                        </td>
                        <td class="py-3 px-4 font-mono text-slate-500">{{ $kp->golongan_lama ?? '-' }}</td>
                        <td class="py-3 px-4 font-mono font-bold text-emerald-800">
                            <span class="px-2 py-0.5 bg-emerald-50 text-emerald-800 border border-emerald-200 rounded font-semibold">{{ $kp->golongan_baru ?? '-' }}</span>
                        </td>
                        <td class="py-3 px-4 font-mono font-bold text-slate-700">{{ $kp->tmt_diusulkan?->format('d/m/Y') ?? '-' }}</td>
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
                        <td colspan="6" class="py-12 text-center text-slate-500">
                            <div class="flex flex-col items-center justify-center space-y-2">
                                <div class="w-12 h-12 rounded-full bg-slate-100 flex items-center justify-center text-slate-400 text-xl">
                                    📄
                                </div>
                                <div class="font-semibold text-slate-700 text-sm">Belum Ada Usulan Kenaikan Pangkat</div>
                                <div class="text-xs text-slate-400">Klik tombol "+ Tambah Usulan Pangkat" di atas untuk mendaftarkan data baru.</div>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection