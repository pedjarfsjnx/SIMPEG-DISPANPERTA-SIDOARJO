@extends('layouts.admin')

@section('title', 'Dashboard Admin - SIMPEG Dispanperta Sidoarjo')

@section('content')
<div class="space-y-6 text-xs">
    <!-- Header Banner -->
    <div class="bg-white rounded-lg border border-slate-200 p-6 shadow-sm flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <div>
            <span class="px-2.5 py-0.5 bg-emerald-100 text-emerald-800 font-bold rounded text-[10px] uppercase tracking-wider">Akses Administrator</span>
            <h2 class="text-xl font-bold text-slate-900 mt-1">Dashboard Pengelolaan Kepegawaian</h2>
            <p class="text-xs text-slate-500">Ringkasan kontrol administrasi, visualisasi grafik, dan statistik pegawai aktif.</p>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('admin.pegawai.create') }}" class="px-3.5 py-2 bg-emerald-800 hover:bg-emerald-900 text-white text-xs font-semibold rounded shadow-sm whitespace-nowrap">
                + Tambah Pegawai Baru
            </a>
        </div>
    </div>

        <!-- Stats Grid (Interactive Hover & Direct Filtering) -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <a href="{{ route('admin.pegawai.index') }}" class="block bg-white p-5 rounded-2xl border border-slate-200 shadow-2xs border-l-4 border-l-emerald-800 transform transition duration-200 hover:-translate-y-1 hover:shadow-md group">
            <div class="flex justify-between items-start">
                <div class="text-xs font-semibold text-slate-500 uppercase tracking-wider group-hover:text-emerald-800">Total Pegawai Aktif</div>
                <span class="text-[10px] text-emerald-800 font-bold bg-emerald-50 px-2 py-0.5 rounded border border-emerald-200">Kelola &rarr;</span>
            </div>
            <div class="text-3xl font-bold text-slate-900 mt-1">{{ number_format($totalPegawai) }}</div>
            <div class="text-xs text-slate-400 mt-1">Personel dalam database</div>
        </a>

        @foreach($rekapKategori as $kat)
        <a href="{{ route('admin.pegawai.index', ['kategori_id' => $kat->id]) }}" class="block bg-white p-5 rounded-2xl border border-slate-200 shadow-2xs border-l-4 border-l-amber-500 transform transition duration-200 hover:-translate-y-1 hover:shadow-md group">
            <div class="flex justify-between items-start">
                <div class="text-xs font-semibold text-slate-500 uppercase tracking-wider group-hover:text-amber-700">{{ $kat->nama }}</div>
                <span class="text-[10px] text-amber-800 font-bold bg-amber-50 px-2 py-0.5 rounded border border-amber-200">Filter &rarr;</span>
            </div>
            <div class="text-3xl font-bold text-emerald-800 mt-1">{{ number_format($kat->pegawai_count) }}</div>
            <div class="text-xs text-slate-400 mt-1">Lihat anggota {{ $kat->nama }}</div>
        </a>
        @endforeach
    </div>

        @foreach($rekapKategori as $kat)
        <div class="bg-white p-5 rounded-lg border border-slate-200 shadow-sm border-l-4 border-l-amber-500">
            <div class="text-xs font-semibold text-slate-500 uppercase tracking-wider">{{ $kat->nama }}</div>
            <div class="text-3xl font-bold text-emerald-800 mt-1">{{ number_format($kat->pegawai_count) }}</div>
            <div class="text-xs text-slate-400 mt-1">Personel Terdaftar</div>
        </div>
        @endforeach
    </div>

    <!-- Interactive Visual Charts (Chart.js) -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        
        <!-- Chart 1: Donut Komposisi Kategori -->
        <div class="bg-white p-5 rounded-lg border border-slate-200 shadow-sm space-y-4">
            <div class="border-b border-slate-100 pb-2 flex items-center justify-between">
                <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wider">Grafik Komposisi Kategori Pegawai</h3>
                <span class="text-[10px] bg-slate-100 px-2 py-0.5 rounded text-slate-600 font-semibold">Persentase</span>
            </div>
            <div class="relative h-64 flex items-center justify-center">
                <canvas id="adminKategoriChart"></canvas>
            </div>
        </div>

        <!-- Chart 2: Bar Distribusi Unit Kerja -->
        <div class="bg-white p-5 rounded-lg border border-slate-200 shadow-sm space-y-4">
            <div class="border-b border-slate-100 pb-2 flex items-center justify-between">
                <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wider">Grafik Distribusi Per Unit Kerja</h3>
                <span class="text-[10px] bg-slate-100 px-2 py-0.5 rounded text-slate-600 font-semibold">Jumlah Personel</span>
            </div>
            <div class="relative h-64">
                <canvas id="adminUnitChart"></canvas>
            </div>
        </div>

    </div>

    <!-- Quick Access & Formasi Status -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <div class="lg:col-span-2 bg-white rounded-lg border border-slate-200 p-5 shadow-sm space-y-4">
            <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wider border-b border-slate-100 pb-2">
                Distribusi Personel Per Unit Kerja
            </h3>
            <div class="space-y-3">
                @foreach($rekapUnitKerja as $unit)
                <div>
                    <div class="flex justify-between text-xs font-medium text-slate-700 mb-1">
                        <span>{{ $unit->nama }}</span>
                        <span class="font-bold text-emerald-900">{{ $unit->pegawai_count }} Personel</span>
                    </div>
                    <div class="w-full bg-slate-100 rounded-full h-2">
                        <div class="bg-emerald-800 h-2 rounded-full" style="width: {{ $totalPegawai > 0 ? ($unit->pegawai_count / $totalPegawai * 100) : 0 }}%"></div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <div class="bg-white rounded-lg border border-slate-200 p-5 shadow-sm space-y-3">
            <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wider border-b border-slate-100 pb-2">
                Status Formasi Jabatan
            </h3>
            <div class="space-y-3 pt-1">
                <div class="flex items-center justify-between p-3 bg-emerald-50 rounded border border-emerald-200">
                    <span class="font-semibold text-emerald-900">Formasi Terisi</span>
                    <span class="font-bold text-lg text-emerald-900">{{ $formasiTerisiCount }}</span>
                </div>
                <div class="flex items-center justify-between p-3 bg-amber-50 rounded border border-amber-200">
                    <span class="font-semibold text-amber-900">Formasi Kosong</span>
                    <span class="font-bold text-lg text-amber-900">{{ $formasiKosongCount }}</span>
                </div>
            </div>
            <div class="pt-2">
                <a href="{{ route('admin.formasi-jabatan.index') }}" class="block w-full py-2 bg-slate-800 hover:bg-slate-900 text-white font-semibold text-center text-xs rounded shadow-sm">
                    Kelola Formasi Jabatan &rarr;
                </a>
            </div>
        </div>

    </div>
