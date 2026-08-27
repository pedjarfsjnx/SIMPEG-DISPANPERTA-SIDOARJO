@extends('layouts.admin')

@section('title', 'Kelola Formasi Jabatan - Admin SIMPEG')

@section('content')
<div class="space-y-6 text-xs">
    <!-- Header & Action -->
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <div>
            <h2 class="text-xl font-bold text-slate-900">Kelola Formasi Jabatan</h2>
            <p class="text-slate-500">Monitoring ketersediaan slot jabatan, mutasi, dan posisi lowong instansi.</p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('admin.formasi-jabatan.create') }}" class="px-4 py-2 bg-emerald-800 hover:bg-emerald-900 text-white font-semibold text-xs rounded-xl shadow-sm transition flex items-center space-x-1.5">
                <span>+ Tambah Formasi</span>
            </a>
        </div>
    </div>

    <!-- 3 Stat Highlight Summary Strip -->
    <div class="bg-white rounded-2xl border border-slate-200/90 p-4 shadow-sm">
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
            
            <!-- 1. Total Formasi -->
            <div class="p-3.5 rounded-xl bg-slate-50/80 border border-slate-200/80 flex items-center justify-between">
                <div>
                    <div class="text-[11px] font-semibold text-slate-500 uppercase tracking-wider">Total Formasi</div>
                    <div class="text-2xl font-bold text-slate-900 mt-0.5">
                        {{ number_format($totalCount) }} <span class="text-xs font-normal text-slate-500">Posisi</span>
                    </div>
                </div>
                <div class="w-8 h-8 rounded-lg bg-slate-200/70 text-slate-600 flex items-center justify-center">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                </div>
            </div>

            <!-- 2. Formasi Terisi -->
            <div class="p-3.5 rounded-xl bg-emerald-50/50 border border-emerald-200/80 flex items-center justify-between">
                <div>
                    <div class="text-[11px] font-semibold text-emerald-800 uppercase tracking-wider">Formasi Terisi</div>
                    <div class="text-2xl font-bold text-emerald-900 mt-0.5">
                        {{ number_format($terisiCount) }} <span class="text-xs font-normal text-emerald-700">Jabatan</span>
                    </div>
                </div>
                <div class="w-8 h-8 rounded-lg bg-emerald-100 text-emerald-800 flex items-center justify-center">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                </div>
            </div>

            <!-- 3. Formasi Kosong / Lowong -->
            <div class="p-3.5 rounded-xl bg-amber-50/50 border border-amber-200/80 flex items-center justify-between">
                <div>
                    <div class="text-[11px] font-semibold text-amber-800 uppercase tracking-wider">Lowong / Kosong</div>
                    <div class="text-2xl font-bold text-amber-900 mt-0.5">
                        {{ number_format($kosongCount) }} <span class="text-xs font-normal text-amber-700">Slot Siap Isi</span>
                    </div>
                </div>
                <div class="w-8 h-8 rounded-lg bg-amber-100 text-amber-800 flex items-center justify-center">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                </div>
            </div>

        </div>
    </div>

    <!-- Filter Card -->
    <div class="bg-white p-5 rounded-2xl border border-slate-200/90 shadow-sm">
        <form method="GET" action="{{ route('admin.formasi-jabatan.index') }}" class="grid grid-cols-1 md:grid-cols-12 gap-3 items-center">
            <!-- Search -->
            <div class="md:col-span-5 relative">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </div>
                <input type="text" 
                       name="search" 
                       value="{{ request('search') }}" 
                       placeholder="Cari Nama Jabatan atau Nama Pejabat..." 
                       class="w-full text-xs pl-10 pr-3.5 py-2.5 rounded-xl border-slate-300 focus:border-emerald-700 focus:ring-2 focus:ring-emerald-700/20 bg-slate-50/50 shadow-2xs transition">
            </div>

            <!-- Unit Kerja -->
            <div class="md:col-span-3">
                <select name="unit_kerja_id" class="w-full text-xs py-2.5 px-3 rounded-xl border-slate-300 focus:border-emerald-700 focus:ring-2 focus:ring-emerald-700/20 bg-white shadow-2xs transition">
                    <option value="">-- Semua Unit Kerja --</option>
                    @foreach($unitKerjaList as $unit)
                        <option value="{{ $unit->id }}" {{ request('unit_kerja_id') == $unit->id ? 'selected' : '' }}>{{ $unit->nama }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Status Formasi -->
            <div class="md:col-span-2">
                <select name="status_formasi" class="w-full text-xs py-2.5 px-3 rounded-xl border-slate-300 focus:border-emerald-700 focus:ring-2 focus:ring-emerald-700/20 bg-white shadow-2xs transition">
                    <option value="">-- Semua Status --</option>
                    <option value="terisi" {{ request('status_formasi') == 'terisi' ? 'selected' : '' }}>Terisi</option>
                    <option value="kosong" {{ request('status_formasi') == 'kosong' ? 'selected' : '' }}>Kosong / Lowong</option>
                </select>
            </div>

            <!-- Buttons -->
            <div class="md:col-span-2 flex items-center gap-2">
                <button type="submit" class="flex-1 py-2.5 px-3 bg-emerald-800 hover:bg-emerald-900 text-white font-bold text-xs rounded-xl shadow-sm transition flex items-center justify-center space-x-1.5">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/></svg>
                    <span>Filter</span>
                </button>
                @if(request()->hasAny(['search', 'unit_kerja_id', 'status_formasi']))
                    <a href="{{ route('admin.formasi-jabatan.index') }}" title="Reset Filter" class="py-2.5 px-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold text-xs rounded-xl border border-slate-200 transition">
                        Reset
                    </a>
                @endif
            </div>
        </form>
    </div>

    <!-- Data Table -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-slate-100 border-b border-slate-200 text-slate-700 font-semibold uppercase text-[11px]">
                        <th class="py-3 px-4">Nama Jabatan</th>
                        <th class="py-3 px-4">Unit Kerja & Bidang</th>
                        <th class="py-3 px-4">Kelas</th>
                        <th class="py-3 px-4">Status Formasi</th>
                        <th class="py-3 px-4">Pejabat Definitif</th>
                        <th class="py-3 px-4 text-center" width="120">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200">
                    @forelse($formasiList as $f)
                    <tr class="hover:bg-slate-50/80 transition">
                        <td class="py-3 px-4 font-bold text-slate-900">{{ $f->nama_jabatan }}</td>
                        <td class="py-3 px-4">
                            <div class="font-medium text-slate-800">{{ $f->unitKerja?->nama }}</div>
                            <div class="text-slate-400">{{ $f->bidang?->nama ?? '-' }}</div>
                        </td>
                        <td class="py-3 px-4 font-mono font-semibold text-slate-600">{{ $f->kelas_jabatan ? 'Kelas '.$f->kelas_jabatan : '-' }}</td>
                        <td class="py-3 px-4">
                            @if($f->status_formasi === 'kosong')
                                <span class="px-2.5 py-1 bg-amber-100 text-amber-800 font-bold rounded-lg text-[10px] inline-flex items-center space-x-1 border border-amber-200">
                                    
                                    <span>KOSONG / LOWONG</span>
                                </span>
                            @else
                                <span class="px-2.5 py-1 bg-emerald-100 text-emerald-800 font-semibold rounded-lg text-[10px] inline-flex items-center space-x-1 border border-emerald-200">
                                    
                                    <span>Terisi</span>
                                </span>
                            @endif
                        </td>
                        <td class="py-3 px-4">
                            @if($f->pegawai->count() > 0)
                                @foreach($f->pegawai as $p)
                                    <a href="{{ route('admin.pegawai.show', $p->id) }}" class="font-semibold text-emerald-800 hover:underline block">
                                        {{ $p->nama }}
                                    </a>
                                @endforeach
                            @else
                                <span class="text-slate-400 italic">Belum ada pegawai</span>
                            @endif
                        </td>
                        <td class="py-3 px-4 text-center">
                            <div class="flex items-center justify-center space-x-1.5">
                                <a href="{{ route('admin.formasi-jabatan.edit', $f->id) }}" class="px-2.5 py-1 bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold rounded-lg text-[11px] transition">
                                    Edit
                                </a>
                                <form method="POST" action="{{ route('admin.formasi-jabatan.destroy', $f->id) }}" onsubmit="return confirm('Hapus formasi jabatan ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="px-2.5 py-1 bg-rose-50 hover:bg-rose-100 text-rose-700 font-semibold rounded-lg text-[11px] transition">
                                        Hapus
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="py-12 text-center text-slate-500">
                            <div class="flex flex-col items-center justify-center space-y-2">
                                <div class="w-12 h-12 rounded-full bg-slate-100 flex items-center justify-center text-slate-400">
    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
</div>
                                <div class="font-semibold text-slate-700 text-sm">Tidak ada data formasi yang cocok</div>
                                <div class="text-xs text-slate-400">Coba ubah kata kunci pencarian atau filter yang dipilih.</div>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($formasiList->hasPages())
        <div class="p-4 border-t border-slate-200 bg-slate-50">
            {{ $formasiList->links() }}
        </div>
        @endif
    </div>
</div>
@endsection