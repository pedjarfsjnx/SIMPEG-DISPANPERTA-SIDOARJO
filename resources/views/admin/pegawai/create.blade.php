@extends('layouts.admin')

@section('title', 'Tambah Pegawai Baru - Admin SIMPEG')

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    <div class="flex items-center justify-between">
        <h2 class="text-xl font-bold text-slate-900">Tambah Data Pegawai Baru</h2>
        <a href="{{ route('admin.pegawai.index') }}" class="text-xs font-semibold text-slate-500 hover:text-slate-800">&larr; Batal / Kembali</a>
    </div>

    <form method="POST" action="{{ route('admin.pegawai.store') }}" class="bg-white p-6 sm:p-8 rounded-xl border border-slate-200 shadow-sm space-y-6 text-xs">
        @csrf

        <!-- Group 1: Identitas Pegawai -->
        <div class="space-y-4">
            <h3 class="font-bold uppercase tracking-wider text-slate-400 border-b border-slate-100 pb-2">1. Identitas Utama</h3>
            
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="col-span-2">
                    <label class="block font-medium text-slate-700 mb-1">Nama Lengkap (dengan gelar) *</label>
                    <input type="text" name="nama" value="{{ old('nama') }}" required class="w-full rounded-lg border-slate-300">
                </div>

                <div>
                    <label class="block font-medium text-slate-700 mb-1">Kategori Pegawai *</label>
                    <select name="kategori_pegawai_id" required class="w-full rounded-lg border-slate-300">
                        @foreach($kategoriList as $kats)
                            <option value="{{ $kat->id }}">{{ $kat->nama }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block font-medium text-slate-700 mb-1">Status Kepegawaian *</label>
                    <select name="status_kepegawaian_id" required class="w-full rounded-lg border-slate-300">
                        @foreach($statusList as $st)
                            <option value="{{ $st->id }}">{{ $st->nama }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block font-medium text-slate-700 mb-1">NIP (PNS/PPPK - 18 digit)</label>
                    <input type="text" name="nip" value="{{ old('nip') }}" placeholder="19800101..." class="w-full rounded-lg border-slate-300">
                </div>

                <div>
                    <label class="block font-medium text-slate-700 mb-1">NIK (Swakelola/Outsourcing - 16 digit)</label>
                    <input type="text" name="nik" value="{{ old('nik') }}" placeholder="351508..." class="w-full rounded-lg border-slate-300">
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
                            <option value="{{ $u->id }}">{{ $u->nama }} ({{ $u->tipe }})</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block font-medium text-slate-700 mb-1">Bidang / Sub-Unit</label>
                    <select name="bidang_id" class="w-full rounded-lg border-slate-300">
                        <option value="">-- Tanpa Bidang / Langsung Unit --</option>
                        @foreach($bidangList as $b)
                            <option value="{{ $b->id }}">{{ $b->nama }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block font-medium text-slate-700 mb-1">Pilih Formasi Jabatan Kosong</label>
                    <select name="formasi_jabatan_id" class="w-full rounded-lg border-slate-300">
                        <option value="">-- Tanpa / Non-Formasi --</option>
                        @foreach($formasiList as $f)
                            <option value="{{ $f->id }}">{{ $f->nama_jabatan }} (Kelas {{ $f->kelas_jabatan ?? '-' }})</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block font-medium text-slate-700 mb-1">Golongan / Ruang</label>
                    <input type="text" name="golongan" value="{{ old('golongan') }}" placeholder="Contoh: IV/a atau IX" class="w-full rounded-lg border-slate-300">
                </div>
            </div>
        </div>

        <!-- Group 3: Data Pribadi & Kontak (Privasi) -->
        <div class="space-y-4 pt-4">
            <h3 class="font-bold uppercase tracking-wider text-slate-400 border-b border-slate-100 pb-2">3. Kontak & Pendidikan</h3>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block font-medium text-slate-700 mb-1">Tempat Lahir</label>
                    <input type="text" name="tempat_lahir" value="{{ old('tempat_lahir') }}" class="w-full rounded-lg border-slate-300">
                </div>

                <div>
                    <label class="block font-medium text-slate-700 mb-1">Tanggal Lahir</label>
                    <input type="date" name="tanggal_lahir" value="{{ old('tanggal_lahir') }}" class="w-full rounded-lg border-slate-300">
                </div>

                <div>
                    <label class="block font-medium text-slate-700 mb-1">Pendidikan Terakhir</label>
                    <input type="text" name="pendidikan" value="{{ old('pendidikan') }}" placeholder="S1 Pertanian" class="w-full rounded-lg border-slate-300">
                </div>

                <div>
                    <label class="block font-medium text-slate-700 mb-1">No. HP (Sensitif)</label>
                    <input type="text" name="no_hp" value="{{ old('no_hp') }}" class="w-full rounded-lg border-slate-300">
                </div>

                <div class="col-span-2">
                    <label class="block font-medium text-slate-700 mb-1">Email Pribadi (Sensitif)</label>
                    <input type="email" name="email" value="{{ old('email') }}" class="w-full rounded-lg border-slate-300">
                </div>
            </div>
        </div>

        <!-- Submit Button -->
        <div class="pt-6 border-t border-slate-100 flex justify-end gap-3">
            <a href="{{ route('admin.pegawai.index') }}" class="px-5 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-700 font-semibold rounded-lg">Batal</a>
            <button type="submit" class="px-5 py-2.5 bg-emerald-800 hover:bg-emerald-900 text-white font-semibold rounded-lg shadow">Simpan Pegawai</button>
        </div>
    </form>
</div>
@endsection
