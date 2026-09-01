@extends('layouts.admin')

@section('title', 'Dashboard Admin - SIMPEG Dispanperta Sidoarjo')

@section('content')
<div class="space-y-5 text-xs">

    <!-- 1. Header & Statistical Overview Section -->
    <div class="bg-white rounded-2xl border border-slate-200/90 p-5 shadow-sm space-y-5">
        
        <!-- Standard Page Header -->
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3 pb-4 border-b border-slate-100">
            <div>
                <h2 class="text-xl font-bold text-slate-900">
                    Dashboard Pengelolaan Kepegawaian
                </h2>
                <p class="text-xs text-slate-500 mt-0.5">
                    Ringkasan kontrol administrasi, ketersediaan formasi jabatan, dan manajemen data pegawai aktif.
                </p>
            </div>

            <!-- Action Buttons -->
            <div class="flex flex-wrap items-center gap-2">
                <a href="{{ route('admin.pegawai.create') }}" 
                   class="inline-flex items-center gap-1.5 px-3.5 py-2 bg-emerald-800 hover:bg-emerald-900 text-white font-semibold text-xs rounded-xl shadow-xs transition whitespace-nowrap">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                    </svg>
                    <span>+ Tambah Pegawai</span>
                </a>
                <a href="{{ route('admin.export.excel') }}" 
                   class="inline-flex items-center gap-1.5 px-3.5 py-2 bg-slate-800 hover:bg-slate-900 text-white font-semibold text-xs rounded-xl shadow-xs transition whitespace-nowrap"
                   title="Export Data Pegawai ke Excel">
                    <svg class="w-3.5 h-3.5 text-emerald-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                    </svg>
                    <span>Export Excel</span>
                </a>
            </div>
        </div>

        <!-- Integrated Key Metrics Hub -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-5 items-stretch">
            
            <!-- Left: Total Pegawai & Formasi Gauge (4 Cols) -->
            <div class="lg:col-span-4 space-y-3 flex flex-col justify-between">
                
                <!-- Main Figure: Total Pegawai -->
                <div class="bg-slate-50/80 rounded-xl p-4 border border-slate-200/80 flex items-center justify-between">
                    <div>
                        <div class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Total Pegawai Aktif</div>
                        <div class="text-3xl font-bold text-slate-900 mt-1">{{ number_format($totalPegawai) }}</div>
                        <div class="text-xs text-slate-500 mt-0.5">Personel terdaftar</div>
                    </div>
                    <a href="{{ route('admin.pegawai.index') }}" class="px-3 py-1.5 bg-emerald-100 hover:bg-emerald-200 text-emerald-800 font-semibold rounded-lg transition text-xs">
                        Kelola &rarr;
                    </a>
                </div>

                <!-- Formasi Gauge -->
                @php
                    $totalFormasi = $formasiTerisiCount + $formasiKosongCount;
                    $pctTerisi = $totalFormasi > 0 ? round(($formasiTerisiCount / $totalFormasi) * 100, 1) : 0;
                @endphp
                <div class="bg-slate-50/80 rounded-xl p-4 border border-slate-200/80 space-y-2.5">
                    <div class="flex items-center justify-between">
                        <span class="text-xs font-semibold text-slate-700 uppercase tracking-wider">Status Formasi Jabatan</span>
                        <span class="text-[10px] font-semibold px-2 py-0.5 bg-emerald-100 text-emerald-800 rounded">{{ $pctTerisi }}% Terisi</span>
                    </div>
                    <div class="grid grid-cols-2 gap-2 text-center">
                        <div class="bg-white p-2 rounded-lg border border-slate-200/80">
                            <div class="text-[10px] text-slate-500 font-medium">Terisi</div>
                            <div class="text-base font-bold text-emerald-800">{{ $formasiTerisiCount }}</div>
                        </div>
                        <div class="bg-white p-2 rounded-lg border border-slate-200/80">
                            <div class="text-[10px] text-slate-500 font-medium">Kosong</div>
                            <div class="text-base font-bold text-amber-700">{{ $formasiKosongCount }}</div>
                        </div>
                    </div>
                    <div class="w-full bg-slate-200 rounded-full h-1.5 overflow-hidden">
                        <div class="bg-emerald-700 h-1.5 rounded-full" style="width: {{ $pctTerisi }}%"></div>
                    </div>
                </div>

            </div>

            <!-- Right: 5 Categories Structured Grid (8 Cols) -->
            <div class="lg:col-span-8 space-y-3">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-semibold text-slate-700 uppercase tracking-wider">
                        Rincian Kategori Kepegawaian
                    </span>
                    <span class="text-[11px] text-slate-500">Filter langsung berdasarkan kategori</span>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-2.5">
                    @foreach($rekapKategori as $kat)
                    @php
                        $percent = $totalPegawai > 0 ? round(($kat->pegawai_count / $totalPegawai) * 100, 1) : 0;
                        $theme = match(strtoupper($kat->nama)) {
                            'PNS' => ['badge' => 'bg-emerald-50 text-emerald-800 border-emerald-200', 'bar' => 'bg-emerald-700'],
                            'PPPK' => ['badge' => 'bg-blue-50 text-blue-800 border-blue-200', 'bar' => 'bg-blue-600'],
                            'PPPK PARUH WAKTU' => ['badge' => 'bg-amber-50 text-amber-800 border-amber-200', 'bar' => 'bg-amber-500'],
                            'SWAKELOLA' => ['badge' => 'bg-purple-50 text-purple-800 border-purple-200', 'bar' => 'bg-purple-600'],
                            'OUTSOURCING' => ['badge' => 'bg-slate-100 text-slate-700 border-slate-200', 'bar' => 'bg-slate-500'],
                            default => ['badge' => 'bg-slate-100 text-slate-700 border-slate-200', 'bar' => 'bg-slate-500']
                        };
                    @endphp
                    <a href="{{ route('admin.pegawai.index', ['kategori_id' => $kat->id]) }}" 
                       class="group block p-3 rounded-xl border border-slate-200/80 bg-slate-50/50 hover:bg-emerald-50/60 hover:border-emerald-300 transition duration-150">
                        <div class="flex items-center justify-between mb-1">
                            <span class="text-[10px] font-semibold px-2 py-0.5 rounded border {{ $theme['badge'] }}">
                                {{ $kat->nama }}
                            </span>
                            <span class="text-[11px] font-medium text-slate-400">{{ $percent }}%</span>
                        </div>
                        <div class="flex items-baseline justify-between mt-1">
                            <span class="text-xl font-bold text-slate-900 group-hover:text-emerald-800 transition">
                                {{ number_format($kat->pegawai_count) }}
                            </span>
                            <span class="text-[11px] text-slate-500 group-hover:text-emerald-800 font-medium">Filter &rarr;</span>
                        </div>
                        <div class="w-full bg-slate-200/80 rounded-full h-1.5 mt-2 overflow-hidden">
                            <div class="{{ $theme['bar'] }} h-1.5 rounded-full" style="width: {{ $percent }}%"></div>
                        </div>
                    </a>
                    @endforeach
                </div>
            </div>

        </div>

    </div>


    <!-- 2. Visual Analytics Deck (Charts) -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-5">
        
        <!-- Donut Kategori -->
        <div class="lg:col-span-5 bg-white p-5 rounded-2xl border border-slate-200/90 shadow-sm flex flex-col justify-between">
            <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                <div>
                    <h3 class="text-xs font-bold text-slate-900 uppercase tracking-wider">Komposisi Aparatur</h3>
                    <p class="text-[11px] text-slate-500 mt-0.5">Proporsi pembagian 5 kategori pegawai</p>
                </div>
                <span class="text-[10px] bg-slate-100 text-slate-700 font-semibold px-2 py-0.5 rounded">
                    Persentase
                </span>
            </div>
            <div class="relative h-64 flex items-center justify-center my-2">
                <canvas id="adminKategoriChart"></canvas>
            </div>
            <div class="text-[11px] text-slate-500 text-center bg-slate-50 py-2 px-3 rounded-lg border border-slate-100">
                Hover pada chart untuk melihat rincian jumlah personel.
            </div>
        </div>

        <!-- Bar Unit Kerja -->
        <div class="lg:col-span-7 bg-white p-5 rounded-2xl border border-slate-200/90 shadow-sm flex flex-col justify-between">
            <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                <div>
                    <h3 class="text-xs font-bold text-slate-900 uppercase tracking-wider">Distribusi Wilayah & Unit Kerja</h3>
                    <p class="text-[11px] text-slate-500 mt-0.5">Sebaran personel di Dinas Induk dan UPTD</p>
                </div>
                <span class="text-[10px] bg-slate-100 text-slate-700 font-semibold px-2 py-0.5 rounded">
                    Jumlah Personel
                </span>
            </div>
            <div class="relative h-64 my-2">
                <canvas id="adminUnitChart"></canvas>
            </div>
            <div class="text-[11px] text-slate-500 text-center bg-slate-50 py-2 px-3 rounded-lg border border-slate-100">
                Grafik menampilkan alokasi personel di seluruh unit dinas.
            </div>
        </div>

    </div>

    <!-- 3. Rincian Unit Kerja Matrix & Pintasan Menu Admin -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-5">
        
        <!-- Unit Kerja List Progress Bars (7 Cols) -->
        <div class="lg:col-span-7 bg-white rounded-2xl border border-slate-200/90 p-5 shadow-sm space-y-4">
            <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                <div>
                    <h3 class="text-xs font-bold text-slate-900 uppercase tracking-wider">
                        Rincian Kekuatan Staf Per Unit Kerja
                    </h3>
                    <p class="text-[11px] text-slate-500 mt-0.5">Klik unit kerja untuk memfilter pegawai di panel admin</p>
                </div>
                <span class="text-[11px] font-bold text-emerald-800 bg-emerald-50 px-2.5 py-1 rounded-lg border border-emerald-200">
                    {{ $rekapUnitKerja->count() }} Unit Kerja
                </span>
            </div>

            <div class="space-y-2">
                @foreach($rekapUnitKerja as $unit)
                @php
                    $pctUnit = $totalPegawai > 0 ? round(($unit->pegawai_count / $totalPegawai) * 100, 1) : 0;
                @endphp
                <a href="{{ route('admin.pegawai.index', ['unit_kerja_id' => $unit->id]) }}" 
                   class="group block p-3 rounded-xl border border-slate-200/70 bg-slate-50/60 hover:bg-emerald-50/70 hover:border-emerald-300 transition duration-150">
                    <div class="flex items-center justify-between mb-1.5">
                        <div class="flex items-center gap-2">
                            <span class="w-2 h-2 rounded-full {{ $unit->tipe === 'dinas' ? 'bg-emerald-700' : 'bg-amber-500' }}"></span>
                            <span class="text-xs font-bold text-slate-800 group-hover:text-emerald-900 transition">
                                {{ $unit->nama }}
                            </span>
                            <span class="text-[10px] px-1.5 py-0.2 bg-white border border-slate-200 text-slate-500 rounded uppercase font-medium">
                                {{ $unit->tipe }}
                            </span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="text-xs font-bold text-slate-900 group-hover:text-emerald-900">
                                {{ $unit->pegawai_count }} Personel
                            </span>
                            <span class="text-[11px] text-slate-400 font-mono">({{ $pctUnit }}%)</span>
                        </div>
                    </div>
                    <div class="w-full bg-slate-200/80 rounded-full h-1.5 overflow-hidden">
                        <div class="bg-emerald-700 h-1.5 rounded-full transition-all duration-300" style="width: {{ $pctUnit }}%"></div>
                    </div>
                </a>
                @endforeach
            </div>
        </div>

        <!-- Quick Administration Shortcuts Hub (5 Cols) -->
        <div class="lg:col-span-5 bg-white rounded-2xl border border-slate-200/90 p-5 shadow-sm space-y-4 flex flex-col justify-between">
            <div>
                <div class="flex items-center justify-between pb-3 border-b border-slate-100 mb-3">
                    <h3 class="text-xs font-bold text-slate-900 uppercase tracking-wider">
                        Pintasan Modul Administrasi
                    </h3>
                    <span class="text-[11px] text-slate-400">Akses Cepat</span>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5">
                    <a href="{{ route('admin.master-data.index') }}" class="p-3 bg-slate-50/80 hover:bg-emerald-50/60 border border-slate-200/80 hover:border-emerald-300 rounded-xl transition flex items-center gap-2.5 group">
                        <div class="w-8 h-8 rounded-lg bg-emerald-100 text-emerald-800 flex items-center justify-center font-bold text-xs">
                            M
                        </div>
                        <div>
                            <div class="font-bold text-slate-800 group-hover:text-emerald-900">Master Data</div>
                            <div class="text-[10px] text-slate-400">Unit, Bidang, Status</div>
                        </div>
                    </a>

                    <a href="{{ route('admin.pensiun.index') }}" class="p-3 bg-slate-50/80 hover:bg-amber-50/60 border border-slate-200/80 hover:border-amber-300 rounded-xl transition flex items-center gap-2.5 group">
                        <div class="w-8 h-8 rounded-lg bg-amber-100 text-amber-800 flex items-center justify-center font-bold text-xs">
                            P
                        </div>
                        <div>
                            <div class="font-bold text-slate-800 group-hover:text-amber-900">Purna Tugas</div>
                            <div class="text-[10px] text-slate-400">Pengajuan Pensiun</div>
                        </div>
                    </a>

                    <a href="{{ route('admin.kenaikan-pangkat.index') }}" class="p-3 bg-slate-50/80 hover:bg-blue-50/60 border border-slate-200/80 hover:border-blue-300 rounded-xl transition flex items-center gap-2.5 group">
                        <div class="w-8 h-8 rounded-lg bg-blue-100 text-blue-800 flex items-center justify-center font-bold text-xs">
                            KP
                        </div>
                        <div>
                            <div class="font-bold text-slate-800 group-hover:text-blue-900">Kenaikan Pangkat</div>
                            <div class="text-[10px] text-slate-400">Periode BKN</div>
                        </div>
                    </a>

                    <a href="{{ route('admin.activity-logs.index') }}" class="p-3 bg-slate-50/80 hover:bg-purple-50/60 border border-slate-200/80 hover:border-purple-300 rounded-xl transition flex items-center gap-2.5 group">
                        <div class="w-8 h-8 rounded-lg bg-purple-100 text-purple-800 flex items-center justify-center font-bold text-xs">
                            LOG
                        </div>
                        <div>
                            <div class="font-bold text-slate-800 group-hover:text-purple-900">Audit Logs</div>
                            <div class="text-[10px] text-slate-400">Riwayat Aktivitas</div>
                        </div>
                    </a>
                </div>
            </div>

            <!-- Footer Action Link -->
            <div class="pt-3 border-t border-slate-100">
                <a href="{{ route('public.dashboard') }}" target="_blank" class="block w-full py-2.5 bg-slate-800 hover:bg-slate-900 text-emerald-300 font-semibold text-center rounded-xl transition shadow-xs text-xs">
                    Buka Tampilan Website Publik &rarr;
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
                    backgroundColor: ['#047857', '#3b82f6', '#f59e0b', '#8b5cf6', '#64748b'],
                    borderWidth: 2,
                    borderColor: '#ffffff',
                    hoverOffset: 3
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { 
                        position: 'bottom', 
                        labels: { 
                            font: { family: 'Inter', size: 11 },
                            padding: 10,
                            usePointStyle: true,
                            boxWidth: 8
                        } 
                    }
                },
                cutout: '65%'
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
                    backgroundColor: '#15803d',
                    hoverBackgroundColor: '#166534',
                    borderRadius: 6,
                    borderSkipped: false
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false }
                },
                scales: {
                    y: { 
                        beginAtZero: true, 
                        ticks: { precision: 0, font: { family: 'Inter', size: 10 } },
                        grid: { color: '#f1f5f9' }
                    },
                    x: { 
                        ticks: { font: { family: 'Inter', size: 10 } },
                        grid: { display: false }
                    }
                }
            }
        });
    });
</script>
@endsection


