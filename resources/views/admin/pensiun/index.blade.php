@extends('layouts.admin')

@section('title', 'Kelola Pensiun - Admin SIMPEG')

@section('content')
<div class="space-y-6 text-xs">
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <div>
            <h2 class="text-xl font-bold text-slate-900">Kelola Data & Usulan Pensiun</h2>
            <p class="text-slate-500">Monitoring jadwal batas usia pensiun (BUP) dan pengajuan pensiun dini pegawai.</p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('admin.pensiun.create') }}" class="px-4 py-2 bg-emerald-800 hover:bg-emerald-900 text-white font-semibold text-xs rounded-xl shadow-sm transition flex items-center space-x-1.5">
                <span>+ Catat Usulan Pensiun</span>
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
                            <div class="font-bold text-slate-900">{{ $p->pegawai?->nama }}</div>
                            <div class="text-[11px] text-slate-400 font-mono">NIP. {{ $p->pegawai?->nip ?? '-' }}</div>
                        </td>
                        <td class="py-3 px-4">
                            <div class="text-slate-800 font-medium">{{ $p->pegawai?->unitKerja?->nama ?? '-' }}</div>
                            <div class="text-slate-400 text-[11px]">{{ $p->pegawai?->bidang?->nama ?? '-' }}</div>
                        </td>
                        <td class="py-3 px-4 font-mono text-slate-500">{{ $p->tanggal_pengajuan?->format('d/m/Y') ?? '-' }}</td>
                        <td class="py-3 px-4 font-mono font-bold text-amber-800">
                            <span class="px-2.5 py-0.5 bg-amber-50 text-amber-800 border border-amber-200 rounded font-semibold">{{ $p->tmt_pensiun?->format('d/m/Y') ?? '-' }}</span>
                        </td>
                        <td class="py-3 px-4 text-slate-600">{{ $p->keterangan ?? 'Pensiun BUP' }}</td>
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
                        <td colspan="6" class="py-12 text-center text-slate-500">
                            <div class="flex flex-col items-center justify-center space-y-2">
                                <div class="w-12 h-12 rounded-full bg-slate-100 flex items-center justify-center text-slate-400 text-xl">
                                    🌴
                                </div>
                                <div class="font-semibold text-slate-700 text-sm">Belum Ada Data Usulan Pensiun</div>
                                <div class="text-xs text-slate-400">Klik tombol "+ Catat Usulan Pensiun" untuk menambahkan jadwal masa purna tugas pegawai.</div>
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