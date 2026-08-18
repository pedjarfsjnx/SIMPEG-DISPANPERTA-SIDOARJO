@extends('layouts.admin')

@section('title', 'Edit Pegawai - Admin SIMPEG')

@section('content')
<div class="max-w-5xl mx-auto space-y-6">
    <!-- Header Title & Back Button -->
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 pb-2 border-b border-slate-200">
        <div>
            <h2 class="text-2xl font-extrabold text-slate-900 tracking-tight">Edit Data Pegawai: {{ $pegawai->nama }}</h2>
            <p class="text-sm text-slate-500 mt-1">Perbarui data informasi dan riwayat kepegawaian pegawai.</p>
        </div>
        <a href="{{ route('admin.pegawai.index') }}" class="inline-flex items-center space-x-2 px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold text-xs rounded-xl transition border border-slate-200 self-start sm:self-auto">
            <span>&larr;</span>
            <span>Kembali ke Daftar</span>
        </a>
    </div>

    <!-- Main Card Form -->
    <form method="POST" action="{{ route('admin.pegawai.update', $pegawai->id) }}" class="bg-white p-6 sm:p-10 rounded-2xl border border-slate-200 shadow-md space-y-8">
        @csrf
        @method('PUT')

        <!-- Group 1: Identitas Pegawai -->
        <div class="space-y-5">
            <div class="flex items-center space-x-3 pb-3 border-b border-slate-200">
                <span class="w-7 h-7 bg-emerald-800 text-white rounded-full flex items-center justify-center font-bold text-xs shadow-xs">1</span>
                <h3 class="text-sm font-bold text-slate-800 uppercase tracking-wider">Identitas Utama & Kategori</h3>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div class="md:col-span-2">
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                        Nama Lengkap (beserta gelar) <span class="text-rose-500">*</span>
                    </label>
                    <input type="text" 
                           name="nama" 
                           value="{{ old('nama', $pegawai->nama) }}" 
                           required 
                           class="w-full text-sm rounded-xl border-slate-300 focus:border-emerald-700 focus:ring-2 focus:ring-emerald-700/20 py-2.5 px-3.5 shadow-2xs">
                    @error('nama') <p class="text-xs text-rose-600 mt-1">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                        Kategori Pegawai <span class="text-rose-500">*</span>
                    </label>
                    <select name="kategori_pegawai_id" required class="w-full text-sm rounded-xl border-slate-300 focus:border-emerald-700 focus:ring-2 focus:ring-emerald-700/20 py-2.5 px-3.5 bg-white shadow-2xs">
                        @foreach($kategoriList as $kat)
                            <option value="{{ $kat->id }}" {{ old('kategori_pegawai_id', $pegawai->kategori_pegawai_id) == $kat->id ? 'selected' : '' }}>{{ $kat->nama }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                        Status Kepegawaian <span class="text-rose-500">*</span>
                    </label>
                    <select name="status_kepegawaian_id" required class="w-full text-sm rounded-xl border-slate-300 focus:border-emerald-700 focus:ring-2 focus:ring-emerald-700/20 py-2.5 px-3.5 bg-white shadow-2xs">
                        @foreach($statusList as $st)
                            <option value="{{ $st->id }}" {{ old('status_kepegawaian_id', $pegawai->status_kepegawaian_id) == $st->id ? 'selected' : '' }}>{{ $st->nama }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                        NIP (PNS / PPPK)
                    </label>
                    <input type="text" 
                           name="nip" 
                           value="{{ old('nip', $pegawai->nip) }}" 
                           class="w-full text-sm font-mono rounded-xl border-slate-300 focus:border-emerald-700 focus:ring-2 focus:ring-emerald-700/20 py-2.5 px-3.5 shadow-2xs">
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                        NIK (Swakelola / Outsourcing)
                    </label>
                    <input type="text" 
                           name="nik" 
                           value="{{ old('nik', $pegawai->nik) }}" 
                           class="w-full text-sm font-mono rounded-xl border-slate-300 focus:border-emerald-700 focus:ring-2 focus:ring-emerald-700/20 py-2.5 px-3.5 shadow-2xs">
                </div>
            </div>
        </div>

        <!-- Group 2: Penempatan & Formasi -->
        <div class="space-y-5 pt-2">
            <div class="flex items-center space-x-3 pb-3 border-b border-slate-200">
                <span class="w-7 h-7 bg-emerald-800 text-white rounded-full flex items-center justify-center font-bold text-xs shadow-xs">2</span>
                <h3 class="text-sm font-bold text-slate-800 uppercase tracking-wider">Unit Kerja & Formasi Jabatan</h3>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                        Unit Kerja <span class="text-rose-500">*</span>
                    </label>
                    <select name="unit_kerja_id" required class="w-full text-sm rounded-xl border-slate-300 focus:border-emerald-700 focus:ring-2 focus:ring-emerald-700/20 py-2.5 px-3.5 bg-white shadow-2xs">
                        @foreach($unitKerjaList as $u)
                            <option value="{{ $u->id }}" {{ old('unit_kerja_id', $pegawai->unit_kerja_id) == $u->id ? 'selected' : '' }}>{{ $u->nama }} ({{ $u->tipe }})</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                        Bidang / Sub-Unit
                    </label>
                    <select name="bidang_id" class="w-full text-sm rounded-xl border-slate-300 focus:border-emerald-700 focus:ring-2 focus:ring-emerald-700/20 py-2.5 px-3.5 bg-white shadow-2xs">
                        <option value="">-- Tanpa Bidang / Langsung Unit --</option>
                        @foreach($bidangList as $b)
                            <option value="{{ $b->id }}" {{ old('bidang_id', $pegawai->bidang_id) == $b->id ? 'selected' : '' }}>{{ $b->nama }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                        Formasi Jabatan
                    </label>
                    <select name="formasi_jabatan_id" class="w-full text-sm rounded-xl border-slate-300 focus:border-emerald-700 focus:ring-2 focus:ring-emerald-700/20 py-2.5 px-3.5 bg-white shadow-2xs">
                        <option value="">-- Tanpa / Non-Formasi --</option>
                        @foreach($formasiList as $f)
                            <option value="{{ $f->id }}" {{ old('formasi_jabatan_id', $pegawai->formasi_jabatan_id) == $f->id ? 'selected' : '' }}>{{ $f->nama_jabatan }} (Kelas {{ $f->kelas_jabatan ?? '-' }})</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                        Golongan / Ruang
                    </label>
                    <input type="text" 
                           name="golongan" 
                           value="{{ old('golongan', $pegawai->golongan) }}" 
                           class="w-full text-sm rounded-xl border-slate-300 focus:border-emerald-700 focus:ring-2 focus:ring-emerald-700/20 py-2.5 px-3.5 shadow-2xs">
                </div>
            </div>
        </div>

        <!-- Group 3: Data Pribadi & Kontak -->
        <div class="space-y-5 pt-2">
            <div class="flex items-center space-x-3 pb-3 border-b border-slate-200">
                <span class="w-7 h-7 bg-emerald-800 text-white rounded-full flex items-center justify-center font-bold text-xs shadow-xs">3</span>
                <h3 class="text-sm font-bold text-slate-800 uppercase tracking-wider">Biodata, Pendidikan & Kontak</h3>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                        Tempat Lahir
                    </label>
                    <input type="text" 
                           name="tempat_lahir" 
                           value="{{ old('tempat_lahir', $pegawai->tempat_lahir) }}" 
                           class="w-full text-sm rounded-xl border-slate-300 focus:border-emerald-700 focus:ring-2 focus:ring-emerald-700/20 py-2.5 px-3.5 shadow-2xs">
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                        Tanggal Lahir
                    </label>
                    <input type="date" 
                           name="tanggal_lahir" 
                           value="{{ old('tanggal_lahir', $pegawai->tanggal_lahir?->format('Y-m-d')) }}" 
                           class="w-full text-sm rounded-xl border-slate-300 focus:border-emerald-700 focus:ring-2 focus:ring-emerald-700/20 py-2.5 px-3.5 shadow-2xs">
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                        Pendidikan Terakhir
                    </label>
                    <input type="text" 
                           name="pendidikan" 
                           value="{{ old('pendidikan', $pegawai->pendidikan) }}" 
                           class="w-full text-sm rounded-xl border-slate-300 focus:border-emerald-700 focus:ring-2 focus:ring-emerald-700/20 py-2.5 px-3.5 shadow-2xs">
                </div>

                <div>
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                        Nomor HP / WhatsApp
                    </label>
                    <input type="text" 
                           name="no_hp" 
                           value="{{ old('no_hp', $pegawai->no_hp) }}" 
                           class="w-full text-sm font-mono rounded-xl border-slate-300 focus:border-emerald-700 focus:ring-2 focus:ring-emerald-700/20 py-2.5 px-3.5 shadow-2xs">
                </div>

                <div class="md:col-span-2">
                    <label class="block text-xs font-bold uppercase tracking-wider text-slate-700 mb-1.5">
                        Alamat Email
                    </label>
                    <input type="email" 
                           name="email" 
                           value="{{ old('email', $pegawai->email) }}" 
                           class="w-full text-sm rounded-xl border-slate-300 focus:border-emerald-700 focus:ring-2 focus:ring-emerald-700/20 py-2.5 px-3.5 shadow-2xs">
                </div>
            </div>
        </div>

        <!-- Form Actions Footer -->
        <div class="pt-6 border-t border-slate-200 flex flex-col sm:flex-row items-center justify-end gap-3">
            <a href="{{ route('admin.pegawai.index') }}" class="w-full sm:w-auto px-6 py-3 text-center bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold text-sm rounded-xl transition border border-slate-200">
                Batal
            </a>
            <button type="submit" class="w-full sm:w-auto px-8 py-3 bg-emerald-800 hover:bg-emerald-900 text-white font-bold text-sm rounded-xl shadow-md transition flex items-center justify-center space-x-2">
                <span>Perbarui Data Pegawai &rarr;</span>
            </button>
        </div>
    </form>
</div>
@endsection