</div>

<!-- Chart.js CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Admin Chart 1: Donut Kategori
        const katCtx = document.getElementById('adminKategoriChart').getContext('2d');
        new Chart(katCtx, {
            type: 'doughnut',
            data: {
                labels: {!! json_encode($rekapKategori->pluck('nama')) !!},
                datasets: [{
                    data: {!! json_encode($rekapKategori->pluck('pegawai_count')) !!},
                    backgroundColor: ['#064e3b', '#166534', '#f59e0b', '#0284c7', '#64748b'],
                    borderWidth: 2,
                    borderColor: '#ffffff'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { position: 'bottom', labels: { font: { family: 'Inter', size: 11 } } }
                }
            }
        });

        // Admin Chart 2: Bar Unit Kerja
        const unitCtx = document.getElementById('adminUnitChart').getContext('2d');
        new Chart(unitCtx, {
            type: 'bar',
            data: {
                labels: {!! json_encode($rekapUnitKerja->pluck('nama')) !!},
                datasets: [{
                    label: 'Jumlah Personel',
                    data: {!! json_encode($rekapUnitKerja->pluck('pegawai_count')) !!},
                    backgroundColor: '#166534',
                    borderRadius: 4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    y: { beginAtZero: true, ticks: { precision: 0 } },
                    x: { ticks: { font: { family: 'Inter', size: 10 } } }
                }
            }
        });
    });
</script>
@endsection
