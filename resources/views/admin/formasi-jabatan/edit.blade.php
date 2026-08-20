@extends('layouts.admin')

@section('title', 'Edit Formasi Jabatan - Admin SIMPEG')

@section('content')
<div class="max-w-3xl space-y-5 text-xs">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-xl font-bold text-slate-900">Edit Formasi Jabatan</h2>
            <p class="text-slate-500">Perbarui rincian posisi atau status keterisian formasi.</p>
        </div>
        <a href="{{ route('admin.formasi-jabatan.index') }}" class="px-3 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl font-semibold transition">
            &larr; Kembali
        </a>
    </div>

    <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">
        <form method="POST" action="{{ route('admin.formasi-jabatan.update', $formasi->id) }}" class="space-y-4">
            @csrf
            @method('PUT')

            <div>
                <label class="block font-semibold text-slate-700 mb-1">Unit Kerja <span class="text-rose-500">*</span></label>
                <select name="unit_kerja_id" required class="w-full text-xs rounded-xl border-slate-300 focus:border-emerald-700 py-2.5">
                    @foreach($unitKerjaList as $unit)
                        <option value="{{ $unit->id }}" {{ old('unit_kerja_id', $formasi->unit_kerja_id) == $unit->id ? 'selected' : '' }}>{{ $unit->nama }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block font-semibold text-slate-700 mb-1">Bidang / Sub-Unit</label>
                <select name="bidang_id" class="w-full text-xs rounded-xl border-slate-300 focus:border-emerald-700 py-2.5">
                    <option value="">-- Non-Bidang / Fungsional Unit --</option>
                    @foreach($bidangList as $bid)
                        <option value="{{ $bid->id }}" {{ old('bidang_id', $formasi->bidang_id) == $bid->id ? 'selected' : '' }}>
                            {{ $bid->unitKerja?->nama ? $bid->unitKerja->nama.' — ' : '' }}{{ $bid->nama }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block font-semibold text-slate-700 mb-1">Nama Jabatan <span class="text-rose-500">*</span></label>
                <input type="text" name="nama_jabatan" value="{{ old('nama_jabatan', $formasi->nama_jabatan) }}" required class="w-full text-xs rounded-xl border-slate-300 focus:border-emerald-700 py-2.5">
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block font-semibold text-slate-700 mb-1">Kelas Jabatan</label>
                    <input type="text" name="kelas_jabatan" value="{{ old('kelas_jabatan', $formasi->kelas_jabatan) }}" class="w-full text-xs rounded-xl border-slate-300 focus:border-emerald-700 py-2.5">
                </div>

                <div>
                    <label class="block font-semibold text-slate-700 mb-1">Status Formasi <span class="text-rose-500">*</span></label>
                    <select name="status_formasi" required class="w-full text-xs rounded-xl border-slate-300 focus:border-emerald-700 py-2.5">
                        <option value="kosong" {{ old('status_formasi', $formasi->status_formasi) == 'kosong' ? 'selected' : '' }}>Kosong / Lowong</option>
                        <option value="terisi" {{ old('status_formasi', $formasi->status_formasi) == 'terisi' ? 'selected' : '' }}>Terisi</option>
                    </select>
                </div>
            </div>

            <div class="pt-4 border-t border-slate-100 flex justify-end gap-2">
                <a href="{{ route('admin.formasi-jabatan.index') }}" class="px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl font-semibold transition">
                    Batal
                </a>
                <button type="submit" class="px-5 py-2.5 bg-emerald-800 hover:bg-emerald-900 text-white font-bold rounded-xl shadow-sm transition">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>
@endsection