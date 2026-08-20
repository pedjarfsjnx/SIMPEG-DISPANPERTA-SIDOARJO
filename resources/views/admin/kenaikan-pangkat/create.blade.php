@extends('layouts.admin')

@section('title', 'Tambah Usulan Kenaikan Pangkat - Admin SIMPEG')

@section('content')
<div class="max-w-5xl mx-auto space-y-6">
    <!-- Header Title & Back Button -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-3 border-b border-slate-200">
        <div>
            <div class="flex items-center space-x-2">
                <span class="px-2.5 py-0.5 bg-emerald-100 text-emerald-800 font-bold rounded-lg text-[10px] uppercase tracking-wider">Modul Karier Pegawai</span>
                <span class="text-slate-400">&bull;</span>
                <span class="text-xs text-slate-500 font-medium">Usulan Kenaikan Pangkat (KP)</span>
            </div>
            <h2 class="text-2xl font-extrabold text-slate-900 tracking-tight mt-1">Tambah Usulan Kenaikan Pangkat</h2>
            <p class="text-sm text-slate-500 mt-0.5">Daftarkan usulan kenaikan jenjang pangkat/golongan pegawai untuk periode mendatang.</p>
        </div>
        <a href="{{ route('admin.kenaikan-pangkat.index') }}" class="inline-flex items-center space-x-2 px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold text-xs rounded-xl transition border border-slate-200 shadow-2xs self-start sm:self-auto">
            <span>&larr;</span>
            <span>Kembali ke Daftar Usulan</span>
        </a>
    </div>

    <!-- Main Card Form -->
    <form method="POST" action="{{ route('admin.kenaikan-pangkat.store') }}" class="bg-white p-6 sm:p-10 rounded-2xl border border-slate-200 shadow-md space-y-8">
        @csrf

        <!-- Section 1: Pemilihan Pegawai -->
        <div class="space-y-5">
            <div class="flex items-center space-x-3 pb-3 border-b border-slate-200">
                <span class="w-7 h-7 bg-emerald-800 text-white rounded-full flex items-center justify-center font-bold text-xs shadow-xs">1</span>
                <h3 class="text-sm font-bold text-slate-800 uppercase tracking-wider">Pilih Personel Pegawai</h3>
            </div>

            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                    Nama Pegawai & Identitas <span class="text-rose-500">*</span>
                </label>
                <select name="pegawai_id" required class="w-full text-sm rounded-xl border-slate-300 focus:border-emerald-700 focus:ring-2 focus:ring-emerald-700/20 py-3 px-3.5 bg-slate-50/50 shadow-2xs transition">
                    <option value="">-- Cari & Pilih Pegawai Terdaftar --</option>
                    @foreach($pegawaiList as $p)
                        <option value="{{ $p->id }}" {{ old('pegawai_id') == $p->id ? 'selected' : '' }}>
                            {{ $p->nama }} — (NIP: {{ $p->nip ?? 'Non-PNS' }} | Golongan Saat Ini: {{ $p->golongan ?? '-' }})
                        </option>
                    @endforeach
                </select>
                @error('pegawai_id') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror
            </div>
        </div>

        <!-- Section 2: Penyesuaian Golongan & TMT -->
        <div class="space-y-5">
            <div class="flex items-center space-x-3 pb-3 border-b border-slate-200">
                <span class="w-7 h-7 bg-emerald-800 text-white rounded-full flex items-center justify-center font-bold text-xs shadow-xs">2</span>
                <h3 class="text-sm font-bold text-slate-800 uppercase tracking-wider">Penyesuaian Golongan / Ruang & TMT</h3>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                        Golongan Lama
                    </label>
                    <input type="text" 
                           name="golongan_lama" 
                           value="{{ old('golongan_lama') }}" 
                           placeholder="Contoh: III/a" 
                           class="w-full text-sm rounded-xl border-slate-300 focus:border-emerald-700 focus:ring-2 focus:ring-emerald-700/20 py-2.5 px-3.5 shadow-2xs">
                    <p class="text-[11px] text-slate-400 mt-1">Pangkat/golongan eksisting saat ini.</p>
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                        Golongan Baru Usulan <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" 
                           name="golongan_baru" 
                           value="{{ old('golongan_baru') }}" 
                           required 
                           placeholder="Contoh: III/b" 
                           class="w-full text-sm rounded-xl border-slate-300 focus:border-emerald-700 focus:ring-2 focus:ring-emerald-700/20 py-2.5 px-3.5 shadow-2xs">
                    <p class="text-[11px] text-slate-400 mt-1">Target kenaikan jenjang pangkat.</p>
                    @error('golongan_baru') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                        TMT Diusulkan <span class="text-rose-500">*</span>
                    </label>
                    <input type="date" 
                           name="tmt_diusulkan" 
                           value="{{ old('tmt_diusulkan') }}" 
                           required 
                           class="w-full text-sm rounded-xl border-slate-300 focus:border-emerald-700 focus:ring-2 focus:ring-emerald-700/20 py-2.5 px-3.5 shadow-2xs">
                    <p class="text-[11px] text-slate-400 mt-1">Periode TMT efektif SK kenaikan pangkat.</p>
                    @error('tmt_diusulkan') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror
                </div>
            </div>
        </div>

        <!-- Section 3: Catatan & Berkas -->
        <div class="space-y-5">
            <div class="flex items-center space-x-3 pb-3 border-b border-slate-200">
                <span class="w-7 h-7 bg-emerald-800 text-white rounded-full flex items-center justify-center font-bold text-xs shadow-xs">3</span>
                <h3 class="text-sm font-bold text-slate-800 uppercase tracking-wider">Catatan Kelengkapan Berkas</h3>
            </div>

            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                    Keterangan / Dokumen Pendukung
                </label>
                <textarea name="keterangan" 
                          rows="3" 
                          placeholder="Catatan SK terakhir, angka kredit PAK, surat usulan dinas, atau status pengantar BKN..." 
                          class="w-full text-sm rounded-xl border-slate-300 focus:border-emerald-700 focus:ring-2 focus:ring-emerald-700/20 p-3 shadow-2xs">{{ old('keterangan') }}</textarea>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="flex items-center justify-end gap-3 pt-6 border-t border-slate-200">
            <a href="{{ route('admin.kenaikan-pangkat.index') }}" class="px-6 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold text-xs rounded-xl transition border border-slate-200">
                Batal
            </a>
            <button type="submit" class="px-7 py-2.5 bg-emerald-800 hover:bg-emerald-900 text-white font-bold text-xs rounded-xl shadow-md transition flex items-center space-x-2">
                <span>Simpan Usulan Pangkat</span>
                <span>&rarr;</span>
            </button>
        </div>
    </form>
</div>
@endsection