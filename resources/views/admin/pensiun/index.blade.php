@extends('layouts.admin')

@section('title', 'Kelola Pensiun - Admin SIMPEG')

@section('content')
<div class="space-y-6 text-xs">
    <div>
        <h2 class="text-xl font-bold text-slate-900">Kelola Pengajuan Pensiun</h2>
        <p class="text-slate-500">Daftar usulan pensiun pegawai yang siap ditampilkan pada dashboard pengingat.</p>
    </div>

    <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse min-w-[600px]">
                <thead>
                    <tr class="bg-slate-100 border-b text-slate-600 font-semibold uppercase text-[11px]">
                        <th class="py-3 px-4">Nama Pegawai</th>
                        <th class="py-3 px-4">Tanggal Pengajuan</th>
                        <th class="py-3 px-4">TMT Pensiun</th>
                        <th class="py-3 px-4">Keterangan</th>
                        <th class="py-3 px-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    @forelse($pensiunList as $p)
                    <tr class="hover:bg-slate-50">
                        <td class="py-3 px-4 font-bold text-slate-900">{{ $p->pegawai?->nama }}</td>
                        <td class="py-3 px-4 font-mono">{{ $p->tanggal_pengajuan?->format('d-m-Y') ?? '-' }}</td>
                        <td class="py-3 px-4 font-mono font-bold text-amber-700">{{ $p->tmt_pensiun?->format('d-m-Y') ?? '-' }}</td>
                        <td class="py-3 px-4 text-slate-600">{{ $p->keterangan ?? '-' }}</td>
                        <td class="py-3 px-4 text-center">
                            <form method="POST" action="{{ route('admin.pensiun.destroy', $p->id) }}" onsubmit="return confirm('Hapus data pengajuan pensiun ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="px-2.5 py-1 bg-rose-100 text-rose-800 font-semibold rounded hover:bg-rose-200 transition text-[11px]">Hapus</button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="py-6 text-center text-slate-500">Belum ada data pengajuan pensiun.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
