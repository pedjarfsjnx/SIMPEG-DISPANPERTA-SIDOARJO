@extends('layouts.admin')

@section('title', 'Edit Formasi Jabatan - Admin SIMPEG')

@section('content')
<div class="max-w-5xl mx-auto space-y-6">
    <!-- Header Title & Back Button -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-3 border-b border-slate-200">
        <div>
            <div class="flex items-center space-x-2">
                <span class="px-2.5 py-0.5 bg-emerald-100 text-emerald-800 font-bold rounded-lg text-[10px] uppercase tracking-wider">Perbarui Posisi</span>
                <span class="text-slate-400">&bull;</span>
                <span class="text-xs text-slate-500 font-medium">Slot Formasi Instansi</span>
            </div>
            <h2 class="text-2xl font-extrabold text-slate-900 tracking-tight mt-1">Edit Formasi Jabatan</h2>
            <p class="text-sm text-slate-500 mt-0.5">Perbarui rincian posisi atau status keterisian formasi.</p>
        </div>
        <a href="{{ route('admin.formasi-jabatan.index') }}" class="inline-flex items-center space-x-2 px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold text-xs rounded-xl transition border border-slate-200 shadow-2xs self-start sm:self-auto">
            <span>&larr;</span>
            <span>Kembali ke Daftar Formasi</span>
        </a>
    </div>

    <!-- Main Card Form -->
    <form method="POST" action="{{ route('admin.formasi-jabatan.update', $formasi->id) }}" class="bg-white p-6 sm:p-10 rounded-2xl border border-slate-200 shadow-md space-y-8">
        @csrf
        @method('PUT')

        <!-- Section 1: Penempatan Unit Kerja & Bidang -->
        <div class="space-y-5">
            <div class="flex items-center space-x-3 pb-3 border-b border-slate-200">
                <span class="w-7 h-7 bg-emerald-800 text-white rounded-full flex items-center justify-center font-bold text-xs shadow-xs">1</span>
                <h3 class="text-sm font-bold text-slate-800 uppercase tracking-wider">Unit Kerja & Penempatan</h3>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                        Unit Kerja <span class="text-rose-500">*</span>
                    </label>
                    <select name="unit_kerja_id" required class="w-full text-sm rounded-xl border-slate-300 focus:border-emerald-700 focus:ring-2 focus:ring-emerald-700/20 py-2.5 px-3.5 bg-white shadow-2xs">
                        @foreach($unitKerjaList as $unit)
                            <option value="{{ $unit->id }}" {{ old('unit_kerja_id', $formasi->unit_kerja_id) == $unit->id ? 'selected' : '' }}>{{ $unit->nama }}</option>
                        @endforeach
                    </select>
                    @error('unit_kerja_id') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                        Bidang / Sub-Unit
                    </label>
                    <select name="bidang_id" class="w-full text-sm rounded-xl border-slate-300 focus:border-emerald-700 focus:ring-2 focus:ring-emerald-700/20 py-2.5 px-3.5 bg-white shadow-2xs">
                        <option value="">-- Non-Bidang / Fungsional Unit --</option>
                        @foreach($bidangList as $bid)
                            <option value="{{ $bid->id }}" {{ old('bidang_id', $formasi->bidang_id) == $bid->id ? 'selected' : '' }}>
                                {{ $bid->unitKerja?->nama ? $bid->unitKerja->nama.' — ' : '' }}{{ $bid->nama }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <!-- Section 2: Rincian Jabatan & Keterisian -->
        <div class="space-y-5">
            <div class="flex items-center space-x-3 pb-3 border-b border-slate-200">
                <span class="w-7 h-7 bg-emerald-800 text-white rounded-full flex items-center justify-center font-bold text-xs shadow-xs">2</span>
                <h3 class="text-sm font-bold text-slate-800 uppercase tracking-wider">Rincian Jabatan & Status Formasi</h3>
            </div>

            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                    Nama Jabatan Lengkap <span class="text-rose-500">*</span>
                </label>
                <input type="text" 
                       name="nama_jabatan" 
                       value="{{ old('nama_jabatan', $formasi->nama_jabatan) }}" 
                       required 
                       class="w-full text-sm rounded-xl border-slate-300 focus:border-emerald-700 focus:ring-2 focus:ring-emerald-700/20 py-2.5 px-3.5 shadow-2xs">
                @error('nama_jabatan') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                        Kelas Jabatan
                    </label>
                    <input type="text" 
                       name="kelas_jabatan" 
                       value="{{ old('kelas_jabatan', $formasi->kelas_jabatan) }}" 
                       class="w-full text-sm rounded-xl border-slate-300 focus:border-emerald-700 focus:ring-2 focus:ring-emerald-700/20 py-2.5 px-3.5 shadow-2xs">
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                        Status Keterisian Formasi <span class="text-rose-500">*</span>
                    </label>
                    <select name="status_formasi" required class="w-full text-sm rounded-xl border-slate-300 focus:border-emerald-700 focus:ring-2 focus:ring-emerald-700/20 py-2.5 px-3.5 bg-white shadow-2xs">
                        <option value="kosong" {{ old('status_formasi', $formasi->status_formasi) == 'kosong' ? 'selected' : '' }}>Kosong / Lowong (Siap Diisi)</option>
                        <option value="terisi" {{ old('status_formasi', $formasi->status_formasi) == 'terisi' ? 'selected' : '' }}>Terisi (Sudah Ada Pejabat)</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="flex items-center justify-end gap-3 pt-6 border-t border-slate-200">
            <a href="{{ route('admin.formasi-jabatan.index') }}" class="px-6 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold text-xs rounded-xl transition border border-slate-200">
                Batal
            </a>
            <button type="submit" class="px-7 py-2.5 bg-emerald-800 hover:bg-emerald-900 text-white font-bold text-xs rounded-xl shadow-md transition flex items-center space-x-2">
                <span>Simpan Perubahan</span>
                <span>&rarr;</span>
            </button>
        </div>
    </form>
</div>
@endsection