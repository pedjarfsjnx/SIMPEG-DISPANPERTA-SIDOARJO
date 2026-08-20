@extends('layouts.admin')

@section('title', 'Tambah Usulan Kenaikan Pangkat - Admin SIMPEG')

@section('content')
<div class="max-w-3xl space-y-5 text-xs">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-xl font-bold text-slate-900">Tambah Usulan Kenaikan Pangkat</h2>
            <p class="text-slate-500">Daftarkan pegawai yang diajukan kenaikan pangkat periode mendatang.</p>
        </div>
        <a href="{{ route('admin.kenaikan-pangkat.index') }}" class="px-3 py-1.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl font-semibold transition">
            &larr; Kembali
        </a>
    </div>

    <div class="bg-white p-6 rounded-2xl border border-slate-200 shadow-sm">
        <form method="POST" action="{{ route('admin.kenaikan-pangkat.store') }}" class="space-y-4">
            @csrf

            <div>
                <label class="block font-semibold text-slate-700 mb-1">Pilih Pegawai <span class="text-rose-500">*</span></label>
                <select name="pegawai_id" required class="w-full text-xs rounded-xl border-slate-300 focus:border-emerald-700 py-2.5">
                    <option value="">-- Pilih Pegawai --</option>
                    @foreach($pegawaiList as $p)
                        <option value="{{ $p->id }}" {{ old('pegawai_id') == $p->id ? 'selected' : '' }}>
                            {{ $p->nama }} (NIP: {{ $p->nip ?? 'Non-PNS' }} | Gol: {{ $p->golongan ?? '-' }})
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block font-semibold text-slate-700 mb-1">Golongan Lama</label>
                    <input type="text" name="golongan_lama" value="{{ old('golongan_lama') }}" placeholder="Contoh: III/a" class="w-full text-xs rounded-xl border-slate-300 focus:border-emerald-700 py-2.5">
                </div>

                <div>
                    <label class="block font-semibold text-slate-700 mb-1">Golongan Baru Usulan <span class="text-rose-500">*</span></label>
                    <input type="text" name="golongan_baru" value="{{ old('golongan_baru') }}" required placeholder="Contoh: III/b" class="w-full text-xs rounded-xl border-slate-300 focus:border-emerald-700 py-2.5">
                </div>
            </div>

            <div>
                <label class="block font-semibold text-slate-700 mb-1">TMT Diusulkan <span class="text-rose-500">*</span></label>
                <input type="date" name="tmt_diusulkan" value="{{ old('tmt_diusulkan') }}" required class="w-full text-xs rounded-xl border-slate-300 focus:border-emerald-700 py-2.5">
            </div>

            <div>
                <label class="block font-semibold text-slate-700 mb-1">Keterangan / Catatan Usulan</label>
                <textarea name="keterangan" rows="3" placeholder="Catatan berkas BKN, SK terakhir, atau dokumen pendukung..." class="w-full text-xs rounded-xl border-slate-300 focus:border-emerald-700 p-2.5">{{ old('keterangan') }}</textarea>
            </div>

            <div class="pt-4 border-t border-slate-100 flex justify-end gap-2">
                <a href="{{ route('admin.kenaikan-pangkat.index') }}" class="px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-xl font-semibold transition">
                    Batal
                </a>
                <button type="submit" class="px-5 py-2.5 bg-emerald-800 hover:bg-emerald-900 text-white font-bold rounded-xl shadow-sm transition">
                    Simpan Usulan Pangkat
                </button>
            </div>
        </form>
    </div>
</div>
@endsection