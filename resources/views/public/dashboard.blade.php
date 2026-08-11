@extends('layouts.public')

@section('title', 'Dashboard - SIMPEG Dinas Pangan dan Pertanian Kabupaten Sidoarjo')

@section('content')
<div class="space-y-6">
    <!-- Header Banner -->
    <div class="bg-white rounded-lg border border-slate-200 p-6 shadow-sm flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <div>
            <span class="px-2.5 py-0.5 bg-emerald-100 text-emerald-800 font-bold rounded text-[10px] uppercase tracking-wider">Dashboard Real-time</span>
            <h2 class="text-xl font-bold text-slate-900 mt-1">Dashboard Kepegawaian</h2>
            <p class="text-xs text-slate-500">Ringkasan visual statistik dan grafik komposisi data pegawai aktif Dinas Pangan dan Pertanian Kabupaten Sidoarjo.</p>
        </div>
        <a href="{{ route('public.pegawai.index') }}" class="px-3.5 py-2 bg-emerald-800 hover:bg-emerald-900 text-white text-xs font-semibold rounded transition shadow-sm whitespace-nowrap">
            Lihat Direktori Pegawai &rarr;
        </a>
    </div>

    <!-- Summary Stats Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        <div class="bg-white p-5 rounded-lg border border-slate-200 shadow-sm border-l-4 border-l-emerald-800">
            <div class="text-xs font-semibold text-slate-500 uppercase tracking-wider">Total Pegawai Aktif</div>
            <div class="text-3xl font-bold text-slate-900 mt-1">{{ number_format($totalPegawai) }}</div>
            <div class="text-xs text-slate-500 mt-1">Terdaftar di database</div>
        </div>

        @foreach($rekapKategori as $kat)
        <div class="bg-white p-5 rounded-lg border border-slate-200 shadow-sm border-l-4 border-l-amber-500">
            <div class="text-xs font-semibold text-slate-500 uppercase tracking-wider">{{ $kat->nama }}</div>
            <div class="text-3xl font-bold text-emerald-800 mt-1">{{ number_format($kat->pegawai_count) }}</div>
            <div class="text-xs text-slate-400 mt-1">Personel Resmi</div>
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
                <canvas id="kategoriChart"></canvas>
            </div>
        </div>

        <!-- Chart 2: Bar Distribusi Unit Kerja -->
        <div class="bg-white p-5 rounded-lg border border-slate-200 shadow-sm space-y-4">
            <div class="border-b border-slate-100 pb-2 flex items-center justify-between">
                <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wider">Grafik Distribusi Per Unit Kerja</h3>
                <span class="text-[10px] bg-slate-100 px-2 py-0.5 rounded text-slate-600 font-semibold">Jumlah Personel</span>
            </div>
            <div class="relative h-64">
                <canvas id="unitChart"></canvas>
            </div>
        </div>

    </div>

    <!-- Grid Rekap Detail & Widgets -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- Column 1 & 2: Unit Kerja & Status -->
        <div class="lg:col-span-2 space-y-6">
            
            <!-- Card Unit Kerja -->
            <div class="bg-white rounded-lg border border-slate-200 p-5 shadow-sm space-y-4">
                <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wider border-b border-slate-100 pb-2">
                    Rekapitulasi Unit Kerja & UPTD
                </h3>
                <div class="space-y-3">
                    @foreach($rekapUnitKerja as $unit)
                    <div>
                        <div class="flex justify-between text-xs font-medium text-slate-700 mb-1">
                            <span>{{ $unit->nama }} <span class="text-slate-400">({{ $unit->tipe }})</span></span>
                            <span class="font-bold text-emerald-900">{{ $unit->pegawai_count }} Pegawai</span>
                        </div>
                        <div class="w-full bg-slate-100 rounded-full h-2">
                            <div class="bg-emerald-800 h-2 rounded-full" style="width: {{ $totalPegawai > 0 ? ($unit->pegawai_count / $totalPegawai * 100) : 0 }}%"></div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Card Status Kepegawaian -->
            <div class="bg-white rounded-lg border border-slate-200 p-5 shadow-sm space-y-4">
                <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wider border-b border-slate-100 pb-2">
                    Status Kepegawaian
                </h3>
                <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                    @foreach($rekapStatus as $st)
                    <div class="bg-slate-50 border border-slate-200 p-3 rounded text-center">
                        <div class="text-xs text-slate-500 font-medium">{{ $st->nama }}</div>
                        <div class="text-lg font-bold text-slate-900 mt-0.5">{{ $st->pegawai_count }}</div>
                    </div>
                    @endforeach
                </div>
            </div>

        </div>

        <!-- Column 3: Informational Widgets -->
        <div class="space-y-6">
            
            <!-- Widget Usulan Pensiun -->
            <div class="bg-white rounded-lg border border-slate-200 p-5 shadow-sm space-y-3">
                <div class="flex items-center justify-between border-b border-slate-100 pb-2">
                    <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wider">Usulan Pensiun</h3>
                    <span class="text-[10px] font-semibold uppercase px-2 py-0.5 bg-slate-100 text-slate-600 rounded">Info</span>
                </div>
                @if($pensiunMendatang->count() > 0)
                <div class="divide-y divide-slate-100 text-xs">
                    @foreach($pensiunMendatang as $pensiun)
                    <div class="py-2.5 first:pt-0 last:pb-0">
                        <div class="font-bold text-slate-900">{{ $pensiun->pegawai?->nama ?? 'Pegawai' }}</div>
                        <div class="text-slate-500 text-[11px] mt-0.5">Unit: {{ $pensiun->pegawai?->unitKerja?->nama ?? '-' }}</div>
                        <div class="text-amber-700 font-semibold text-[11px] mt-0.5">
                            TMT Pensiun: {{ \Carbon\Carbon::parse($pensiun->tmt_pensiun)->format('d/m/Y') }}
                        </div>
                    </div>
                    @endforeach
                </div>
                @else
                <p class="text-xs text-slate-400 italic">Belum ada data usulan pensiun.</p>
                @endif
            </div>

            <!-- Widget Usulan Kenaikan Pangkat -->
            <div class="bg-white rounded-lg border border-slate-200 p-5 shadow-sm space-y-3">
                <div class="flex items-center justify-between border-b border-slate-100 pb-2">
                    <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wider">Usulan Naik Pangkat</h3>
                    <span class="text-[10px] font-semibold uppercase px-2 py-0.5 bg-slate-100 text-slate-600 rounded">Info</span>
                </div>
                @if($kpMendatang->count() > 0)
                <div class="divide-y divide-slate-100 text-xs">
                    @foreach($kpMendatang as $kp)
                    <div class="py-2.5 first:pt-0 last:pb-0">
                        <div class="font-bold text-slate-900">{{ $kp->pegawai?->nama ?? 'Pegawai' }}</div>
                        <div class="text-slate-500 text-[11px] mt-0.5">
                            Usulan Gol: <span class="font-mono text-slate-700">{{ $kp->golongan_baru ?? '-' }}</span>
                        </div>
                        <div class="text-emerald-800 font-semibold text-[11px] mt-0.5">
                            TMT Diusulkan: {{ \Carbon\Carbon::parse($kp->tmt_diusulkan)->format('d/m/Y') }}
                        </div>
                    </div>
                    @endforeach
                </div>
                @else
                <p class="text-xs text-slate-400 italic">Belum ada data usulan kenaikan pangkat.</p>
                @endif
            </div>

        </div>

    </div>
</div>

<!-- Chart.js CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Chart 1: Donut Kategori
        const katCtx = document.getElementById('kategoriChart').getContext('2d');
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

        // Chart 2: Bar Unit Kerja
        const unitCtx = document.getElementById('unitChart').getContext('2d');
        new Chart(unitCtx, {
            type: 'bar',
            data: {
                labels: {!! json_encode($rekapUnitKerja->pluck('nama')) !!},
                datasets: [{
                    label: 'Jumlah Pegawai',
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
