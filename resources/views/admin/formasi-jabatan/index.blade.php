@extends('layouts.admin')

@section('title', 'Kelola Formasi Jabatan - Admin SIMPEG')

@section('content')
<div class="space-y-6 text-xs">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-xl font-bold text-slate-900">Kelola Formasi Jabatan</h2>
            <p class="text-slate-500">Slot posisi jabatan instansi (mendukung pencatatan jabatan kosong & Plt).</p>
        </div>
    </div>

    <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-slate-100 border-b text-slate-600 font-semibold uppercase">
                    <th class="py-3 px-4">Nama Jabatan</th>
                    <th class="py-3 px-4">Unit Kerja & Bidang</th>
                    <th class="py-3 px-4">Kelas</th>
                    <th class="py-3 px-4">Status Formasi</th>
                    <th class="py-3 px-4">Pejabat Definitif Saat Ini</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-200">
                @foreach($formasiList as $f)
                <tr class="hover:bg-slate-50">
                    <td class="py-3 px-4 font-bold text-slate-900">{{ $f->nama_jabatan }}</td>
                    <td class="py-3 px-4">
                        <div class="font-medium text-slate-800">{{ $f->unitKerja?->nama }}</div>
                        <div class="text-slate-400">{{ $f->bidang?->nama ?? '-' }}</div>
                    </td>
                    <td class="py-3 px-4 font-mono">{{ $f->kelas_jabatan ?? '-' }}</td>
                    <td class="py-3 px-4">
                        @if($f->status_formasi === 'kosong')
                            <span class="px-2.5 py-0.5 bg-amber-100 text-amber-800 font-bold rounded">KOSONG / LOWONG</span>
                        @else
                            <span class="px-2.5 py-0.5 bg-emerald-100 text-emerald-800 font-semibold rounded">Terisi</span>
                        @endif
                    </td>
                    <td class="py-3 px-4">
                        @if($f->pegawai->count() > 0)
                            @foreach($f->pegawai as $p)
                                <div class="font-semibold text-slate-900">{{ $p->nama }}</div>
                            @endforeach
                        @else
                            <span class="text-slate-400 italic">Belum ada pegawai</span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
