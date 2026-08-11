@extends('layouts.public')

@section('title', 'Struktur Organisasi - Dinas Pangan dan Pertanian Kabupaten Sidoarjo')

@section('content')
<div class="space-y-6">
    <div class="bg-white p-5 rounded-lg border border-slate-200 shadow-sm flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <div>
            <h2 class="text-lg font-bold text-slate-900">Struktur Organisasi & Distribusi Unit Kerja</h2>
            <p class="text-xs text-slate-500 mt-1">Bagan hierarki Dinas Pangan dan Pertanian Kabupaten Sidoarjo beserta alokasi pegawai resmi.</p>
        </div>
        <a href="{{ route('public.pegawai.index') }}" class="px-3.5 py-2 bg-emerald-800 hover:bg-emerald-900 text-white text-xs font-semibold rounded shadow-sm">
            Lihat Direktori Pegawai &rarr;
        </a>
    </div>

    <div class="space-y-6">
        @foreach($unitKerjaWithBidang as $unit)
        <div class="bg-white rounded-lg border border-slate-200 shadow-sm overflow-hidden text-xs">
            <div class="bg-emerald-900 text-white p-4 flex flex-col sm:flex-row sm:items-center justify-between gap-2 border-b-2 border-amber-500">
                <div>
                    <span class="text-[10px] font-semibold uppercase tracking-wider text-emerald-300">[{{ $unit->tipe }}]</span>
                    <h3 class="text-base font-bold">{{ $unit->nama }}</h3>
                </div>
                <div class="flex items-center gap-2 text-xs">
                    <span class="px-2.5 py-1 bg-emerald-800 text-emerald-100 rounded font-semibold">
                        {{ $unit->pegawai->count() }} Total Pegawai
                    </span>
                </div>
            </div>

            <div class="p-5 grid grid-cols-1 md:grid-cols-2 gap-5">
                @forelse($unit->bidang as $bidang)
                <div class="bg-slate-50 border border-slate-200 rounded-lg p-4 space-y-3">
                    <div class="flex items-center justify-between border-b border-slate-200 pb-2">
                        <h4 class="font-bold text-slate-900 text-sm leading-snug">{{ $bidang->nama }}</h4>
                        <span class="text-xs text-emerald-800 font-bold bg-emerald-100 px-2 py-0.5 rounded whitespace-nowrap">
                            {{ $bidang->pegawai->count() }} Pegawai
                        </span>
                    </div>

                    @if($bidang->pegawai->count() > 0)
                    <ul class="text-xs text-slate-700 divide-y divide-slate-200 max-h-60 overflow-y-auto pr-1">
                        @foreach($bidang->pegawai as $peg)
                        <li class="py-1.5 flex justify-between items-center">
                            <div>
                                <span class="font-semibold text-slate-900 block">{{ $peg->nama }}</span>
                                <span class="text-slate-500 text-[11px] block">{{ $peg->formasiJabatan?->nama_jabatan ?: 'Staff / Pelaksana' }}</span>
                            </div>
                            <span class="text-emerald-800 bg-emerald-50 px-2 py-0.5 rounded font-mono text-[10px] font-semibold border border-emerald-200 whitespace-nowrap ml-2">
                                {{ $peg->kategori?->nama }}
                            </span>
                        </li>
                        @endforeach
                    </ul>
                    @else
                    <div class="text-xs text-slate-500 italic py-3 text-center bg-white rounded border border-dashed border-slate-300">
                        Belum ada pegawai yang dialokasikan di bidang ini.
                    </div>
                    @endif
                </div>
                @empty
                <div class="col-span-2 text-xs text-slate-600 bg-slate-50 p-4 rounded border border-slate-200">
                    <p class="font-bold text-slate-800">Unit Operasional Khusus (UPTD)</p>
                    <p class="text-slate-500 mt-0.5">Beroperasi langsung di bawah Kepala UPTD tanpa bidang struktural terpisah. Total {{ $unit->pegawai->count() }} personel aktif.</p>
                </div>
                @endforelse
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection
