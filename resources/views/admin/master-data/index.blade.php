@extends('layouts.admin')

@section('title', 'Master Data - Admin SIMPEG')

@section('content')
<div class="space-y-6 text-xs">
    <div>
        <h2 class="text-xl font-bold text-slate-900">Kelola Master Data</h2>
        <p class="text-slate-500">Pengaturan Master Kategori Pegawai, Status, Unit Kerja, dan Bidang.</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        
        <!-- Unit Kerja Card -->
        <div class="bg-white p-5 rounded-lg border border-slate-200 shadow-sm space-y-4">
            <h3 class="font-bold text-slate-900 text-sm border-b pb-2 uppercase tracking-wider">Master Unit Kerja</h3>
            <ul class="divide-y divide-slate-100">
                @foreach($unitKerjaList as $u)
                <li class="py-2 flex justify-between items-center">
                    <span class="font-medium text-slate-800">{{ $u->nama }} <span class="text-slate-400">({{ $u->tipe }})</span></span>
                    <span class="text-slate-500 font-semibold">{{ $u->pegawai_count }} Pegawai</span>
                </li>
                @endforeach
            </ul>
        </div>

        <!-- Bidang Card -->
        <div class="bg-white p-5 rounded-lg border border-slate-200 shadow-sm space-y-4">
            <h3 class="font-bold text-slate-900 text-sm border-b pb-2 uppercase tracking-wider">Master Bidang</h3>
            <ul class="divide-y divide-slate-100">
                @foreach($bidangList as $b)
                <li class="py-2 flex justify-between items-center">
                    <span class="font-medium text-slate-800">{{ $b->nama }}</span>
                    <span class="text-slate-500 font-semibold">{{ $b->pegawai_count }} Pegawai</span>
                </li>
                @endforeach
            </ul>
        </div>

        <!-- Kategori Card -->
        <div class="bg-white p-5 rounded-lg border border-slate-200 shadow-sm space-y-4">
            <h3 class="font-bold text-slate-900 text-sm border-b pb-2 uppercase tracking-wider">Master Kategori Pegawai</h3>
            <ul class="divide-y divide-slate-100">
                @foreach($kategoriList as $k)
                <li class="py-2 flex justify-between items-center">
                    <span class="font-medium text-slate-800">{{ $k->nama }}</span>
                    <span class="text-slate-500 font-semibold">{{ $k->pegawai_count }} Pegawai</span>
                </li>
                @endforeach
            </ul>
        </div>

        <!-- Status Card -->
        <div class="bg-white p-5 rounded-lg border border-slate-200 shadow-sm space-y-4">
            <h3 class="font-bold text-slate-900 text-sm border-b pb-2 uppercase tracking-wider">Master Status Kepegawaian</h3>
            <ul class="divide-y divide-slate-100">
                @foreach($statusList as $s)
                <li class="py-2 flex justify-between items-center">
                    <span class="font-medium text-slate-800">{{ $s->nama }}</span>
                    <span class="text-slate-500 font-semibold">{{ $s->pegawai_count }} Pegawai</span>
                </li>
                @endforeach
            </ul>
        </div>

    </div>
</div>
@endsection
