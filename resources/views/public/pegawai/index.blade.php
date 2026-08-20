@extends('layouts.public')

@section('title', 'Direktori Pegawai - Dinas Pangan dan Pertanian Kabupaten Sidoarjo')

@section('content')
<div class="space-y-5">
    <!-- Filter Header -->
    <div class="bg-white p-5 rounded-lg border border-slate-200 shadow-sm space-y-4">
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-2 border-b border-slate-100 pb-3">
            <div>
                <h2 class="text-lg font-bold text-slate-900">Direktori Kepegawaian</h2>
                <p class="text-xs text-slate-500">Pencarian, penyaringan Eselon / Kelas Jabatan, dan data pegawai resmi Dinas Pangan dan Pertanian Kabupaten Sidoarjo.</p>
            </div>
            <div class="flex items-center gap-2">
                <a href="{{ route('public.pegawai.download-pdf', request()->query()) }}" class="px-3 py-1.5 bg-emerald-800 hover:bg-emerald-900 text-white font-medium text-xs rounded transition shadow-sm">
                    Download PDF
                </a>
                <a href="{{ route('public.pegawai.cetak', request()->query()) }}" target="_blank" class="px-3 py-1.5 bg-slate-800 hover:bg-slate-900 text-white font-medium text-xs rounded transition shadow-sm">
                    Cetak Laporan
                </a>
            </div>
        </div>

            <!-- Search & Filter Card -->
    <div class="bg-white p-5 rounded-2xl border border-slate-200/90 shadow-sm">
        <form method="GET" action="{{ route('public.pegawai.index') }}" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-12 gap-3 items-center">
            <!-- Search -->
            <div class="md:col-span-4 relative">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </div>
                <input type="text" 
                       name="search" 
                       value="{{ request('search') }}" 
                       placeholder="Cari Nama Lengkap atau NIP (18 digit)..." 
                       class="w-full text-xs pl-10 pr-3.5 py-2.5 rounded-xl border-slate-300 focus:border-emerald-700 focus:ring-2 focus:ring-emerald-700/20 bg-slate-50/50 shadow-2xs transition">
            </div>

            <!-- Kategori -->
            <div class="md:col-span-2">
                <select name="kategori_id" class="w-full text-xs py-2.5 px-3 rounded-xl border-slate-300 focus:border-emerald-700 focus:ring-2 focus:ring-emerald-700/20 bg-white shadow-2xs transition">
                    <option value="">-- Kategori --</option>
                    @foreach($kategoriOptions as $kat)
                        <option value="{{ $kat->id }}" {{ request('kategori_id') == $kat->id ? 'selected' : '' }}>
                            {{ $kat->nama }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Eselon / Kelas -->
            <div class="md:col-span-3">
                <select name="kelas_jabatan" class="w-full text-xs py-2.5 px-3 rounded-xl border-slate-300 focus:border-emerald-700 focus:ring-2 focus:ring-emerald-700/20 bg-white shadow-2xs transition">
                    <option value="">-- Semua Eselon / Kelas --</option>
                    <option value="eselon_2" {{ request('kelas_jabatan') == 'eselon_2' ? 'selected' : '' }}>Eselon II.b (Kadis)</option>
                    <option value="eselon_3a" {{ request('kelas_jabatan') == 'eselon_3a' ? 'selected' : '' }}>Eselon III.a (Sekdin & Kabid)</option>
                    <option value="eselon_3b" {{ request('kelas_jabatan') == 'eselon_3b' ? 'selected' : '' }}>Eselon III.b (Kasubbag / UPTD)</option>
                    <option value="fungsional" {{ request('kelas_jabatan') == 'fungsional' ? 'selected' : '' }}>Fungsional JFT</option>
                    <option value="pelaksana" {{ request('kelas_jabatan') == 'pelaksana' ? 'selected' : '' }}>Pelaksana JFU</option>
                </select>
            </div>

            <!-- Unit Kerja -->
            <div class="md:col-span-3">
                <select name="unit_kerja_id" class="w-full text-xs py-2.5 px-3 rounded-xl border-slate-300 focus:border-emerald-700 focus:ring-2 focus:ring-emerald-700/20 bg-white shadow-2xs transition">
                    <option value="">-- Semua Unit Kerja --</option>
                    @foreach($unitKerjaOptions as $unit)
                        <option value="{{ $unit->id }}" {{ request('unit_kerja_id') == $unit->id ? 'selected' : '' }}>
                            {{ $unit->nama }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Action Buttons Row -->
            <div class="md:col-span-12 flex justify-end gap-2 pt-1 border-t border-slate-100">
                <button type="submit" class="py-2 px-5 bg-emerald-800 hover:bg-emerald-900 text-white font-bold text-xs rounded-xl shadow-sm transition flex items-center space-x-1.5">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    <span>Cari Pegawai</span>
                </button>
                @if(request()->hasAny(['search', 'kategori_id', 'kelas_jabatan', 'unit_kerja_id']))
                <a href="{{ route('public.pegawai.index') }}" class="py-2 px-4 bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold text-xs rounded-xl border border-slate-200 transition">
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
                        <th class="py-3 px-4">Kategori & Eselon</th>
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
                            @if($peg->formasiJabatan?->kelas_jabatan)
                            <div class="text-amber-700 font-semibold text-[10px] mt-1">
                                {{ $peg->formasiJabatan?->eselon_label }}
                            </div>
                            @endif
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
                            <a href="{{ route('public.pegawai.show', $peg->id) }}" class="inline-flex items-center px-3 py-1.5 bg-emerald-800 hover:bg-emerald-900 text-white font-bold text-xs rounded-lg shadow-2xs transition">
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
