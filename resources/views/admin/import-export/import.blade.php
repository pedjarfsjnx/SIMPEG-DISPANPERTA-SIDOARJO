@extends('layouts.admin')

@section('title', 'Import Data Excel - Admin SIMPEG')

@section('content')
<div class="max-w-2xl mx-auto space-y-5 text-xs">
    <div>
        <h2 class="text-xl font-bold text-slate-900">Import Data Pegawai dari Excel</h2>
        <p class="text-slate-500">Unggah file Excel (.xlsx / .xls) untuk memperbarui atau menambah data pegawai secara massal.</p>
    </div>

    @if(session('error'))
    <div class="p-3.5 bg-rose-50 border border-rose-200 text-rose-800 rounded font-medium">
        {{ session('error') }}
    </div>
    @endif

    <form method="POST" action="{{ route('admin.import.preview') }}" enctype="multipart/form-data" class="bg-white p-6 rounded-lg border border-slate-200 shadow-sm space-y-4">
        @csrf
        
        <div>
            <label class="block font-semibold text-slate-700 mb-1">Pilih File Excel (.xlsx / .xls)</label>
            <input type="file" name="excel_file" required accept=".xlsx,.xls,.csv" class="w-full text-xs border border-slate-300 rounded p-2">
        </div>

        <div class="bg-slate-50 border border-slate-200 text-slate-700 p-3.5 rounded text-[11px] space-y-1">
            <p class="font-bold text-slate-900 uppercase">Catatan Validasi Import:</p>
            <ul class="list-disc list-inside space-y-0.5 text-slate-600">
                <li>Header kop surat pada Excel akan dibersihkan secara otomatis.</li>
                <li>NIP dengan tanda petik (contoh `'1980...`) akan dinormalisasi.</li>
                <li>Setelah unggah, Anda dapat **memeriksa pratinjau (preview)** sebelum data disimpan permanen.</li>
            </ul>
        </div>

        <div class="pt-2 flex justify-end">
            <button type="submit" class="px-5 py-2.5 bg-emerald-800 hover:bg-emerald-900 text-white font-semibold rounded shadow-sm">
                Pratinjau Data (Preview) &rarr;
            </button>
        </div>
    </form>
</div>
@endsection
