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

    <!-- Filters & Search -->
    <div class="bg-white p-4 rounded-lg border border-slate-200 shadow-sm">
        <form method="GET" action="{{ route('admin.pegawai.index') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari Nama, NIP, atau NIK..." class="text-xs rounded border-slate-300">
            
            <select name="kategori_id" class="text-xs rounded border-slate-300">
                <option value="">Semua Kategori</option>
                @foreach($kategoriOptions as $kat)
                    <option value="{{ $kat->id }}" {{ request('kategori_id') == $kat->id ? 'selected' : '' }}>{{ $kat->nama }}</option>
                @endforeach
            </select>

            <select name="unit_kerja_id" class="text-xs rounded border-slate-300">
                <option value="">Semua Unit Kerja</option>
                @foreach($unitKerjaOptions as $unitKerja)
                    <option value="{{ $unitKerja->id }}" {{ request('unit_kerja_id') == $unitKerja->id ? 'selected' : '' }}>{{ $unitKerja->nama }}</option>
                @endforeach
            </select>

            <select name="bidang_id" class="text-xs rounded border-slate-300">
                <option value="">Semua Bidang</option>
                @foreach($bidangOptions as $bidang)
                    <option value="{{ $bidang->id }}" {{ request('bidang_id') == $bidang->id ? 'selected' : '' }}>
                        {{ $bidang->unitKerja?->nama ? $bidang->unitKerja->nama.' — ' : '' }}{{ $bidang->nama }}
                    </option>
                @endforeach
            </select>

            <select name="trashed" class="text-xs rounded border-slate-300">
                <option value="">Data Aktif</option>
                <option value="only" {{ request('trashed') == 'only' ? 'selected' : '' }}>Data Terarsip (Soft Deleted)</option>
            </select>

            <select name="sort_by" class="text-xs rounded border-slate-300">
                <option value="nama_asc" {{ request('sort_by', 'nama_asc') === 'nama_asc' ? 'selected' : '' }}>Urutkan: Nama A–Z</option>
                <option value="nama_desc" {{ request('sort_by') === 'nama_desc' ? 'selected' : '' }}>Urutkan: Nama Z–A</option>
                <option value="unit_kerja_asc" {{ request('sort_by') === 'unit_kerja_asc' ? 'selected' : '' }}>Urutkan: Unit Kerja A–Z</option>
                <option value="unit_kerja_desc" {{ request('sort_by') === 'unit_kerja_desc' ? 'selected' : '' }}>Urutkan: Unit Kerja Z–A</option>
                <option value="bidang_asc" {{ request('sort_by') === 'bidang_asc' ? 'selected' : '' }}>Urutkan: Bidang A–Z</option>
                <option value="bidang_desc" {{ request('sort_by') === 'bidang_desc' ? 'selected' : '' }}>Urutkan: Bidang Z–A</option>
            </select>

            <div class="flex gap-2">
                <button type="submit" class="w-full bg-emerald-800 text-white font-semibold text-xs py-2 rounded">Filter</button>
                <a href="{{ route('admin.pegawai.index') }}" class="px-3 bg-slate-200 text-slate-700 font-semibold text-xs py-2 rounded">Reset</a>
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
