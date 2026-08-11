@extends('layouts.public')

@section('title', 'Direktori Pegawai - Dinas Pangan dan Pertanian Kabupaten Sidoarjo')

@section('content')
<div class="space-y-5">
    <!-- Filter Header -->
    <div class="bg-white p-5 rounded-lg border border-slate-200 shadow-sm space-y-4">
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-2 border-b border-slate-100 pb-3">
            <div>
                <h2 class="text-lg font-bold text-slate-900">Direktori Kepegawaian</h2>
                <p class="text-xs text-slate-500">Pencarian dan penyaringan data pegawai resmi Dinas Pangan dan Pertanian Kabupaten Sidoarjo.</p>
            </div>
            <a href="{{ route('public.pegawai.cetak') }}" target="_blank" class="px-3 py-1.5 bg-slate-800 hover:bg-slate-900 text-white font-medium text-xs rounded transition shadow-sm">
                Cetak Laporan
            </a>
        </div>

        <form method="GET" action="{{ route('public.pegawai.index') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3">
            <div class="lg:col-span-2">
                <input type="text" 
                       name="search" 
                       value="{{ request('search') }}" 
                       placeholder="Cari nama atau NIP pegawai..." 
                       class="w-full text-xs rounded border-slate-300 focus:border-emerald-700 focus:ring-emerald-700">
            </div>

            <div>
                <select name="kategori_id" class="w-full text-xs rounded border-slate-300 focus:border-emerald-700 focus:ring-emerald-700">
                    <option value="">Semua Kategori</option>
                    @foreach($kategoriOptions as $kat)
                        <option value="{{ $kat->id }}" {{ request('kategori_id') == $kat->id ? 'selected' : '' }}>
                            {{ $kat->nama }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <select name="unit_kerja_id" class="w-full text-xs rounded border-slate-300 focus:border-emerald-700 focus:ring-emerald-700">
                    <option value="">Semua Unit Kerja</option>
                    @foreach($unitKerjaOptions as $unit)
                        <option value="{{ $unit->id }}" {{ request('unit_kerja_id') == $unit->id ? 'selected' : '' }}>
                            {{ $unit->nama }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="flex items-center gap-2">
                <button type="submit" class="w-full py-2 px-3 bg-emerald-800 hover:bg-emerald-900 text-white font-medium text-xs rounded transition">
                    Cari Data
                </button>
                @if(request()->hasAny(['search', 'kategori_id', 'unit_kerja_id']))
                <a href="{{ route('public.pegawai.index') }}" class="py-2 px-3 bg-slate-200 hover:bg-slate-300 text-slate-700 text-xs rounded transition">
                    Reset
                </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Data Table -->
    <div class="bg-white rounded-lg border border-slate-200 shadow-sm overflow-hidden text-xs">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-100 border-b border-slate-200 text-slate-700 font-semibold uppercase tracking-wider text-[11px]">
                        <th class="py-3 px-4">Nama & NIP/NIK</th>
                        <th class="py-3 px-4">Kategori</th>
                        <th class="py-3 px-4">Unit Kerja & Bidang</th>
                        <th class="py-3 px-4">Jabatan / Kebutuhan</th>
                        <th class="py-3 px-4">Golongan</th>
                        <th class="py-3 px-4 text-center">Detail</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    @forelse($pegawaiList as $peg)
                    <tr class="hover:bg-slate-50 transition">
                        <td class="py-3 px-4">
                            <div class="font-bold text-slate-900 text-sm">{{ $peg->nama }}</div>
                            <div class="text-slate-500 font-mono mt-0.5 text-[11px]">
                                {{ $peg->nip ? 'NIP. '.$peg->nip : ($peg->nik ? 'NIK. '.$peg->nik : '-') }}
                            </div>
                        </td>
                        <td class="py-3 px-4">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded font-semibold text-[11px] bg-emerald-100 text-emerald-800">
                                {{ $peg->kategori?->nama ?? '-' }}
                            </span>
                        </td>
                        <td class="py-3 px-4">
                            <div class="font-medium text-slate-800">{{ $peg->unitKerja?->nama ?? '-' }}</div>
                            <div class="text-slate-500 text-[11px] mt-0.5 font-medium">
                                {{ $peg->bidang?->nama ?: 'Non-Struktural (Pelaksana Unit)' }}
                            </div>
                        </td>
                        <td class="py-3 px-4 font-medium text-slate-800">
                            {{ $peg->formasiJabatan?->nama_jabatan ?? '-' }}
                        </td>
                        <td class="py-3 px-4 font-mono text-slate-700">
                            {{ $peg->golongan ?? '-' }}
                        </td>
                        <td class="py-3 px-4 text-center">
                            <a href="{{ route('public.pegawai.show', $peg->id) }}" class="inline-flex items-center px-2.5 py-1 bg-slate-100 text-slate-800 hover:bg-emerald-800 hover:text-white font-medium rounded border border-slate-300 transition text-xs">
                                Lihat &rarr;
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="py-8 text-center text-slate-500 italic">
                            Tidak ada data pegawai yang memenuhi kriteria pencarian.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="p-4 border-t border-slate-200">
            {{ $pegawaiList->links() }}
        </div>
    </div>
</div>
@endsection
