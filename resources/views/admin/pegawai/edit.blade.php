@extends('layouts.admin')

@section('title', 'Edit Pegawai - Admin SIMPEG')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <h2 class="text-xl font-bold text-slate-900">Edit Data Pegawai: {{ $pegawai->nama }}</h2>
        <a href="{{ route('admin.pegawai.index') }}" class="text-xs font-semibold text-slate-500 hover:text-slate-800">&larr; Kembali</a>
    </div>

    <form method="POST" action="{{ route('admin.pegawai.update', $pegawai->id) }}" class="bg-white p-6 sm:p-8 rounded-xl border border-slate-200 shadow-sm space-y-6 text-xs">
        @csrf
        @method('PUT')

        <!-- Group 1: Identitas Pegawai -->
        <div class="space-y-4">
            <h3 class="font-bold uppercase tracking-wider text-slate-400 border-b border-slate-100 pb-2">1. Identitas Utama</h3>
            
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="col-span-2">
                    <label class="block font-medium text-slate-700 mb-1">Nama Lengkap *</label>
                    <input type="text" name="nama" value="{{ old('nama', $pegawai->nama) }}" required class="w-full rounded-lg border-slate-300">
                </div>

                <div>
                    <label class="block font-medium text-slate-700 mb-1">Kategori Pegawai *</label>
                    <select name="kategori_pegawai_id" required class="w-full rounded-lg border-slate-300">
                        @foreach($kategoriList as $kat)
                            <option value="{{ $kat->id }}" {{ $pegawai->kategori_pegawai_id == $kat->id ? 'selected' : '' }}>{{ $kat->nama }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block font-medium text-slate-700 mb-1">Status Kepegawaian *</label>
                    <select name="status_kepegawaian_id" required class="w-full rounded-lg border-slate-300">
                        @foreach($statusList as $st)
                            <option value="{{ $st->id }}" {{ $pegawai->status_kepegawaian_id == $st->id ? 'selected' : '' }}>{{ $st->nama }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block font-medium text-slate-700 mb-1">NIP</label>
                    <input type="text" name="nip" value="{{ old('nip', $pegawai->nip) }}" class="w-full rounded-lg border-slate-300">
                </div>

                <div>
                    <label class="block font-medium text-slate-700 mb-1">NIK</label>
                    <input type="text" name="nik" value="{{ old('nik', $pegawai->nik) }}" class="w-full rounded-lg border-slate-300">
                </div>
            </div>
        </div>

        <!-- Group 2: Unit Kerja & Formasi -->
        <div class="space-y-4 pt-4">
            <h3 class="font-bold uppercase tracking-wider text-slate-400 border-b border-slate-100 pb-2">2. Unit Kerja & Jabatan</h3>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block font-medium text-slate-700 mb-1">Unit Kerja *</label>
                    <select name="unit_kerja_id" required class="w-full rounded-lg border-slate-300">
                        @foreach($unitKerjaList as $u)
                            <option value="{{ $u->id }}" {{ $pegawai->unit_kerja_id == $u->id ? 'selected' : '' }}>{{ $u->nama }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block font-medium text-slate-700 mb-1">Bidang / Sub-Unit</label>
                    <select name="bidang_id" class="w-full rounded-lg border-slate-300">
                        <option value="">-- Tanpa Bidang --</option>
                        @foreach($bidangList as $b)
                            <option value="{{ $b->id }}" {{ $pegawai->bidang_id == $b->id ? 'selected' : '' }}>{{ $b->nama }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block font-medium text-slate-700 mb-1">Formasi Jabatan</label>
                    <select name="formasi_jabatan_id" class="w-full rounded-lg border-slate-300">
                        <option value="">-- Tanpa / Non-Formasi --</option>
                        @foreach($formasiList as $f)
                            <option value="{{ $f->id }}" {{ $pegawai->formasi_jabatan_id == $f->id ? 'selected' : '' }}>{{ $f->nama_jabatan }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block font-medium text-slate-700 mb-1">Golongan / Ruang</label>
                    <input type="text" name="golongan" value="{{ old('golongan', $pegawai->golongan) }}" class="w-full rounded-lg border-slate-300">
                </div>
            </div>
        </div>

        <!-- Group 3: Kontak & Pendidikan -->
        <div class="space-y-4 pt-4">
            <h3 class="font-bold uppercase tracking-wider text-slate-400 border-b border-slate-100 pb-2">3. Kontak & Pendidikan</h3>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block font-medium text-slate-700 mb-1">Tempat Lahir</label>
                    <input type="text" name="tempat_lahir" value="{{ old('tempat_lahir', $pegawai->tempat_lahir) }}" class="w-full rounded-lg border-slate-300">
                </div>

                <div>
                    <label class="block font-medium text-slate-700 mb-1">Tanggal Lahir</label>
                    <input type="date" name="tanggal_lahir" value="{{ old('tanggal_lahir', $pegawai->tanggal_lahir?->format('Y-m-d')) }}" class="w-full rounded-lg border-slate-300">
                </div>

                <div>
                    <label class="block font-medium text-slate-700 mb-1">Pendidikan Terakhir</label>
                    <input type="text" name="pendidikan" value="{{ old('pendidikan', $pegawai->pendidikan) }}" class="w-full rounded-lg border-slate-300">
                </div>

                <div>
                    <label class="block font-medium text-slate-700 mb-1">No. HP</label>
                    <input type="text" name="no_hp" value="{{ old('no_hp', $pegawai->no_hp) }}" class="w-full rounded-lg border-slate-300">
                </div>

                <div class="col-span-2">
                    <label class="block font-medium text-slate-700 mb-1">Email</label>
                    <input type="email" name="email" value="{{ old('email', $pegawai->email) }}" class="w-full rounded-lg border-slate-300">
                </div>
            </div>
        </div>

        <div class="pt-6 border-t border-slate-100 flex justify-end gap-3">
            <a href="{{ route('admin.pegawai.index') }}" class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold rounded-lg">Batal</a>
            <button type="submit" class="px-5 py-2.5 bg-emerald-800 hover:bg-emerald-900 text-white font-semibold rounded-lg shadow">Perbarui Data</button>
        </div>
    </form>
</div>
@endsection
