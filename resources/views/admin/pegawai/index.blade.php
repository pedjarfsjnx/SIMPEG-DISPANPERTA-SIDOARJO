@extends('layouts.admin')

@section('title', 'Kelola Pegawai - Admin SIMPEG')

@section('content')
<div class="space-y-5">
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <div>
            <h2 class="text-xl font-bold text-slate-900">Kelola Data Pegawai</h2>
            <p class="text-xs text-slate-500">Manajemen data pegawai instansi (termasuk NIK, Kontak, dan Soft Delete).</p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('admin.pegawai.create') }}" class="px-4 py-2 bg-emerald-800 hover:bg-emerald-900 text-white font-semibold text-xs rounded shadow-sm">
                + Tambah Pegawai
            </a>
        </div>
    </div>

        <!-- Filters & Search Card -->
    <div class="bg-white p-5 rounded-2xl border border-slate-200/90 shadow-sm space-y-4">
        <form method="GET" action="{{ route('admin.pegawai.index') }}" class="space-y-3">
            
            <!-- Row 1: Search Bar & Primary Filters -->
            <div class="grid grid-cols-1 md:grid-cols-12 gap-3">
                <!-- Search Input with Magnifier Icon -->
                <div class="md:col-span-5 relative">
                    <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    </div>
                    <input type="text" 
                           name="search" 
                           value="{{ request('search') }}" 
                           placeholder="Cari Nama Lengkap, NIP (18 digit), atau NIK..." 
                           class="w-full text-xs pl-10 pr-3.5 py-2.5 rounded-xl border-slate-300 focus:border-emerald-700 focus:ring-2 focus:ring-emerald-700/20 bg-slate-50/50 shadow-2xs transition">
                </div>

                <!-- Kategori Pegawai -->
                <div class="md:col-span-3">
                    <select name="kategori_id" class="w-full text-xs py-2.5 px-3 rounded-xl border-slate-300 focus:border-emerald-700 focus:ring-2 focus:ring-emerald-700/20 bg-white shadow-2xs transition">
                        <option value="">-- Semua Kategori Pegawai --</option>
                        @foreach($kategoriOptions as $kat)
                            <option value="{{ $kat->id }}" {{ request('kategori_id') == $kat->id ? 'selected' : '' }}>{{ $kat->nama }}</option>
                        @endforeach
                    </select>
                </div>

                <!-- Unit Kerja -->
                <div class="md:col-span-4">
                    <select name="unit_kerja_id" class="w-full text-xs py-2.5 px-3 rounded-xl border-slate-300 focus:border-emerald-700 focus:ring-2 focus:ring-emerald-700/20 bg-white shadow-2xs transition">
                        <option value="">-- Semua Unit Kerja --</option>
                        @foreach($unitKerjaOptions as $unitKerja)
                            <option value="{{ $unitKerja->id }}" {{ request('unit_kerja_id') == $unitKerja->id ? 'selected' : '' }}>{{ $unitKerja->nama }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- Row 2: Secondary Filters & Action Buttons -->
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-12 gap-3 pt-1 border-t border-slate-100">
                <!-- Bidang / Sub-Unit -->
                <div class="md:col-span-4">
                    <select name="bidang_id" class="w-full text-xs py-2.5 px-3 rounded-xl border-slate-300 focus:border-emerald-700 focus:ring-2 focus:ring-emerald-700/20 bg-white shadow-2xs transition">
                        <option value="">-- Semua Bidang / Sub-Unit --</option>
                        @foreach($bidangOptions as $bidang)
                            <option value="{{ $bidang->id }}" {{ request('bidang_id') == $bidang->id ? 'selected' : '' }}>
                                {{ $bidang->unitKerja?->nama ? $bidang->unitKerja->nama.' — ' : '' }}{{ $bidang->nama }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <!-- Status Arsip / Soft Delete -->
                <div class="md:col-span-2">
                    <select name="trashed" class="w-full text-xs py-2.5 px-3 rounded-xl border-slate-300 focus:border-emerald-700 focus:ring-2 focus:ring-emerald-700/20 bg-white shadow-2xs transition">
                        <option value="">Data Aktif</option>
                        <option value="only" {{ request('trashed') == 'only' ? 'selected' : '' }}>Terarsip (Deleted)</option>
                    </select>
                </div>

                <!-- Sort By Dropdown -->
                <div class="md:col-span-3">
                    <select name="sort_by" class="w-full text-xs py-2.5 px-3 rounded-xl border-slate-300 focus:border-emerald-700 focus:ring-2 focus:ring-emerald-700/20 bg-white shadow-2xs transition">
                        <option value="nama_asc" {{ request('sort_by', 'nama_asc') === 'nama_asc' ? 'selected' : '' }}>Urutan: Nama (A &rarr; Z)</option>
                        <option value="nama_desc" {{ request('sort_by') === 'nama_desc' ? 'selected' : '' }}>Urutan: Nama (Z &rarr; A)</option>
                        <option value="unit_kerja_asc" {{ request('sort_by') === 'unit_kerja_asc' ? 'selected' : '' }}>Urutan: Unit Kerja (A &rarr; Z)</option>
                        <option value="unit_kerja_desc" {{ request('sort_by') === 'unit_kerja_desc' ? 'selected' : '' }}>Urutan: Unit Kerja (Z &rarr; A)</option>
                        <option value="bidang_asc" {{ request('sort_by') === 'bidang_asc' ? 'selected' : '' }}>Urutan: Bidang (A &rarr; Z)</option>
                        <option value="bidang_desc" {{ request('sort_by') === 'bidang_desc' ? 'selected' : '' }}>Urutan: Bidang (Z &rarr; A)</option>
                    </select>
                </div>

                <!-- Submit & Reset Buttons -->
                <div class="md:col-span-3 flex items-center gap-2">
                    <button type="submit" class="flex-1 py-2.5 px-4 bg-emerald-800 hover:bg-emerald-900 text-white font-bold text-xs rounded-xl shadow-sm transition flex items-center justify-center space-x-1.5">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/></svg>
                        <span>Terapkan Filter</span>
                    </button>
                    @if(request()->hasAny(['search', 'kategori_id', 'unit_kerja_id', 'bidang_id', 'trashed', 'sort_by']))
                        <a href="{{ route('admin.pegawai.index') }}" title="Reset Semua Filter" class="py-2.5 px-3 bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold text-xs rounded-xl border border-slate-200 transition flex items-center justify-center">
                            Reset
                        </a>
                    @endif
                </div>
            </div>
        </form>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-lg border border-slate-200 shadow-sm overflow-hidden text-xs">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-100 border-b border-slate-200 text-slate-700 font-semibold uppercase text-[11px]">
                        <th class="py-3 px-4">Nama & Identitas</th>
                        <th class="py-3 px-4">Kategori & Status</th>
                        <th class="py-3 px-4">Unit Kerja & Bidang</th>
                        <th class="py-3 px-4">Kontak Privasi</th>
                        <th class="py-3 px-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    @forelse($pegawaiList as $peg)
                    <tr class="hover:bg-slate-50">
                        <td class="py-3 px-4">
                            <div class="font-bold text-slate-900 text-sm">{{ $peg->nama }}</div>
                            <div class="text-slate-500 font-mono text-[11px] mt-0.5">
                                {{ $peg->nip ? 'NIP. '.$peg->nip : ($peg->nik ? 'NIK. '.$peg->nik : '-') }}
                            </div>
                        </td>
                        <td class="py-3 px-4">
                            <span class="px-2.5 py-0.5 bg-emerald-100 text-emerald-800 font-semibold rounded text-[11px]">{{ $peg->kategori?->nama }}</span>
                            <div class="text-slate-500 mt-1 font-medium text-[11px]">{{ $peg->status?->nama }}</div>
                        </td>
                        <td class="py-3 px-4">
                            <div class="font-medium text-slate-800">{{ $peg->unitKerja?->nama }}</div>
                            <div class="text-slate-500 text-[11px] font-medium">
                                {{ $peg->bidang?->nama ?: 'Non-Struktural (Pelaksana Unit)' }}
                            </div>
                        </td>
                                                <td class="py-3 px-4">
                            @if(!empty($peg->no_hp))
                                @php
                                    $cleanWa = preg_replace('/[^0-9]/', '', $peg->no_hp);
                                    if (str_starts_with($cleanWa, '0')) { $cleanWa = '62' . substr($cleanWa, 1); }
                                    elseif (str_starts_with($cleanWa, '8')) { $cleanWa = '62' . $cleanWa; }
                                @endphp
                                <div class="flex items-center space-x-1.5">
                                    <span class="text-slate-800 font-mono text-[11px]">{{ $peg->no_hp }}</span>
                                    <a href="https://wa.me/{{ $cleanWa }}" target="_blank" title="Kirim Pesan WhatsApp" class="text-emerald-600 hover:text-emerald-700 font-bold text-[10px] bg-emerald-50 hover:bg-emerald-100 px-1.5 py-0.5 rounded border border-emerald-200 transition">
                                        WA &rarr;
                                    </a>
                                </div>
                            @else
                                <div class="text-slate-400 font-mono text-[11px]">-</div>
                            @endif

                            @if(!empty($peg->email))
                                <div class="flex items-center space-x-1.5 mt-1">
                                    <span class="text-slate-500 text-[11px] truncate max-w-[160px]">{{ $peg->email }}</span>
                                    <a href="mailto:{{ $peg->email }}" title="Kirim Email" class="text-sky-600 hover:text-sky-700 font-bold text-[10px] bg-sky-50 hover:bg-sky-100 px-1.5 py-0.5 rounded border border-sky-200 transition">
                                        Mail &rarr;
                                    </a>
                                </div>
                            @endif
                        </td>
                        <td class="py-3 px-4 text-center space-x-1">
                            <a href="{{ route('admin.pegawai.show', $peg->id) }}" class="px-2.5 py-1 bg-slate-100 text-slate-700 font-semibold rounded hover:bg-slate-200 text-xs">Detail</a>
                            
                            @if($peg->trashed())
                                <form method="POST" action="{{ route('admin.pegawai.restore', $peg->id) }}" class="inline">
                                    @csrf
                                    <button type="submit" class="px-2.5 py-1 bg-amber-100 text-amber-800 font-semibold rounded hover:bg-amber-200 text-xs">Pulihkan</button>
                                </form>
                            @else
                                <a href="{{ route('admin.pegawai.edit', $peg->id) }}" class="px-2.5 py-1 bg-emerald-100 text-emerald-800 font-semibold rounded hover:bg-emerald-200 text-xs">Edit</a>
                                <form method="POST" action="{{ route('admin.pegawai.destroy', $peg->id) }}" class="inline" onsubmit="return confirm('Arsipkan data pegawai ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="px-2.5 py-1 bg-rose-100 text-rose-800 font-semibold rounded hover:bg-rose-200 text-xs">Arsip</button>
                                </form>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="py-6 text-center text-slate-500 italic">Tidak ada data pegawai.</td>
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
