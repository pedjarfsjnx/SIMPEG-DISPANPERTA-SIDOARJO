@extends('layouts.admin')

@section('title', 'Kelola Kenaikan Pangkat - Admin SIMPEG')

@section('content')
<div class="space-y-6 text-xs">
    <div>
        <h2 class="text-xl font-bold text-slate-900">Kelola Usulan Kenaikan Pangkat</h2>
        <p class="text-slate-500">Daftar usulan kenaikan pangkat pegawai periode mendatang.</p>
    </div>

    <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-slate-100 border-b text-slate-600 font-semibold uppercase">
                    <th class="py-3 px-4">Nama Pegawai</th>
                    <th class="py-3 px-4">Golongan Lama</th>
                    <th class="py-3 px-4">Golongan Baru Usulan</th>
                    <th class="py-3 px-4">TMT Diusulkan</th>
                    <th class="py-3 px-4 text-center">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-200">
                @forelse($kpList as $kp)
                <tr class="hover:bg-slate-50">
                    <td class="py-3 px-4 font-bold text-slate-900">{{ $kp->pegawai?->nama }}</td>
                    <td class="py-3 px-4 font-mono text-slate-500">{{ $kp->golongan_lama ?? '-' }}</td>
                    <td class="py-3 px-4 font-mono font-bold text-emerald-800">{{ $kp->golongan_baru ?? '-' }}</td>
                    <td class="py-3 px-4 font-mono font-bold text-emerald-700">{{ $kp->tmt_diusulkan?->format('d-m-Y') ?? '-' }}</td>
                    <td class="py-3 px-4 text-center">
                        <form method="POST" action="{{ route('admin.kenaikan-pangkat.destroy', $kp->id) }}" onsubmit="return confirm('Hapus data usulan kenaikan pangkat ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="px-2.5 py-1 bg-rose-100 text-rose-800 font-semibold rounded hover:bg-rose-200">Hapus</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="py-6 text-center text-slate-500">Belum ada data usulan kenaikan pangkat.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
