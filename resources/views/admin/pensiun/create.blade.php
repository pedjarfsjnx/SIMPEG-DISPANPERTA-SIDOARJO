@extends('layouts.admin')

@section('title', 'Catat Usulan Pensiun - Admin SIMPEG')

@section('content')
<div class="max-w-5xl mx-auto space-y-6">
    <!-- Header Title & Back Button -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-3 border-b border-slate-200">
        <div>
            <div class="flex items-center space-x-2">
                <span class="px-2.5 py-0.5 bg-amber-100 text-amber-800 font-bold rounded-lg text-[10px] uppercase tracking-wider">Modul Purna Tugas</span>
                <span class="text-slate-400">&bull;</span>
                <span class="text-xs text-slate-500 font-medium">BUP & Pensiun Dini</span>
            </div>
            <h2 class="text-2xl font-extrabold text-slate-900 tracking-tight mt-1">Catat Usulan Pensiun Pegawai</h2>
            <p class="text-sm text-slate-500 mt-0.5">Daftarkan jadwal dan berkas masa purna tugas pegawai instansi ke dalam sistem.</p>
        </div>
        <a href="{{ route('admin.pensiun.index') }}" class="inline-flex items-center space-x-2 px-4 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold text-xs rounded-xl transition border border-slate-200 shadow-2xs self-start sm:self-auto">
            <span>&larr;</span>
            <span>Kembali ke Daftar Pensiun</span>
        </a>
    </div>

    <!-- Main Card Form -->
    <form method="POST" action="{{ route('admin.pensiun.store') }}" class="bg-white p-6 sm:p-10 rounded-2xl border border-slate-200 shadow-md space-y-8">
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
                            {{ $p->nama }} — (NIP: {{ $p->nip ?? 'Non-PNS' }} | Jabatan: {{ $p->jabatan ?? '-' }})
                        </option>
                    @endforeach
                </select>
                @error('pegawai_id') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror
            </div>
        </div>

        <!-- Section 2: Jadwal & Masa Pensiun -->
        <div class="space-y-5">
            <div class="flex items-center space-x-3 pb-3 border-b border-slate-200">
                <span class="w-7 h-7 bg-emerald-800 text-white rounded-full flex items-center justify-center font-bold text-xs shadow-xs">2</span>
                <h3 class="text-sm font-bold text-slate-800 uppercase tracking-wider">Jadwal & Penetapan TMT</h3>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                        Tanggal Pengajuan Berkas
                    </label>
                    <input type="date" 
                           name="tanggal_pengajuan" 
                           value="{{ old('tanggal_pengajuan', date('Y-m-d')) }}" 
                           class="w-full text-sm rounded-xl border-slate-300 focus:border-emerald-700 focus:ring-2 focus:ring-emerald-700/20 py-2.5 px-3.5 shadow-2xs">
                    <p class="text-[11px] text-slate-400 mt-1">Tanggal kelengkapan berkas diterima admin.</p>
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                        TMT Pensiun (Terhitung Mulai Tanggal) <span class="text-rose-500">*</span>
                    </label>
                    <input type="date" 
                           name="tmt_pensiun" 
                           value="{{ old('tmt_pensiun') }}" 
                           required 
                           class="w-full text-sm rounded-xl border-slate-300 focus:border-emerald-700 focus:ring-2 focus:ring-emerald-700/20 py-2.5 px-3.5 shadow-2xs">
                    <p class="text-[11px] text-slate-400 mt-1">Tanggal resmi berlakunya masa purna tugas.</p>
                    @error('tmt_pensiun') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror
                </div>
            </div>
        </div>

        <!-- Section 3: Jenis Pensiun & Catatan -->
        <div class="space-y-5">
            <div class="flex items-center space-x-3 pb-3 border-b border-slate-200">
                <span class="w-7 h-7 bg-emerald-800 text-white rounded-full flex items-center justify-center font-bold text-xs shadow-xs">3</span>
                <h3 class="text-sm font-bold text-slate-800 uppercase tracking-wider">Keterangan & Klasifikasi</h3>
            </div>

            <div>
                <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                    Jenis Pensiun / Keterangan Tambahan
                </label>
                <input type="text" 
                       name="keterangan" 
                       value="{{ old('keterangan', 'Mencapai Batas Usia Pensiun (BUP)') }}" 
                       placeholder="Contoh: Batas Usia Pensiun (BUP) / Pensiun Dini / Pensiun Janda-Duda" 
                       class="w-full text-sm rounded-xl border-slate-300 focus:border-emerald-700 focus:ring-2 focus:ring-emerald-700/20 py-2.5 px-3.5 shadow-2xs">
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="flex items-center justify-end gap-3 pt-6 border-t border-slate-200">
            <a href="{{ route('admin.pensiun.index') }}" class="px-6 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold text-xs rounded-xl transition border border-slate-200">
                Batal
            </a>
            <button type="submit" class="px-7 py-2.5 bg-emerald-800 hover:bg-emerald-900 text-white font-bold text-xs rounded-xl shadow-md transition flex items-center space-x-2">
                <span>Simpan Usulan Pensiun</span>
                <span>&rarr;</span>
            </button>
        </div>
    </form>
</div>
@endsection