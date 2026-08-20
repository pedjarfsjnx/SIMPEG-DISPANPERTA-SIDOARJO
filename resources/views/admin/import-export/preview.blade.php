@extends('layouts.admin')

@section('title', 'Pratinjau Import Excel - Admin SIMPEG')

@section('content')
<div class="space-y-6 text-xs">
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-xl font-bold text-slate-900">Pratinjau Data Import</h2>
            <p class="text-slate-500">Periksa hasil pembacaan baris Excel sebelum disimpan ke database.</p>
        </div>
        <form method="POST" action="{{ route('admin.import.commit') }}">
            @csrf
            <button type="submit" class="px-5 py-2.5 bg-emerald-800 hover:bg-emerald-900 text-white font-bold rounded-lg shadow">
                Valid Konfirmasi & Commit Simpan ke Database
            </button>
        </form>
    </div>

    <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-slate-100 border-b text-slate-600 font-semibold uppercase">
                    <th class="py-3 px-4">No</th>
                    <th class="py-3 px-4">Nama</th>
                    <th class="py-3 px-4">NIP (Hasil Normalisasi)</th>
                    <th class="py-3 px-4">Jabatan</th>
                    <th class="py-3 px-4">Golongan</th>
                    <th class="py-3 px-4">Pendidikan</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-200">
                @forelse($previewRows as $index => $row)
                <tr class="hover:bg-slate-50">
                    <td class="py-3 px-4 font-mono text-slate-400">{{ $index + 1 }}</td>
                    <td class="py-3 px-4 font-bold text-slate-900">{{ $row['nama'] }}</td>
                    <td class="py-3 px-4 font-mono text-emerald-800 font-semibold">{{ $row['nip'] ?: '-' }}</td>
                    <td class="py-3 px-4 text-slate-700">{{ $row['jabatan'] ?: '-' }}</td>
                    <td class="py-3 px-4 font-mono text-slate-600">{{ $row['golongan'] ?: '-' }}</td>
                    <td class="py-3 px-4 text-slate-600">{{ $row['pendidikan'] ?: '-' }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="py-6 text-center text-slate-500">Tidak ada baris data yang terbaca dari file.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
